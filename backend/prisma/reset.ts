import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  console.log('🧹 Clearing existing data...');

  // Delete in reverse order of foreign key dependencies
  await prisma.productImage.deleteMany();
  await prisma.product.deleteMany();
  await prisma.banner.deleteMany();
  await prisma.gallery.deleteMany();
  await prisma.testimonial.deleteMany();
  await prisma.setting.deleteMany();
  await prisma.collection.deleteMany();
  await prisma.category.deleteMany();
  await prisma.user.deleteMany();

  console.log('✅ All data cleared.');
}

main()
  .catch((e) => {
    console.error('❌ Clear error:', e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
