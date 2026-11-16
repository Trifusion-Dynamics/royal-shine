import Link from 'next/link';
import Image from 'next/image';
import { productsService } from '@/services/products.service';
import { cn } from '@/lib/utils';
import { formatPrice } from '@/lib/utils';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import { Badge } from '@/components/ui/badge';

export const metadata = {
  title: 'Product Details | Royal Shine',
  description: 'Explore exquisite jewellery pieces at Royal Shine.',
};

type ProductDetail = {
  id: number;
  name: string;
  slug: string;
  sku: string;
  short_description?: string | null;
  description?: string | null;
  metal_type?: string | null;
  gold_purity?: string | null;
  weight?: number | null;
  weight_unit?: string | null;
  stone_details?: string | null;
  price: number | null;
  price_on_request: boolean;
  is_featured: boolean;
  status: string;
  category?: { id: number; name: string; slug: string };
  collection?: { id: number; name: string; slug: string } | null;
  images?: Array<{ id: number; image: string; alt_text?: string | null; is_primary: boolean; sort_order: number }>;
  primary_image?: { id: number; image: string; alt_text?: string | null } | null;
  relatedProducts?: Array<{ id: number; name: string; slug: string; price: number | null; price_on_request: boolean; images?: Array<{ image: string }> }>;
};

async function getProduct(slug: string) {
  try {
    const res = await productsService.getBySlug(slug);
    const data = res.data || res;
    return data;
  } catch {
    return null;
  }
}

export default async function ProductPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const product = await getProduct(slug);

  if (!product) {
    return (
      <main className="min-h-screen flex items-center justify-center">
        <Header />
        <div className="text-center py-20">
          <h1 className="font-serif text-3xl text-dark-900 mb-4">Product not found</h1>
          <p className="text-dark-500 mb-6">The jewellery piece you are looking for does not exist.</p>
          <Link href="/jewellery" className="button button-dark inline-block">
            Browse collection
          </Link>
        </div>
        <Footer />
      </main>
    );
  }

  const p = product as ProductDetail;
  const primary = p.images?.find((i) => i.is_primary) || p.images?.[0] || p.primary_image;
  const otherImages = (p.images || []).filter((i) => !i.is_primary);

  return (
    <main className="min-h-screen">
      <Header />
      <section className="section-wrap">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <nav className="mb-8 text-sm text-dark-500">
            <Link href="/" className="hover:text-gold-600">Home</Link>{' '}
            <span className="text-dark-300">/</span>{' '}
            <Link href="/jewellery" className="hover:text-gold-600">Jewellery</Link>{' '}
            <span className="text-dark-300">/</span>{' '}
            <span className="text-dark-900 font-medium">{p.name}</span>
          </nav>

          <div className="lg:grid lg:grid-cols-12 gap-12">
            <div className="lg:col-span-6">
              <div className="relative aspect-square bg-dark-50 rounded-2xl overflow-hidden mb-6">
                {primary?.image ? (
                  <Image
                    src={primary.image}
                    alt={primary.alt_text || p.name}
                    fill
                    sizes="(max-width: 768px) 100vw, 50vw"
                    className="object-cover object-center"
                    priority
                  />
                ) : (
                  <div className="w-full h-full flex items-center justify-center">
                    <span className="text-gold-400 font-serif text-4xl">R</span>
                  </div>
                )}
              </div>
              {otherImages.length > 0 && (
                <div className="flex gap-4 overflow-x-auto pb-2">
                  {otherImages.map((img) => (
                    <img
                      key={img.id}
                      src={img.image}
                      alt={img.alt_text || p.name}
                      className="w-20 h-20 rounded-xl object-cover cursor-pointer border border-dark-200 hover:border-gold-600 transition-colors flex-shrink-0"
                    />
                  ))}
                </div>
              )}
            </div>

            <div className="lg:col-span-6">
              <div className="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
                <div>
                  <p className="text-xs uppercase tracking-wider text-gold-600 font-semibold mb-1">
                    {p.category?.name || 'Jewellery'}
                  </p>
                  <h1 className="font-serif text-3xl text-dark-900 font-semibold mb-2">{p.name}</h1>
                  <p className="text-sm text-dark-500">{p.sku}</p>
                </div>
                {p.is_featured && (
                  <Badge>Featured</Badge>
                )}
              </div>

              <div className="mb-8">
                <h3 className="text-sm uppercase tracking-wider text-dark-400 font-semibold mb-3">Description</h3>
                <p className="text-dark-600 leading-relaxed">
                  {p.description || p.short_description || 'No description available for this piece.'}
                </p>
              </div>

              <div className="bg-dark-50/50 rounded-2xl p-6 mb-8 border border-dark-100">
                <div className="flex items-baseline gap-3 mb-4">
                  <span className="font-serif text-3xl font-bold text-dark-900">
                    {p.price_on_request ? 'Price on Request' : formatPrice(p.price)}
                  </span>
                  {p.price_on_request && (
                    <Badge variant="warning" className="text-xs ml-2">POA</Badge>
                  )}
                </div>
                {p.metal_type && (
                  <p className="text-sm text-dark-600 mb-1">
                    <span className="font-semibold text-dark-900">Metal:</span> {p.metal_type.charAt(0).toUpperCase() + p.metal_type.slice(1)}
                  </p>
                )}
                {p.gold_purity && (
                  <p className="text-sm text-dark-600 mb-1">
                    <span className="font-semibold text-dark-900">Purity:</span> {p.gold_purity}
                  </p>
                )}
                {p.weight && (
                  <p className="text-sm text-dark-600 mb-1">
                    <span className="font-semibold text-dark-900">Weight:</span> {p.weight} {p.weight_unit || 'g'}
                  </p>
                )}
                {p.stone_details && (
                  <p className="text-sm text-dark-600 mb-0">
                    <span className="font-semibold text-dark-900">Stones:</span> {p.stone_details}
                  </p>
                )}
              </div>

              <div className="mb-8">
                <button className="button button-dark w-full lg:w-auto px-8 py-4 text-base">
                  {p.price_on_request ? 'Enquire Now' : 'Add to Enquiry'}
                </button>
                <button className="w-full lg:w-auto mt-3 lg:mt-0 lg:ml-4 button outline px-8 py-4 text-base flex items-center justify-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                  </svg>
                  WhatsApp
                </button>
              </div>

              <div className="border-t border-dark-200 pt-8">
                <h3 className="font-serif font-semibold text-dark-900 mb-6">You may also like</h3>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
                  {(p.relatedProducts || []).map((rel: any) => (
                    <Link key={rel.id} href={`/product/${rel.slug}`} className="block">
                      <div className="aspect-square bg-dark-50 rounded-xl overflow-hidden mb-3">
                        {rel.images?.[0]?.image ? (
                          <Image src={rel.images[0].image} alt={rel.name} fill className="object-cover object-center" sizes="25vw" />
                        ) : (
                          <div className="w-full h-full flex items-center justify-center text-gold-400 font-serif text-lg">R</div>
                        )}
                      </div>
                      <p className="text-sm font-semibold text-dark-900 mb-1">{rel.name}</p>
                      <p className="text-xs text-dark-500">
                        {rel.price_on_request ? 'POA' : formatPrice(rel.price)}
                      </p>
                    </Link>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </main>
  );
}
