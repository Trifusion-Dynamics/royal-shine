import Link from 'next/link';
import Image from 'next/image';
import { collectionsService } from '@/services/collections.service';
import { productsService } from '@/services/products.service';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import { formatPrice } from '@/lib/utils';

type CollectionItem = {
  id: number;
  name: string;
  slug: string;
  description?: string | null;
  image?: string | null;
  products_count?: number;
};

async function getCollections() {
  try {
    const res = await collectionsService.getAll(false);
    const data = res.data || res;
    return Array.isArray(data) ? data : data.data || [];
  } catch {
    return [];
  }
}

async function getProducts() {
  try {
    const res = await productsService.getAll({ status: 'published', limit: 20 });
    const data = res.data || res;
    return Array.isArray(data) ? data : data.data || [];
  } catch {
    return [];
  }
}

export const metadata = {
  title: 'Collections | Royal Shine',
  description: 'Explore our curated jewellery collections - Heritage Gold, Eternal Diamonds, Bridal Splendour, and more.',
};

export default async function CollectionsPage() {
  const [collections, products] = await Promise.all([getCollections(), getProducts()]);

  return (
    <main className="min-h-screen">
      <Header />
      <section className="section-wrap">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="font-serif text-4xl text-dark-900 mb-8">Our Collections</h2>
          <p className="text-dark-600 max-w-2xl mb-16">
            Each collection tells a story. From heritage-inspired gold to eternal diamonds, discover pieces designed for your most cherished moments.
          </p>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16">
            {(collections as CollectionItem[]).map((collection) => (
              <div
                key={collection.id}
                className="group relative overflow-hidden rounded-2xl border border-dark-100 bg-white hover:shadow-gold-lg transition-all duration-300"
              >
                <div className="aspect-[4/3] relative overflow-hidden bg-dark-50">
                  {collection.image ? (
                    <Image
                      src={collection.image}
                      alt={collection.name}
                      fill
                      sizes="(max-width: 768px) 100vw, 50vw"
                      className="object-cover object-center transition-transform duration-500 group-hover:scale-105"
                      priority
                    />
                  ) : (
                    <div className="w-full h-full flex items-center justify-center">
                      <span className="text-gold-400 font-serif text-6xl">{collection.name.charAt(0)}</span>
                    </div>
                  )}
                  <div className="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent" />
                  <div className="absolute bottom-0 left-0 right-0 p-8">
                    <h3 className="font-serif text-2xl text-white font-semibold">{collection.name}</h3>
                    <p className="text-dark-100 text-sm mt-1">{collection.description}</p>
                  </div>
                </div>
                <div className="p-6">
                  <p className="text-xs uppercase tracking-wider text-dark-400 font-semibold">
                    {collection.products_count} pieces
                  </p>
                  <Link
                    href={`/collections/${collection.slug}`}
                    className="text-gold-600 text-sm font-medium hover:text-gold-700 inline-flex items-center gap-1"
                  >
                    Explore →
                  </Link>
                </div>
              </div>
            ))}
          </div>

          <section className="border-t border-dark-100 pt-12">
            <h3 className="font-serif text-2xl text-dark-900 mb-8">Featured Pieces</h3>
            <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
              {(products as any[]).slice(0, 8).map((product) => (
                <div key={product.id} className="flex flex-col">
                  <div className="aspect-square bg-dark-50 rounded-xl overflow-hidden mb-3">
                    {product.images?.[0]?.image ? (
                      <Image src={product.images[0].image} alt={product.name} fill className="object-cover object-center" sizes="25vw" />
                    ) : (
                      <div className="w-full h-full flex items-center justify-center text-gold-400 font-serif text-lg">R</div>
                    )}
                  </div>
                  <p className="text-xs uppercase tracking-wider text-dark-400 font-semibold mb-1">
                    {product.category?.name || 'Jewellery'}
                  </p>
                  <p className="text-sm font-semibold text-dark-900 mb-1">{product.name}</p>
                  <p className="text-xs text-dark-500">
                    {product.price_on_request ? 'POA' : formatPrice(product.price)}
                  </p>
                </div>
              ))}
            </div>
          </section>
        </div>
      </section>
      <Footer />
    </main>
  );
}
