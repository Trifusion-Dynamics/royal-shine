import { PrismaClient, UserRole, ContentStatus, ProductStatus, MetalType, GoldPurity, EnquiryStatus, EnquiryType } from '@prisma/client';
import * as fs from 'fs';
import * as path from 'path';

const prisma = new PrismaClient();

async function main() {
  console.log('🌱 Starting database seed from SQLite dump...');

  const dumpPath = path.resolve('C:/Users/arunk/.gemini/antigravity/brain/cec52090-36ee-4573-b616-8596489233bb/scratch/sqlite_dump.json');
  let dump: any = {};
  if (fs.existsSync(dumpPath)) {
    dump = JSON.parse(fs.readFileSync(dumpPath, 'utf8'));
  }

  // 1. Seed Users
  console.log('Seeding users...');
  const users = dump.users?.records || [];
  if (users.length === 0) {
    // Default admin
    await prisma.user.upsert({
      where: { email: 'admin@example.com' },
      update: {},
      create: {
        name: 'Admin',
        email: 'admin@example.com',
        password: '$2y$12$e0NZBqK1eTqjJ3Qp6d8hceW.7ZgWnS/q.4Gv9bY6Hq2oM5x2g3uC.', // bcrypt
        isAdmin: true,
        role: UserRole.super_admin,
      },
    });
  } else {
    for (const u of users) {
      await prisma.user.upsert({
        where: { email: u.email },
        update: {},
        create: {
          id: u.id,
          name: u.name,
          email: u.email,
          password: u.password,
          isAdmin: Boolean(u.is_admin),
          role: (u.role as UserRole) || UserRole.super_admin,
          emailVerifiedAt: u.email_verified_at ? new Date(u.email_verified_at) : null,
          rememberToken: u.remember_token,
          createdAt: u.created_at ? new Date(u.created_at) : new Date(),
          updatedAt: u.updated_at ? new Date(u.updated_at) : new Date(),
        },
      });
    }
  }

  // 2. Seed Categories
  console.log('Seeding categories...');
  const categories = dump.categories?.records || [];
  for (const c of categories) {
    await prisma.category.upsert({
      where: { slug: c.slug },
      update: {},
      create: {
        id: c.id,
        name: c.name,
        slug: c.slug,
        description: c.description,
        image: c.image,
        isFeatured: Boolean(c.is_featured),
        status: (c.status as ContentStatus) || ContentStatus.active,
        seoTitle: c.seo_title,
        seoDescription: c.seo_description,
        sortOrder: c.sort_order ?? 0,
        createdAt: c.created_at ? new Date(c.created_at) : new Date(),
        updatedAt: c.updated_at ? new Date(c.updated_at) : new Date(),
      },
    });
  }

  // 3. Seed Collections
  console.log('Seeding collections...');
  const collections = dump.collections?.records || [];
  for (const col of collections) {
    await prisma.collection.upsert({
      where: { slug: col.slug },
      update: {},
      create: {
        id: col.id,
        name: col.name,
        slug: col.slug,
        description: col.description,
        image: col.image,
        isFeatured: Boolean(col.is_featured),
        status: (col.status as ContentStatus) || ContentStatus.active,
        seoTitle: col.seo_title,
        seoDescription: col.seo_description,
        sortOrder: col.sort_order ?? 0,
        createdAt: col.created_at ? new Date(col.created_at) : new Date(),
        updatedAt: col.updated_at ? new Date(col.updated_at) : new Date(),
      },
    });
  }

  // 4. Seed Products
  console.log('Seeding products...');
  const products = dump.products?.records || [];
  for (const p of products) {
    let purity: GoldPurity | null = null;
    if (p.gold_purity === '9K') purity = GoldPurity.K9;
    else if (p.gold_purity === '14K') purity = GoldPurity.K14;
    else if (p.gold_purity === '18K') purity = GoldPurity.K18;
    else if (p.gold_purity === '22K') purity = GoldPurity.K22;

    let metal: MetalType | null = null;
    if (['gold', 'silver', 'artificial', 'other'].includes(p.metal_type)) {
      metal = p.metal_type as MetalType;
    }

    let pStatus: ProductStatus = ProductStatus.draft;
    if (p.status === 'published') pStatus = ProductStatus.published;
    else if (p.status === 'inactive') pStatus = ProductStatus.inactive;

    await prisma.product.upsert({
      where: { slug: p.slug },
      update: {},
      create: {
        id: p.id,
        categoryId: p.category_id,
        collectionId: p.collection_id || null,
        name: p.name,
        slug: p.slug,
        sku: p.sku,
        shortDescription: p.short_description,
        description: p.description,
        metalType: metal,
        goldPurity: purity,
        weight: p.weight !== null && p.weight !== undefined ? p.weight : null,
        weightUnit: p.weight_unit || 'g',
        stoneDetails: p.stone_details,
        price: p.price !== null && p.price !== undefined ? p.price : null,
        priceOnRequest: Boolean(p.price_on_request),
        isFeatured: Boolean(p.is_featured),
        status: pStatus,
        seoTitle: p.seo_title,
        seoDescription: p.seo_description,
        sortOrder: p.sort_order ?? 0,
        createdAt: p.created_at ? new Date(p.created_at) : new Date(),
        updatedAt: p.updated_at ? new Date(p.updated_at) : new Date(),
      },
    });
  }

  // 5. Seed Product Images
  console.log('Seeding product images...');
  const productImages = dump.product_images?.records || [];
  for (const pi of productImages) {
    const exists = await prisma.productImage.findUnique({ where: { id: pi.id } });
    if (!exists) {
      await prisma.productImage.create({
        data: {
          id: pi.id,
          productId: pi.product_id,
          image: pi.image,
          altText: pi.alt_text,
          sortOrder: pi.sort_order ?? 0,
          isPrimary: Boolean(pi.is_primary),
          createdAt: pi.created_at ? new Date(pi.created_at) : new Date(),
          updatedAt: pi.updated_at ? new Date(pi.updated_at) : new Date(),
        },
      });
    }
  }

  // 6. Seed Banners
  console.log('Seeding banners...');
  const banners = dump.banners?.records || [];
  for (const b of banners) {
    const exists = await prisma.banner.findUnique({ where: { id: b.id } });
    if (!exists) {
      await prisma.banner.create({
        data: {
          id: b.id,
          image: b.image,
          mobileImage: b.mobile_image,
          heading: b.heading,
          subheading: b.subheading,
          ctaLabel: b.cta_label,
          ctaUrl: b.cta_url,
          status: (b.status as ContentStatus) || ContentStatus.active,
          sortOrder: b.sort_order ?? 0,
          altText: b.alt_text,
          startsAt: b.starts_at ? new Date(b.starts_at) : null,
          endsAt: b.ends_at ? new Date(b.ends_at) : null,
          createdAt: b.created_at ? new Date(b.created_at) : new Date(),
          updatedAt: b.updated_at ? new Date(b.updated_at) : new Date(),
        },
      });
    }
  }

  // 7. Seed Galleries
  console.log('Seeding galleries...');
  const galleries = dump.galleries?.records || [];
  for (const g of galleries) {
    const exists = await prisma.gallery.findUnique({ where: { id: g.id } });
    if (!exists) {
      await prisma.gallery.create({
        data: {
          id: g.id,
          image: g.image,
          title: g.title,
          description: g.description,
          altText: g.alt_text,
          status: (g.status as ContentStatus) || ContentStatus.active,
          sortOrder: g.sort_order ?? 0,
          createdAt: g.created_at ? new Date(g.created_at) : new Date(),
          updatedAt: g.updated_at ? new Date(g.updated_at) : new Date(),
        },
      });
    }
  }

  // 8. Seed Testimonials
  console.log('Seeding testimonials...');
  const testimonials = dump.testimonials?.records || [];
  for (const t of testimonials) {
    const exists = await prisma.testimonial.findUnique({ where: { id: t.id } });
    if (!exists) {
      await prisma.testimonial.create({
        data: {
          id: t.id,
          customerName: t.customer_name,
          customerLocation: t.customer_location,
          testimonial: t.testimonial,
          image: t.image,
          rating: t.rating ?? 5,
          sortOrder: t.sort_order ?? 0,
          isApproved: Boolean(t.is_approved),
          createdAt: t.created_at ? new Date(t.created_at) : new Date(),
          updatedAt: t.updated_at ? new Date(t.updated_at) : new Date(),
        },
      });
    }
  }

  // 9. Seed Settings
  console.log('Seeding settings...');
  const settings = dump.settings?.records || [];
  for (const s of settings) {
    await prisma.setting.upsert({
      where: { key: s.key },
      update: {},
      create: {
        key: s.key,
        value: s.value !== null && s.value !== undefined ? String(s.value) : '',
        group: s.group || 'general',
      },
    });
  }

  // 10. Seed Default Pages if empty
  console.log('Seeding default static pages...');
  const defaultPages = [
    {
      title: 'About Us',
      slug: 'about-us',
      content: 'Royal Shine is a premier jewellery boutique known for pure hallmarked gold, certified diamonds, and handcrafted silver jewellery.',
      seoTitle: 'About Royal Shine - Heritage & Craftsmanship',
      seoDescription: 'Discover the story, heritage, and uncompromising craftsmanship behind Royal Shine jewellery.',
    },
    {
      title: 'Privacy Policy',
      slug: 'privacy-policy',
      content: 'At Royal Shine, we value your privacy. We collect customer enquiry information solely to assist you with your jewellery inquiries.',
      seoTitle: 'Privacy Policy - Royal Shine',
      seoDescription: 'Read the privacy policy of Royal Shine jewellery catalogue.',
    },
    {
      title: 'Terms & Conditions',
      slug: 'terms-and-conditions',
      content: 'Welcome to Royal Shine. By browsing our jewellery catalogue, you agree to our terms and conditions. Prices and availability may vary.',
      seoTitle: 'Terms & Conditions - Royal Shine',
      seoDescription: 'Terms and conditions for browsing the Royal Shine catalogue and submitting enquiries.',
    },
  ];

  for (const p of defaultPages) {
    await prisma.page.upsert({
      where: { slug: p.slug },
      update: {},
      create: {
        title: p.title,
        slug: p.slug,
        content: p.content,
        seoTitle: p.seoTitle,
        seoDescription: p.seoDescription,
        status: 'active',
      },
    });
  }

  console.log('✅ Seeding complete!');
}

main()
  .catch((e) => {
    console.error('❌ Seeding error:', e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
