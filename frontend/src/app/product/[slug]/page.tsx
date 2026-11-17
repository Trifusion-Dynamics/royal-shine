import ProductClient from './ProductClient';

export const metadata = {
  title: 'Product Details | Royal Shine',
  description: 'Explore exquisite jewellery pieces at Royal Shine.',
};

export default async function ProductPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  return <ProductClient slug={slug} />;
}
