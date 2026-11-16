import { Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../database/prisma.service';
import { CreateGalleryDto, UpdateGalleryDto } from './dto/gallery.dto';
import { ContentStatus } from '@prisma/client';

@Injectable()
export class GalleriesService {
  constructor(private prisma: PrismaService) {}

  async findAll(includeInactive = false) {
    return this.prisma.gallery.findMany({
      where: includeInactive ? {} : { status: ContentStatus.active },
      orderBy: { sortOrder: 'asc' },
    });
  }

  async findOne(id: number) {
    const item = await this.prisma.gallery.findUnique({ where: { id } });
    if (!item) {
      throw new NotFoundException(`Gallery item with ID ${id} not found`);
    }
    return item;
  }

  async create(dto: CreateGalleryDto) {
    return this.prisma.gallery.create({ data: dto });
  }

  async update(id: number, dto: UpdateGalleryDto) {
    await this.findOne(id);
    return this.prisma.gallery.update({
      where: { id },
      data: dto,
    });
  }

  async remove(id: number) {
    await this.findOne(id);
    return this.prisma.gallery.delete({ where: { id } });
  }
}
