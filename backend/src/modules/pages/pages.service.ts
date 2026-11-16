import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../../database/prisma.service';
import { CreatePageDto, UpdatePageDto } from './dto/page.dto';

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
export class PagesService {
  constructor(private prisma: PrismaService) {}

  async findAll() {
    return this.prisma.page.findMany({
      orderBy: { title: 'asc' },
    });
  }

  async findBySlug(slug: string) {
    const page = await this.prisma.page.findUnique({ where: { slug } });
    if (!page) {
      throw new NotFoundException(`Page "${slug}" not found`);
    }
    return page;
  }

  async findOne(id: number) {
    const page = await this.prisma.page.findUnique({ where: { id } });
    if (!page) {
      throw new NotFoundException(`Page with ID ${id} not found`);
    }
    return page;
  }

  async create(dto: CreatePageDto) {
    const slug = dto.slug ? slugify(dto.slug) : slugify(dto.title);
    const existing = await this.prisma.page.findUnique({ where: { slug } });
    if (existing) {
      throw new BadRequestException(`Page with slug "${slug}" already exists`);
    }

    return this.prisma.page.create({
      data: {
        ...dto,
        slug,
      },
    });
  }

  async update(id: number, dto: UpdatePageDto) {
    await this.findOne(id);
    const data: any = { ...dto };
    if (dto.slug) {
      data.slug = slugify(dto.slug);
      const existing = await this.prisma.page.findFirst({
        where: { slug: data.slug, NOT: { id } },
      });
      if (existing) {
        throw new BadRequestException(`Page with slug "${data.slug}" already exists`);
      }
    }

    return this.prisma.page.update({
      where: { id },
      data,
    });
  }

  async remove(id: number) {
    await this.findOne(id);
    return this.prisma.page.delete({ where: { id } });
  }
}
