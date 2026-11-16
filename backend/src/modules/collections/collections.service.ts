import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../../database/prisma.service';
import { CreateCollectionDto, UpdateCollectionDto } from './dto/collection.dto';
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
export class CollectionsService {
  constructor(private prisma: PrismaService) {}

  async findAll(includeInactive = false) {
    return this.prisma.collection.findMany({
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
    const collection = await this.prisma.collection.findUnique({
      where: { slug },
      include: {
        products: {
          where: { status: 'published' },
          include: {
            images: { orderBy: { sortOrder: 'asc' } },
            category: true,
          },
        },
        _count: {
          select: { products: true },
        },
      },
    });
    if (!collection) {
      throw new NotFoundException(`Collection with slug "${slug}" not found`);
    }
    return collection;
  }

  async findOne(id: number) {
    const collection = await this.prisma.collection.findUnique({
      where: { id },
      include: {
        _count: {
          select: { products: true },
        },
      },
    });
    if (!collection) {
      throw new NotFoundException(`Collection with ID ${id} not found`);
    }
    return collection;
  }

  async create(dto: CreateCollectionDto) {
    const slug = dto.slug ? slugify(dto.slug) : slugify(dto.name);
    const existing = await this.prisma.collection.findUnique({ where: { slug } });
    if (existing) {
      throw new BadRequestException(`Collection with slug "${slug}" already exists`);
    }

    return this.prisma.collection.create({
      data: {
        ...dto,
        slug,
      },
    });
  }

  async update(id: number, dto: UpdateCollectionDto) {
    await this.findOne(id);
    const data: any = { ...dto };
    if (dto.slug) {
      data.slug = slugify(dto.slug);
      const existing = await this.prisma.collection.findFirst({
        where: { slug: data.slug, NOT: { id } },
      });
      if (existing) {
        throw new BadRequestException(`Collection with slug "${data.slug}" already exists`);
      }
    }

    return this.prisma.collection.update({
      where: { id },
      data,
    });
  }

  async remove(id: number) {
    await this.findOne(id);
    return this.prisma.collection.delete({ where: { id } });
  }
}
