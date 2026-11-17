import { Injectable, UnauthorizedException, Logger, OnModuleInit } from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import { UserRole } from '@prisma/client';
import { PrismaService } from '../../database/prisma.service';
import { LoginDto } from './dto/login.dto';
import * as argon2 from 'argon2';
import * as bcrypt from 'bcryptjs';

@Injectable()
export class AuthService implements OnModuleInit {
  private readonly logger = new Logger(AuthService.name);

  constructor(
    private prisma: PrismaService,
    private jwtService: JwtService,
  ) {}

  async onModuleInit() {
    const email = process.env.ADMIN_EMAIL?.trim().toLowerCase();
    const password = process.env.ADMIN_PASSWORD;

    if (!email || !password) {
      this.logger.warn('ADMIN_EMAIL/ADMIN_PASSWORD not set; automatic first-admin provisioning skipped.');
      return;
    }
    if (password.length < 16) {
      throw new Error('ADMIN_PASSWORD must contain at least 16 characters.');
    }

    await this.prisma.$connect();
    const existing = await this.prisma.user.findUnique({ where: { email } });
    if (existing) {
      this.logger.log(`Configured admin account already exists: ${email}; password was not changed.`);
      return;
    }

    const passwordHash = await bcrypt.hash(password, 12);
    await this.prisma.user.create({
      data: {
        name: process.env.ADMIN_NAME?.trim() || 'Royal Shine Administrator',
        email,
        password: passwordHash,
        isAdmin: true,
        role: UserRole.super_admin,
        emailVerifiedAt: new Date(),
      },
    });
    this.logger.log(`Initial super-admin account provisioned: ${email}`);
  }

  async login(loginDto: LoginDto) {
    const email = loginDto.email.trim().toLowerCase();
    const user = await this.prisma.user.findUnique({ where: { email } });

    if (!user || (!user.isAdmin && user.role !== UserRole.super_admin && user.role !== UserRole.admin)) {
      throw new UnauthorizedException('Invalid credentials');
    }

    let valid = false;
    try {
      valid = await argon2.verify(user.password, loginDto.password);
    } catch {
      try {
        valid = await bcrypt.compare(loginDto.password, user.password);
      } catch {
        valid = false;
      }
    }

    if (!valid) {
      throw new UnauthorizedException('Invalid credentials');
    }

    const payload = { sub: user.id, email: user.email, role: user.role, isAdmin: user.isAdmin };
    const token = await this.jwtService.signAsync(payload);

    return {
      access_token: token,
      user: {
        id: user.id,
        name: user.name,
        email: user.email,
        role: user.role,
        isAdmin: user.isAdmin,
      },
    };
  }

  async getMe(userId: number) {
    const user = await this.prisma.user.findUnique({
      where: { id: userId },
      select: {
        id: true,
        name: true,
        email: true,
        role: true,
        isAdmin: true,
        createdAt: true,
      },
    });

    if (!user || (!user.isAdmin && user.role !== UserRole.super_admin && user.role !== UserRole.admin)) {
      throw new UnauthorizedException('Admin user not found');
    }

    return user;
  }
}
