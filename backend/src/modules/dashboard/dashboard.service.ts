import { Injectable } from '@nestjs/common';
import { PrismaService } from '../../database/prisma.service';
import { EnquiryStatus, ProductStatus } from '@prisma/client';

@Injectable()
export class DashboardService {
  constructor(private prisma: PrismaService) {}

  async getStats() {
    const [
      totalProducts,
      publishedProducts,
      draftProducts,
      inactiveProducts,
      totalCategories,
      totalCollections,
      totalEnquiries,
      newEnquiries,
      contactMessages,
    ] = await Promise.all([
      this.prisma.product.count(),
      this.prisma.product.count({ where: { status: ProductStatus.published } }),
      this.prisma.product.count({ where: { status: ProductStatus.draft } }),
      this.prisma.product.count({ where: { status: ProductStatus.inactive } }),
      this.prisma.category.count(),
      this.prisma.collection.count(),
      this.prisma.enquiry.count(),
      this.prisma.enquiry.count({ where: { status: EnquiryStatus.new } }),
      this.prisma.enquiry.count({ where: { enquiryType: 'contact' } }),
    ]);

    const pendingEnquiries = await this.prisma.enquiry.count({
      where: {
        status: { in: [EnquiryStatus.new, EnquiryStatus.contacted, EnquiryStatus.in_progress] },
      },
    });

    const resolvedEnquiries = await this.prisma.enquiry.count({
      where: {
        status: { in: [EnquiryStatus.converted, EnquiryStatus.closed] },
      },
    });

    // 1. Enquiry Status Breakdown
    const enquiriesByStatusRaw = await this.prisma.enquiry.groupBy({
      by: ['status'],
      _count: { id: true },
    });
    const statusMap = Object.values(EnquiryStatus).reduce((acc, curr) => {
      acc[curr] = 0;
      return acc;
    }, {} as Record<string, number>);
    for (const item of enquiriesByStatusRaw) {
      statusMap[item.status] = item._count.id;
    }

    // 2. Published Products by Category
    const categoriesWithCount = await this.prisma.category.findMany({
      orderBy: { sortOrder: 'asc' },
      select: {
        id: true,
        name: true,
        _count: {
          select: {
            products: {
              where: { status: ProductStatus.published },
            },
          },
        },
      },
    });

    const productsByCategory = categoriesWithCount.map(c => ({
      name: c.name,
      count: c._count.products,
    }));

    // 3. Monthly Enquiries (last 6 months)
    const sixMonthsAgo = new Date();
    sixMonthsAgo.setMonth(sixMonthsAgo.getMonth() - 5);
    sixMonthsAgo.setDate(1);
    sixMonthsAgo.setHours(0, 0, 0, 0);

    const recentEnquiryDates = await this.prisma.enquiry.findMany({
      where: { createdAt: { gte: sixMonthsAgo } },
      select: { createdAt: true },
    });

    const monthlyCounts: Record<string, number> = {};
    for (let i = 0; i < 6; i++) {
      const d = new Date();
      d.setMonth(d.getMonth() - (5 - i));
      const key = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`;
      monthlyCounts[key] = 0;
    }

    for (const e of recentEnquiryDates) {
      const d = e.createdAt;
      const key = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`;
      if (monthlyCounts[key] !== undefined) {
        monthlyCounts[key]++;
      }
    }

    // 4. Recent Enquiries
    const recentEnquiries = await this.prisma.enquiry.findMany({
      take: 5,
      orderBy: { createdAt: 'desc' },
      include: {
        product: { select: { id: true, name: true, sku: true } },
      },
    });

    // 5. Recent Products
    const recentProducts = await this.prisma.product.findMany({
      take: 5,
      orderBy: { createdAt: 'desc' },
      include: {
        category: { select: { id: true, name: true } },
        images: { take: 1, orderBy: { sortOrder: 'asc' } },
      },
    });

    return {
      counts: {
        totalProducts,
        publishedProducts,
        draftProducts,
        inactiveProducts,
        totalCategories,
        totalCollections,
        totalEnquiries,
        newEnquiries,
        pendingEnquiries,
        contactMessages,
        resolvedEnquiries,
      },
      enquiryStatusChart: statusMap,
      productsByCategoryChart: productsByCategory,
      monthlyEnquiriesChart: monthlyCounts,
      recentEnquiries,
      recentProducts,
    };
  }
}
