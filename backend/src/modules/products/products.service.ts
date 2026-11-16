import {
  Injectable,
  NotFoundException,
  ConflictException,
} from '@nestjs/common';
import { PrismaService } from '../../database/prisma.service';
import { CreateProductDto, UpdateProductDto, ProductQueryDto } from './dto/product.dto';
import { ProductStatus } from '@prisma/client';

function slugify(text: string): string {
  return text
    .toString()
    .toLowerCase()
    .trim()
    .replace(/\s+/g, '-')
    .replace(/[^\w\-]+/g, '')
    .replace(/\-\-+/g, '-');
}

@Injectable()
export class ProductsService {
  constructor(private prisma: PrismaService) {}

  async findAll(query: ProductQueryDto) {
    const {
      page = 1,
      limit = 20,
      search,
      categoryId,
      categorySlug,
      collectionId,
      collectionSlug,
      status,
      metalType,
      goldPurity,
      minPrice,
      maxPrice,
      isFeatured,
      sortBy = 'createdAt',
      sortOrder = 'desc',
    } = query;

    const skip = (Number(page) - 1) * Number(limit);

    const where: any = {};
    if (search) {
      where.OR = [
        { name: { contains: search, mode: 'insensitive' } },
        { sku: { contains: search, mode: 'insensitive' } },
        { shortDescription: { contains: search, mode: 'insensitive' } },
        { description: { contains: search, mode: 'insensitive' } },
      ];
    }

    if (categoryId) where.categoryId = Number(categoryId);
    if (categorySlug) {
      where.category = { slug: categorySlug };
    }
    if (collectionId) where.collectionId = Number(collectionId);
    if (collectionSlug) {
      where.collection = { slug: collectionSlug };
    }
    if (status) where.status = status;
    if (metalType) where.metalType = metalType;
    if (goldPurity) where.goldPurity = goldPurity;
    if (isFeatured !== undefined) where.isFeatured = isFeatured;

    if (minPrice !== undefined || maxPrice !== undefined) {
      where.price = {};
      if (minPrice !== undefined) where.price.gte = Number(minPrice);
      if (maxPrice !== undefined) where.price.lte = Number(maxPrice);
    }

    // Sort mapping
    let orderBy: any = { [sortBy]: sortOrder };
    if (sortBy === 'price_low') {
      orderBy = { price: 'asc' };
    } else if (sortBy === 'price_high') {
      orderBy = { price: 'desc' };
    } else if (sortBy === 'latest') {
      orderBy = { createdAt: 'desc' };
    }

    const [total, items] = await Promise.all([
      this.prisma.product.count({ where }),
      this.prisma.product.findMany({
        where,
        skip,
        take: Number(limit),
        orderBy,
        include: {
          category: { select: { id: true, name: true, slug: true } },
          collection: { select: { id: true, name: true, slug: true } },
          images: { orderBy: { sortOrder: 'asc' } },
        },
      }),
    ]);

    return {
      data: items,
      meta: {
        total,
        page: Number(page),
        limit: Number(limit),
        totalPages: Math.ceil(total / Number(limit)),
      },
    };
  }

  async findOne(slug: string) {
    const product = await this.prisma.product.findUnique({
      where: { slug },
      include: {
        category: true,
        collection: true,
        images: { orderBy: { sortOrder: 'asc' } },
      },
    });

    if (!product) throw new NotFoundException(`Product '${slug}' not found`);

    // Fetch related products (same category, published, limit 4)
    const relatedProducts = await this.prisma.product.findMany({
      where: {
        categoryId: product.categoryId,
        status: ProductStatus.published,
        NOT: { id: product.id },
      },
      take: 4,
      orderBy: { createdAt: 'desc' },
      include: {
        category: { select: { id: true, name: true, slug: true } },
        images: { orderBy: { sortOrder: 'asc' } },
      },
    });

    return {
      ...product,
      relatedProducts,
    };
  }

  async findById(id: number) {
    const product = await this.prisma.product.findUnique({
      where: { id },
      include: {
        category: { select: { id: true, name: true, slug: true } },
        collection: { select: { id: true, name: true, slug: true } },
        images: { orderBy: { sortOrder: 'asc' } },
      },
    });

    if (!product) throw new NotFoundException(`Product #${id} not found`);
    return product;
  }

  async create(dto: CreateProductDto) {
    const slug = dto.slug ? slugify(dto.slug) : slugify(dto.name);
    const existing = await this.prisma.product.findFirst({
      where: { OR: [{ slug }, { sku: dto.sku }] },
    });
    if (existing) {
      throw new ConflictException('Product with this slug or SKU already exists');
    }

    const { images, ...productData } = dto;

    return this.prisma.product.create({
      data: {
        ...productData,
        slug,
        images: images && images.length > 0 ? {
          create: images.map((img, idx) => ({
            image: img.image,
            altText: img.altText || null,
            isPrimary: img.isPrimary ?? (idx === 0),
            sortOrder: img.sortOrder ?? idx,
          })),
        } : undefined,
      },
      include: { category: true, collection: true, images: true },
    });
  }

  async update(id: number, dto: UpdateProductDto) {
    await this.findById(id);

    const slug = dto.slug ? slugify(dto.slug) : dto.name ? slugify(dto.name) : undefined;
    if (slug || dto.sku) {
      const existing = await this.prisma.product.findFirst({
        where: {
          AND: [
            { id: { not: id } },
            {
              OR: [
                ...(slug ? [{ slug }] : []),
                ...(dto.sku ? [{ sku: dto.sku }] : []),
              ],
            },
          ],
        },
      });
      if (existing) throw new ConflictException('Slug or SKU already in use');
    }

    const { images, ...productData } = dto;

    // Transaction to update product and replace images if provided
    return this.prisma.$transaction(async (tx) => {
      if (images !== undefined) {
        await tx.productImage.deleteMany({ where: { productId: id } });
        if (images.length > 0) {
          await tx.productImage.createMany({
            data: images.map((img, idx) => ({
              productId: id,
              image: img.image,
              altText: img.altText || null,
              isPrimary: img.isPrimary ?? (idx === 0),
              sortOrder: img.sortOrder ?? idx,
            })),
          });
        }
      }

      return tx.product.update({
        where: { id },
        data: {
          ...productData,
          ...(slug ? { slug } : {}),
        },
        include: { category: true, collection: true, images: true },
      });
    });
  }

  async remove(id: number) {
    await this.findById(id);
    await this.prisma.product.delete({ where: { id } });
    return { message: 'Product deleted successfully' };
  }

  async getFeatured() {
    return this.prisma.product.findMany({
      where: { isFeatured: true, status: ProductStatus.published },
      include: {
        category: { select: { id: true, name: true, slug: true } },
        images: { orderBy: { sortOrder: 'asc' } },
      },
      orderBy: { sortOrder: 'asc' },
      take: 8,
    });
  }

  async getNewArrivals() {
    return this.prisma.product.findMany({
      where: { status: ProductStatus.published },
      include: {
        category: { select: { id: true, name: true, slug: true } },
        images: { orderBy: { sortOrder: 'asc' } },
      },
      orderBy: { createdAt: 'desc' },
      take: 8,
    });
  }
}
