import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../../database/prisma.service';
import { CreateCategoryDto, UpdateCategoryDto, ReorderCategoriesDto } from './dto/category.dto';
import { ContentStatus } from '@prisma/client';

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
export class CategoriesService {
  constructor(private prisma: PrismaService) {}

  async findAll(includeInactive = false) {
    return this.prisma.category.findMany({
      where: includeInactive ? {} : { status: ContentStatus.active },
      orderBy: { sortOrder: 'asc' },
      include: {
        _count: {
          select: { products: true },
        },
      },
    });
  }

  async findBySlug(slug: string) {
    const category = await this.prisma.category.findUnique({
      where: { slug },
      include: {
        _count: {
          select: { products: true },
        },
      },
    });
    if (!category) {
      throw new NotFoundException(`Category with slug "${slug}" not found`);
    }
    return category;
  }

  async findOne(id: number) {
    const category = await this.prisma.category.findUnique({
      where: { id },
      include: {
        _count: {
          select: { products: true },
        },
      },
    });
    if (!category) {
      throw new NotFoundException(`Category with ID ${id} not found`);
    }
    return category;
  }

  async create(dto: CreateCategoryDto) {
    const slug = dto.slug ? slugify(dto.slug) : slugify(dto.name);
    const existing = await this.prisma.category.findUnique({ where: { slug } });
    if (existing) {
      throw new BadRequestException(`Category with slug "${slug}" already exists`);
    }

    return this.prisma.category.create({
      data: {
        ...dto,
        slug,
      },
    });
  }

  async update(id: number, dto: UpdateCategoryDto) {
    await this.findOne(id);
    const data: any = { ...dto };
    if (dto.slug) {
      data.slug = slugify(dto.slug);
      const existing = await this.prisma.category.findFirst({
        where: { slug: data.slug, NOT: { id } },
      });
      if (existing) {
        throw new BadRequestException(`Category with slug "${data.slug}" already exists`);
      }
    }

    return this.prisma.category.update({
      where: { id },
      data,
    });
  }

  async remove(id: number) {
    const category = await this.findOne(id);
    const productCount = await this.prisma.product.count({ where: { categoryId: id } });
    if (productCount > 0) {
      throw new BadRequestException(
        `Cannot delete category "${category.name}" because it contains ${productCount} product(s). Please reassign or delete products first.`,
      );
    }
    return this.prisma.category.delete({ where: { id } });
  }

  async reorder(dto: ReorderCategoriesDto) {
    const updates = dto.ids.map((id, index) =>
      this.prisma.category.update({
        where: { id },
        data: { sortOrder: index },
      }),
    );
    await this.prisma.$transaction(updates);
    return { success: true };
  }
}
