import { Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../database/prisma.service';
import { CreateEnquiryDto, UpdateEnquiryAdminDto } from './dto/enquiry.dto';
import { EnquiryStatus, EnquiryType } from '@prisma/client';

@Injectable()
export class EnquiriesService {
  constructor(private prisma: PrismaService) {}

  async submitEnquiry(dto: CreateEnquiryDto) {
    // 1. Honeypot check
    if (dto.website && dto.website.trim().length > 0) {
      return { success: true, message: 'Your enquiry has been received.' };
    }

    // Clean mobile number (strip spaces, country code +91)
    let cleanMobile = dto.mobile.replace(/\s+/g, '');
    cleanMobile = cleanMobile.replace(/^(\+91|91|0)/, '');

    // 2. Duplicate check (same mobile & message in last 2 minutes)
    const twoMinutesAgo = new Date(Date.now() - 2 * 60 * 1000);
    const existing = await this.prisma.enquiry.findFirst({
      where: {
        mobile: cleanMobile,
        message: dto.message,
        createdAt: { gte: twoMinutesAgo },
      },
    });

    if (existing) {
      return {
        success: true,
        message: 'Your enquiry has been submitted. We will contact you soon.',
      };
    }

    let subject = dto.subject;
    let enquiryType = dto.enquiryType || EnquiryType.contact;
    let productReference = dto.productReference;

    if (dto.productId) {
      const product = await this.prisma.product.findUnique({
        where: { id: dto.productId },
      });
      if (product) {
        enquiryType = EnquiryType.product;
        if (!subject) subject = `Enquiry for ${product.name}`;
        if (!productReference) productReference = product.sku;
      }
    }

    const enquiry = await this.prisma.enquiry.create({
      data: {
        name: dto.name,
        mobile: cleanMobile,
        email: dto.email || null,
        message: dto.message,
        subject: subject || (enquiryType === EnquiryType.product ? 'Product Enquiry' : 'Contact Message'),
        enquiryType,
        productId: dto.productId || null,
        productReference: productReference || null,
        status: EnquiryStatus.new,
      },
    });

    return {
      success: true,
      message: 'Your enquiry has been submitted successfully. We will contact you soon.',
      id: enquiry.id,
    };
  }

  async findAll(params: {
    status?: EnquiryStatus;
    type?: EnquiryType;
    search?: string;
    page?: number;
    limit?: number;
  }) {
    const page = params.page ? Math.max(1, params.page) : 1;
    const limit = params.limit ? Math.min(100, Math.max(1, params.limit)) : 15;
    const skip = (page - 1) * limit;

    const where: any = {};
    if (params.status) where.status = params.status;
    if (params.type) where.enquiryType = params.type;

    if (params.search) {
      where.OR = [
        { name: { contains: params.search, mode: 'insensitive' } },
        { mobile: { contains: params.search, mode: 'insensitive' } },
        { email: { contains: params.search, mode: 'insensitive' } },
        { subject: { contains: params.search, mode: 'insensitive' } },
        { productReference: { contains: params.search, mode: 'insensitive' } },
      ];
    }

    const [total, items] = await Promise.all([
      this.prisma.enquiry.count({ where }),
      this.prisma.enquiry.findMany({
        where,
        skip,
        take: limit,
        orderBy: { createdAt: 'desc' },
        include: {
          product: { select: { id: true, name: true, slug: true, sku: true } },
          assignedAdmin: { select: { id: true, name: true, email: true } },
        },
      }),
    ]);

    return {
      data: items,
      meta: {
        total,
        page,
        limit,
        totalPages: Math.ceil(total / limit),
      },
    };
  }

  async findOne(id: number) {
    const enquiry = await this.prisma.enquiry.findUnique({
      where: { id },
      include: {
        product: {
          include: {
            images: { orderBy: { sortOrder: 'asc' } },
          },
        },
        assignedAdmin: { select: { id: true, name: true, email: true } },
      },
    });
    if (!enquiry) {
      throw new NotFoundException(`Enquiry with ID ${id} not found`);
    }
    return enquiry;
  }

  async update(id: number, dto: UpdateEnquiryAdminDto) {
    await this.findOne(id);
    const data: any = {};
    if (dto.status !== undefined) data.status = dto.status;
    if (dto.internalNotes !== undefined) data.internalNotes = dto.internalNotes;
    if (dto.followUpDate !== undefined) {
      data.followUpDate = dto.followUpDate ? new Date(dto.followUpDate) : null;
    }
    if (dto.assignedAdminId !== undefined) {
      data.assignedAdminId = dto.assignedAdminId || null;
    }

    return this.prisma.enquiry.update({
      where: { id },
      data,
      include: {
        product: true,
        assignedAdmin: true,
      },
    });
  }

  async remove(id: number) {
    await this.findOne(id);
    return this.prisma.enquiry.delete({ where: { id } });
  }

  // Customers aggregated view (grouped by mobile phone)
  async getCustomers(search?: string) {
    const enquiries = await this.prisma.enquiry.findMany({
      orderBy: { createdAt: 'desc' },
      select: {
        id: true,
        name: true,
        mobile: true,
        email: true,
        createdAt: true,
      },
    });

    // Group in memory
    const customerMap = new Map<string, any>();
    for (const e of enquiries) {
      if (!customerMap.has(e.mobile)) {
        customerMap.set(e.mobile, {
          mobile: e.mobile,
          name: e.name,
          email: e.email,
          enquiriesCount: 1,
          latestEnquiryAt: e.createdAt,
        });
      } else {
        const item = customerMap.get(e.mobile);
        item.enquiriesCount += 1;
        if (!item.email && e.email) item.email = e.email;
      }
    }

    let result = Array.from(customerMap.values());
    if (search) {
      const q = search.toLowerCase();
      result = result.filter(
        c =>
          c.name.toLowerCase().includes(q) ||
          c.mobile.includes(q) ||
          (c.email && c.email.toLowerCase().includes(q)),
      );
    }
    return result;
  }

  async getCustomerHistory(mobile: string) {
    return this.prisma.enquiry.findMany({
      where: { mobile },
      orderBy: { createdAt: 'desc' },
      include: {
        product: { select: { id: true, name: true, sku: true, slug: true } },
        assignedAdmin: { select: { id: true, name: true } },
      },
    });
  }
}
