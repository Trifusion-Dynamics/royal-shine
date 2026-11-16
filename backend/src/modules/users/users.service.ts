import {
  Injectable,
  NotFoundException,
  BadRequestException,
  ForbiddenException,
} from '@nestjs/common';
import { PrismaService } from '../../database/prisma.service';
import { CreateUserDto, UpdateUserDto } from './dto/user.dto';
import * as bcrypt from 'bcryptjs';
import { UserRole } from '@prisma/client';

@Injectable()
export class UsersService {
  constructor(private prisma: PrismaService) {}

  async findAll() {
    return this.prisma.user.findMany({
      orderBy: { createdAt: 'desc' },
      select: {
        id: true,
        name: true,
        email: true,
        role: true,
        isAdmin: true,
        createdAt: true,
        updatedAt: true,
      },
    });
  }

  async findOne(id: number) {
    const user = await this.prisma.user.findUnique({
      where: { id },
      select: {
        id: true,
        name: true,
        email: true,
        role: true,
        isAdmin: true,
        createdAt: true,
        updatedAt: true,
      },
    });
    if (!user) {
      throw new NotFoundException(`User with ID ${id} not found`);
    }
    return user;
  }

  async create(dto: CreateUserDto) {
    const existing = await this.prisma.user.findUnique({
      where: { email: dto.email.toLowerCase().trim() },
    });
    if (existing) {
      throw new BadRequestException('A user with this email already exists');
    }

    const hashedPassword = await bcrypt.hash(dto.password, 10);

    return this.prisma.user.create({
      data: {
        name: dto.name,
        email: dto.email.toLowerCase().trim(),
        password: hashedPassword,
        role: dto.role,
        isAdmin: true,
      },
      select: {
        id: true,
        name: true,
        email: true,
        role: true,
        isAdmin: true,
        createdAt: true,
      },
    });
  }

  async update(id: number, dto: UpdateUserDto) {
    await this.findOne(id);
    const data: any = {};
    if (dto.name) data.name = dto.name;
    if (dto.role) data.role = dto.role;

    if (dto.email) {
      const email = dto.email.toLowerCase().trim();
      const existing = await this.prisma.user.findFirst({
        where: { email, NOT: { id } },
      });
      if (existing) {
        throw new BadRequestException('A user with this email already exists');
      }
      data.email = email;
    }

    if (dto.password) {
      data.password = await bcrypt.hash(dto.password, 10);
    }

    return this.prisma.user.update({
      where: { id },
      data,
      select: {
        id: true,
        name: true,
        email: true,
        role: true,
        isAdmin: true,
        updatedAt: true,
      },
    });
  }

  async remove(id: number, currentUserId: number) {
    if (id === currentUserId) {
      throw new ForbiddenException('You cannot delete your own account');
    }

    const targetUser = await this.findOne(id);
    if (targetUser.role === UserRole.super_admin) {
      const superAdminCount = await this.prisma.user.count({
        where: { role: UserRole.super_admin },
      });
      if (superAdminCount <= 1) {
        throw new ForbiddenException('Cannot delete the last super admin account');
      }
    }

    return this.prisma.user.delete({ where: { id } });
  }
}
