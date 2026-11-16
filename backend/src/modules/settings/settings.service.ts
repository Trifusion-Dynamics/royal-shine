import { Injectable } from '@nestjs/common';
import { PrismaService } from '../../database/prisma.service';

@Injectable()
export class SettingsService {
  constructor(private prisma: PrismaService) {}

  async getAllAsMap(): Promise<Record<string, string>> {
    const settings = await this.prisma.setting.findMany();
    const map: Record<string, string> = {};
    for (const s of settings) {
      map[s.key] = s.value ?? '';
    }
    return map;
  }

  async getAll() {
    return this.prisma.setting.findMany({
      orderBy: { key: 'asc' },
    });
  }

  async get(key: string, defaultValue = ''): Promise<string> {
    const setting = await this.prisma.setting.findUnique({ where: { key } });
    return setting?.value ?? defaultValue;
  }

  async updateMany(map: Record<string, any>) {
    const upserts = Object.entries(map).map(([key, value]) => {
      const stringValue = value !== null && value !== undefined ? String(value) : '';
      return this.prisma.setting.upsert({
        where: { key },
        update: { value: stringValue },
        create: { key, value: stringValue, group: 'general' },
      });
    });

    await this.prisma.$transaction(upserts);
    return this.getAllAsMap();
  }
}
