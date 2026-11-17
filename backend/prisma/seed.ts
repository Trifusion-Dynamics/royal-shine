import { PrismaClient, UserRole, ContentStatus, ProductStatus, MetalType, GoldPurity, EnquiryStatus, EnquiryType } from '@prisma/client';

const prisma = new PrismaClient();

// Direct public folder paths - images served from Next.js public folder
const IMG = (file: string) => `/images/${file}`;

async function main() {
  console.log('🌱 Seeding Royal Shine premium jewellery database with real images...');

  // ── 1. Users ──
  console.log('Seeding users...');
  const admin = await prisma.user.upsert({
    where: { email: 'admin@royalshine.in' },
    update: {},
    create: {
      name: 'Royal Shine Admin',
      email: 'admin@royalshine.in',
      password: '$2a$10$LVFgJYyUQhmUGXFG6jTaIubBZTN/puNSYYaet2Ktz.Gyv6M7T99LO',
      role: UserRole.super_admin,
      isAdmin: true,
    },
  });

  console.log(`✅ Admin created: ${admin.email} / RoyalShine@2024`);

  // ── 2. Categories ──
  console.log('Seeding categories...');
  const categories = await Promise.all([
    prisma.category.upsert({
      where: { slug: 'gold-jewellery' },
      update: {},
      create: {
        name: 'Gold Jewellery',
        slug: 'gold-jewellery',
        description: 'Pure hallmarked gold jewellery crafted for every celebration.',
        image: IMG('necjlace/royal-shine-necklace-collection-hero.webp'),
        isFeatured: true,
        status: ContentStatus.active,
        sortOrder: 1,
      },
    }),
    prisma.category.upsert({
      where: { slug: 'diamond-jewellery' },
      update: {},
      create: {
        name: 'Diamond Jewellery',
        slug: 'diamond-jewellery',
        description: 'Certified diamond jewellery for unforgettable moments.',
        image: IMG('Rings/royal-shine-rings-collection-hero.webp'),
        isFeatured: true,
        status: ContentStatus.active,
        sortOrder: 2,
      },
    }),
    prisma.category.upsert({
      where: { slug: 'silver-jewellery' },
      update: {},
      create: {
        name: 'Silver Jewellery',
        slug: 'silver-jewellery',
        description: 'Elegant handcrafted silver jewellery for daily wear.',
        image: IMG('Silver Jewellery/royal-shine-luxury-silver-jewellery-collection-hero.webp'),
        isFeatured: false,
        status: ContentStatus.active,
        sortOrder: 3,
      },
    }),
    prisma.category.upsert({
      where: { slug: 'bridal-collection' },
      update: {},
      create: {
        name: 'Bridal Collection',
        slug: 'bridal-collection',
        description: 'Exquisite bridal sets designed for your special day.',
        image: IMG('Bridal-jewellery/royal-shine-bridal-jewellery-collection-hero.webp'),
        isFeatured: true,
        status: ContentStatus.active,
        sortOrder: 4,
      },
    }),
    prisma.category.upsert({
      where: { slug: 'artificial-jewellery' },
      update: {},
      create: {
        name: 'Artificial Jewellery',
        slug: 'artificial-jewellery',
        description: 'Affordable fashion jewellery for every occasion.',
        image: IMG('Earing/royal-shine-earrings-collection-hero.webp'),
        isFeatured: false,
        status: ContentStatus.active,
        sortOrder: 5,
      },
    }),
  ]);

  const goldCat = categories[0];
  const diamondCat = categories[1];
  const silverCat = categories[2];
  const bridalCat = categories[3];

  // ── 3. Collections ──
  console.log('Seeding collections...');
  const collections = await Promise.all([
    prisma.collection.upsert({
      where: { slug: 'heritage-gold' },
      update: {},
      create: {
        name: 'Heritage Gold',
        slug: 'heritage-gold',
        description: 'Timeless gold designs inspired by royal heritage.',
        image: IMG('necjlace/royal-shine-necklace-collection-hero.webp'),
        isFeatured: true,
        status: ContentStatus.active,
        sortOrder: 1,
      },
    }),
    prisma.collection.upsert({
      where: { slug: 'eternal-diamonds' },
      update: {},
      create: {
        name: 'Eternal Diamonds',
        slug: 'eternal-diamonds',
        description: 'Certified diamonds that shine forever.',
        image: IMG('Rings/royal-shine-rings-collection-hero.webp'),
        isFeatured: true,
        status: ContentStatus.active,
        sortOrder: 2,
      },
    }),
    prisma.collection.upsert({
      where: { slug: 'bridal-splendour' },
      update: {},
      create: {
        name: 'Bridal Splendour',
        slug: 'bridal-splendour',
        description: 'Complete bridal jewellery sets for your wedding day.',
        image: IMG('Bridal-jewellery/royal-shine-bridal-jewellery-collection-hero.webp'),
        isFeatured: true,
        status: ContentStatus.active,
        sortOrder: 3,
      },
    }),
    prisma.collection.upsert({
      where: { slug: 'everyday-elegance' },
      update: {},
      create: {
        name: 'Everyday Elegance',
        slug: 'everyday-elegance',
        description: 'Lightweight designs for daily wear.',
        image: IMG('Silver Jewellery/royal-shine-luxury-silver-jewellery-collection-hero.webp'),
        isFeatured: false,
        status: ContentStatus.active,
        sortOrder: 4,
      },
    }),
  ]);

  const heritageCol = collections[0];
  const eternalCol = collections[1];
  const bridalCol = collections[2];
  const everydayCol = collections[3];

  // ── 4. Products ──
  console.log('Seeding products...');
  const products = await Promise.all([
    // Heritage Gold collection
    prisma.product.create({
      data: {
        name: 'Royal Heritage Gold Necklace',
        slug: 'royal-heritage-gold-necklace',
        sku: 'RS-GN-001',
        categoryId: goldCat.id,
        collectionId: heritageCol.id,
        shortDescription: '22K hallmarked gold necklace with intricate filigree work.',
        description: 'A masterpiece of 22K hallmarked gold, featuring intricate filigree work inspired by royal Indian heritage. Perfect for weddings and grand celebrations.',
        metalType: MetalType.gold,
        goldPurity: GoldPurity.K22,
        weight: 28.5,
        weightUnit: 'g',
        stoneDetails: 'None',
        price: 185000,
        priceOnRequest: false,
        isFeatured: true,
        status: ProductStatus.published,
        sortOrder: 1,
        images: {
          create: [
            { image: IMG('necjlace/royal-shine-gold-diamond-necklace-chandelier-earring-set-original.png'), altText: 'Royal Heritage Gold Necklace - Front view', isPrimary: true, sortOrder: 1 },
            { image: IMG('necjlace/royal-shine-antique-gold-rani-haar-ruby-emerald-original.png'), altText: 'Royal Heritage Gold Necklace - Detail view', isPrimary: false, sortOrder: 2 },
            { image: IMG('necjlace/royal-shine-three-layer-gold-pendant-necklace-original.png'), altText: 'Royal Heritage Gold Necklace - Worn view', isPrimary: false, sortOrder: 3 },
          ],
        },
      },
      include: { images: true },
    }),
    prisma.product.create({
      data: {
        name: 'Heritage Gold Jhumka Pair',
        slug: 'heritage-gold-jhumka-pair',
        sku: 'RS-GE-002',
        categoryId: goldCat.id,
        collectionId: heritageCol.id,
        shortDescription: 'Traditional 22K gold jhumkas with dangling pearls.',
        description: 'Classic 22K gold jhumkas with delicate dangling pearls. A timeless pair that complements every traditional outfit.',
        metalType: MetalType.gold,
        goldPurity: GoldPurity.K22,
        weight: 12.0,
        weightUnit: 'g',
        stoneDetails: 'Freshwater pearls',
        price: 68000,
        priceOnRequest: false,
        isFeatured: true,
        status: ProductStatus.published,
        sortOrder: 2,
        images: {
          create: [
            { image: IMG('Earing/royal-shine-bahubali-temple-gold-silver-jhumka-original.png'), altText: 'Heritage Gold Jhumka Pair', isPrimary: true, sortOrder: 1 },
            { image: IMG('Earing/royal-shine-gold-silver-tiered-chandelier-earrings-original.png'), altText: 'Heritage Gold Jhumka Detail', isPrimary: false, sortOrder: 2 },
          ],
        },
      },
      include: { images: true },
    }),
    // Eternal Diamonds collection
    prisma.product.create({
      data: {
        name: 'Eternal Diamond Solitaire Ring',
        slug: 'eternal-diamond-solitaire-ring',
        sku: 'RS-DR-003',
        categoryId: diamondCat.id,
        collectionId: eternalCol.id,
        shortDescription: '1.5 carat certified diamond solitaire in 18K white gold.',
        description: 'A breathtaking 1.5 carat GIA-certified diamond solitaire set in 18K white gold. Designed for proposals and anniversaries.',
        metalType: MetalType.gold,
        goldPurity: GoldPurity.K18,
        weight: 4.2,
        weightUnit: 'g',
        stoneDetails: '1.5 ct GIA-certified diamond, VS1 clarity, G color',
        price: 450000,
        priceOnRequest: false,
        isFeatured: true,
        status: ProductStatus.published,
        sortOrder: 1,
        images: {
          create: [
            { image: IMG('Rings/royal-shine-solitaire-diamond-ring-original.png.png'), altText: 'Eternal Diamond Solitaire Ring', isPrimary: true, sortOrder: 1 },
            { image: IMG('Rings/royal-shine-oval-halo-pave-engagement-ring-original.png'), altText: 'Diamond Solitaire Ring - Side profile', isPrimary: false, sortOrder: 2 },
          ],
        },
      },
      include: { images: true },
    }),
    prisma.product.create({
      data: {
        name: 'Diamond Tennis Bracelet',
        slug: 'diamond-tennis-bracelet',
        sku: 'RS-DB-004',
        categoryId: diamondCat.id,
        collectionId: eternalCol.id,
        shortDescription: '2.0 carat total diamond tennis bracelet in 18K white gold.',
        description: 'A stunning 2.0 carat total weight diamond tennis bracelet in 18K white gold. Continuous sparkle for your wrist.',
        metalType: MetalType.gold,
        goldPurity: GoldPurity.K18,
        weight: 18.5,
        weightUnit: 'g',
        stoneDetails: '2.0 ct total diamonds, VS2 clarity, H color',
        price: 320000,
        priceOnRequest: false,
        isFeatured: false,
        status: ProductStatus.published,
        sortOrder: 2,
        images: {
          create: [
            { image: IMG('Braclets/royal-shine-gold-white-gold-tennis-diamond-bracelets-original.png'), altText: 'Diamond Tennis Bracelet', isPrimary: true, sortOrder: 1 },
          ],
        },
      },
      include: { images: true },
    }),
    // Bridal Splendour collection
    prisma.product.create({
      data: {
        name: 'Bridal Splendour Necklace Set',
        slug: 'bridal-splendour-necklace-set',
        sku: 'RS-BS-005',
        categoryId: bridalCat.id,
        collectionId: bridalCol.id,
        shortDescription: 'Complete 22K gold bridal necklace, earrings, and maang tikka set.',
        description: 'The ultimate bridal set in 22K hallmarked gold. Includes necklace, matching jhumkas, and maang tikka. Designed to make every bride feel like royalty.',
        metalType: MetalType.gold,
        goldPurity: GoldPurity.K22,
        weight: 85.0,
        weightUnit: 'g',
        stoneDetails: 'Uncut diamonds and rubies',
        price: 650000,
        priceOnRequest: false,
        isFeatured: true,
        status: ProductStatus.published,
        sortOrder: 1,
        images: {
          create: [
            { image: IMG('Bridal-jewellery/royal-shine-gold-kundan-polki-complete-bridal-jewellery-set-original.png'), altText: 'Bridal Splendour Necklace Set', isPrimary: true, sortOrder: 1 },
            { image: IMG('Bridal-jewellery/royal-shine-gold-kundan-polki-bridal-jhoomar-passa-original.png'), altText: 'Bridal Set - Earrings detail', isPrimary: false, sortOrder: 2 },
            { image: IMG('Bridal-jewellery/royal-shine-gold-kundan-pearl-bridal-matha-patti-original.png'), altText: 'Bridal Set - Maang tikka detail', isPrimary: false, sortOrder: 3 },
          ],
        },
      },
      include: { images: true },
    }),
    prisma.product.create({
      data: {
        name: 'Bridal Gold Bangles Pair',
        slug: 'bridal-gold-bangles-pair',
        sku: 'RS-BB-006',
        categoryId: bridalCat.id,
        collectionId: bridalCol.id,
        shortDescription: '22K gold bridal bangles with intricate engraving.',
        description: 'Traditional 22K gold bridal bangles with intricate engraving work. A must-have for every Indian bride.',
        metalType: MetalType.gold,
        goldPurity: GoldPurity.K22,
        weight: 65.0,
        weightUnit: 'g',
        stoneDetails: 'None',
        price: 425000,
        priceOnRequest: false,
        isFeatured: false,
        status: ProductStatus.published,
        sortOrder: 2,
        images: {
          create: [
            { image: IMG('Braclets/royal-shine-gold-kundan-meenakari-bridal-bangles-original.png'), altText: 'Bridal Gold Bangles Pair', isPrimary: true, sortOrder: 1 },
          ],
        },
      },
      include: { images: true },
    }),
    // Everyday Elegance - Silver
    prisma.product.create({
      data: {
        name: 'Silver Lotus Pendant',
        slug: 'silver-lotus-pendant',
        sku: 'RS-SP-007',
        categoryId: silverCat.id,
        collectionId: everydayCol.id,
        shortDescription: 'Sterling silver lotus flower pendant with chain.',
        description: 'A delicate sterling silver lotus flower pendant on an adjustable chain. Minimal elegance for everyday wear.',
        metalType: MetalType.silver,
        weight: 5.5,
        weightUnit: 'g',
        stoneDetails: 'None',
        price: 4500,
        priceOnRequest: false,
        isFeatured: false,
        status: ProductStatus.published,
        sortOrder: 1,
        images: {
          create: [
            { image: IMG('Silver Jewellery/royal-shine-925-sterling-silver-pendant-studs-ring-bracelet-set-original.png'), altText: 'Silver Lotus Pendant', isPrimary: true, sortOrder: 1 },
          ],
        },
      },
      include: { images: true },
    }),
    prisma.product.create({
      data: {
        name: 'Silver Oxidised Earrings',
        slug: 'silver-oxidised-earrings',
        sku: 'RS-SE-008',
        categoryId: silverCat.id,
        collectionId: everydayCol.id,
        shortDescription: 'Oxidised silver jhumka earrings for casual wear.',
        description: 'Oxidised silver jhumka earrings with traditional motifs. Perfect for casual outings and office wear.',
        metalType: MetalType.silver,
        weight: 8.0,
        weightUnit: 'g',
        stoneDetails: 'None',
        price: 3200,
        priceOnRequest: false,
        isFeatured: false,
        status: ProductStatus.published,
        sortOrder: 2,
        images: {
          create: [
            { image: IMG('Earing/royal-shine-gold-silver-hoop-earrings-set-original.png'), altText: 'Silver Oxidised Earrings', isPrimary: true, sortOrder: 1 },
          ],
        },
      },
      include: { images: true },
    }),
    // Gold - 18K
    prisma.product.create({
      data: {
        name: '18K Gold Pendant with Chain',
        slug: '18k-gold-pendant-chain',
        sku: 'RS-GP-009',
        categoryId: goldCat.id,
        collectionId: heritageCol.id,
        shortDescription: '18K gold pendant with delicate chain.',
        description: 'Elegant 18K gold pendant with a delicate chain. Suitable for gifting and daily wear.',
        metalType: MetalType.gold,
        goldPurity: GoldPurity.K18,
        weight: 6.5,
        weightUnit: 'g',
        stoneDetails: 'None',
        price: 42000,
        priceOnRequest: false,
        isFeatured: false,
        status: ProductStatus.published,
        sortOrder: 3,
        images: {
          create: [
            { image: IMG('necjlace/royal-shine-gold-teardrop-diamond-pendant-necklace-original.png.png'), altText: '18K Gold Pendant with Chain', isPrimary: true, sortOrder: 1 },
          ],
        },
      },
      include: { images: true },
    }),
  ]);

  console.log(`Seeded ${products.length} products`);

  // ── 5. Banners ──
  console.log('Seeding banners...');
  await Promise.all([
    prisma.banner.create({
      data: {
        image: IMG('necjlace/royal-shine-necklace-collection-hero.webp'),
        heading: 'The Art of Adornment',
        subheading: 'Every piece tells your story. Discover jewellery that celebrates your moments.',
        ctaLabel: 'Explore Collection',
        ctaUrl: '/jewellery',
        status: ContentStatus.active,
        sortOrder: 1,
        altText: 'Royal Shine jewellery hero banner',
      },
    }),
    prisma.banner.create({
      data: {
        image: IMG('Bridal-jewellery/royal-shine-bridal-jewellery-collection-hero.webp'),
        heading: 'Bridal Splendour',
        subheading: 'Exquisite 22K gold bridal sets for your wedding day.',
        ctaLabel: 'View Bridal Collection',
        ctaUrl: '/collections/bridal-splendour',
        status: ContentStatus.active,
        sortOrder: 2,
        altText: 'Royal Shine bridal collection banner',
      },
    }),
    prisma.banner.create({
      data: {
        image: IMG('Rings/royal-shine-rings-collection-hero.webp'),
        heading: 'Eternal Diamonds',
        subheading: 'Certified diamonds that shine forever.',
        ctaLabel: 'Shop Diamonds',
        ctaUrl: '/jewellery?category=diamond-jewellery',
        status: ContentStatus.active,
        sortOrder: 3,
        altText: 'Royal Shine diamond collection banner',
      },
    }),
  ]);

  // ── 6. Gallery ──
  console.log('Seeding gallery...');
  await Promise.all([
    prisma.gallery.create({
      data: {
        image: IMG('Bridal-jewellery/royal-shine-bridal-jewellery-collection-hero.webp'),
        title: 'Wedding Day',
        description: 'A bride adorned in Royal Shine bridal jewellery.',
        altText: 'Bridal jewellery gallery',
        status: ContentStatus.active,
        sortOrder: 1,
      },
    }),
    prisma.gallery.create({
      data: {
        image: IMG('necjlace/royal-shine-necklace-collection-hero.webp'),
        title: 'Festival Celebration',
        description: 'Gold jewellery for festive occasions.',
        altText: 'Festival jewellery gallery',
        status: ContentStatus.active,
        sortOrder: 2,
      },
    }),
    prisma.gallery.create({
      data: {
        image: IMG('Silver Jewellery/royal-shine-luxury-silver-jewellery-collection-hero.webp'),
        title: 'Everyday Elegance',
        description: 'Silver and gold for daily wear.',
        altText: 'Everyday jewellery gallery',
        status: ContentStatus.active,
        sortOrder: 3,
      },
    }),
  ]);

  // ── 7. Testimonials ──
  console.log('Seeding testimonials...');
  await Promise.all([
    prisma.testimonial.create({
      data: {
        customerName: 'Priya Sharma',
        customerLocation: 'Mumbai, India',
        testimonial: 'The bridal set I purchased from Royal Shine exceeded all my expectations. The craftsmanship is exquisite and the quality is unmatched.',
        image: IMG('Earing/royal-shine-earrings-collection-hero.webp'),
        rating: 5,
        sortOrder: 1,
        isApproved: true,
      },
    }),
    prisma.testimonial.create({
      data: {
        customerName: 'Rahul Verma',
        customerLocation: 'Delhi, India',
        testimonial: 'Bought a diamond solitaire ring for my proposal. The diamond is stunning and the service was exceptional.',
        image: IMG('Rings/royal-shine-rings-collection-hero.webp'),
        rating: 5,
        sortOrder: 2,
        isApproved: true,
      },
    }),
    prisma.testimonial.create({
      data: {
        customerName: 'Ananya Reddy',
        customerLocation: 'Bangalore, India',
        testimonial: 'Love my silver lotus pendant. It is lightweight and perfect for everyday wear. Highly recommend Royal Shine.',
        image: IMG('Silver Jewellery/royal-shine-luxury-silver-jewellery-collection-hero.webp'),
        rating: 5,
        sortOrder: 3,
        isApproved: true,
      },
    }),
  ]);

  // ── 8. Settings ──
  console.log('Seeding settings...');
  await Promise.all([
    prisma.setting.create({ data: { key: 'site_name', value: 'Royal Shine', group: 'general' } }),
    prisma.setting.create({ data: { key: 'site_logo', value: IMG('necjlace/royal-shine-necklace-collection-hero.webp'), group: 'general' } }),
    prisma.setting.create({ data: { key: 'phone', value: '+91 98765 43210', group: 'contact' } }),
    prisma.setting.create({ data: { key: 'whatsapp_number', value: '919876543210', group: 'contact' } }),
    prisma.setting.create({ data: { key: 'email', value: 'hello@royalshine.in', group: 'contact' } }),
    prisma.setting.create({ data: { key: 'address', value: '123 Jewellery Street, Connaught Place, New Delhi, India 110001', group: 'contact' } }),
    prisma.setting.create({ data: { key: 'announcement_bar_text', value: 'Free shipping on all orders above ₹50,000 ✦', group: 'general' } }),
    prisma.setting.create({ data: { key: 'announcement_bar_enabled', value: 'true', group: 'general' } }),
    prisma.setting.create({ data: { key: 'instagram_url', value: 'https://instagram.com/royalshine', group: 'social' } }),
    prisma.setting.create({ data: { key: 'facebook_url', value: 'https://facebook.com/royalshine', group: 'social' } }),
  ]);

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
