import { Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../database/prisma.service';
import { CreateTestimonialDto, UpdateTestimonialDto } from './dto/testimonial.dto';

@Injectable()
export class TestimonialsService {
  constructor(private prisma: PrismaService) {}

  async findAll(includeUnapproved = false) {
    return this.prisma.testimonial.findMany({
      where: includeUnapproved ? {} : { isApproved: true },
      orderBy: { sortOrder: 'asc' },
    });
  }

  async findOne(id: number) {
    const testimonial = await this.prisma.testimonial.findUnique({ where: { id } });
    if (!testimonial) {
      throw new NotFoundException(`Testimonial with ID ${id} not found`);
    }
    return testimonial;
  }

  async create(dto: CreateTestimonialDto) {
    return this.prisma.testimonial.create({ data: dto });
  }

  async update(id: number, dto: UpdateTestimonialDto) {
    await this.findOne(id);
    return this.prisma.testimonial.update({
      where: { id },
      data: dto,
    });
  }

  async remove(id: number) {
    await this.findOne(id);
    return this.prisma.testimonial.delete({ where: { id } });
  }
}
