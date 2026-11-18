import Link from 'next/link';
import Image from 'next/image';
import { collectionsService } from '@/services/collections.service';
import { productsService } from '@/services/products.service';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import { SectionHeading } from '@/components/ui/section-heading';
import { formatPrice } from '@/lib/utils';

export const metadata = {
  title: 'Collections | Royal Shine',
  description: 'Explore our curated jewellery collections - Heritage Gold, Eternal Diamonds, Bridal Splendour, and more.',
};

async function getData() {
  const [collectionsRes, productsRes] = await Promise.allSettled([
    collectionsService.getAll(false),
    productsService.getAll({ status: 'published', limit: 20 }),
  ]);

  const unwrap = (r: any, fallback: any) =>
    r.status === 'fulfilled' ? (r.value.data || r.value || fallback) : fallback;

  return {
    collections: unwrap(collectionsRes, []).filter((c: any) => c.status === 'active'),
    products: unwrap(productsRes, []),
  };
}

export default async function CollectionsPage() {
  const { collections, products } = await getData();

  return (
    <main className="min-h-screen">
      <Header />
      <section className="py-16">
        <div className="container-luxury text-center">
          <h1 className="text-5xl md:text-6xl font-serif text-charcoal mb-4 leading-tight">
            Our Collections
          </h1>
          <div className="w-20 h-[1px] bg-gold mx-auto mb-6" />
          <p className="text-lg text-warm-gray max-w-2xl mx-auto leading-relaxed">
            Curated collections for every occasion
          </p>
        </div>

        {collections.length > 0 && (
          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-10 mt-14">
            {collections.map((collection: any) => (
              <Link
                key={collection.id}
                href={`/collections/${collection.slug}`}
                className="group block border border-beige bg-white rounded-lg overflow-hidden card"
              >
                {collection.image && (
                  <div className="image-wrap aspect-[4/5]">
                    <Image
                      src={collection.image}
                      alt={collection.name}
                      fill
                      sizes="(max-width: 768px) 100vw, 25vw"
                      className="object-cover object-center group-hover:scale-105 transition-transform duration-700"
                      priority
                    />
                  </div>
                )}
                <div className="p-6">
                  <h3 className="font-serif text-lg text-charcoal mb-2 leading-tight group-hover:text-gold transition-colors">
                    {collection.name}
                  </h3>
                  {collection.description && (
                    <p className="text-sm text-warm-gray line-clamp-2 mb-4 leading-relaxed">
                      {collection.description}
                    </p>
                  )}
                  <span className="btn btn-text text-gold hover:text-brown text-sm">
                    Explore Collection
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                  </span>
                </div>
              </Link>
            ))}
          </div>
        )}

        {products.length > 0 && (
          <section className="mt-20">
            <SectionHeading title="Featured Products" subtitle="From our collections" />
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
              {products.slice(0, 8).map((product: any) => (
                <div key={product.id} className="card group block">
                  <div className="image-wrap aspect-square">
                    {product.images?.[0]?.image ? (
                      <Image
                        src={productImage(product.images[0], { width: 400, height: 400, quality: 85, format: 'webp' })}
                        alt={product.name}
                        fill
                        sizes="25vw"
                        className="object-cover object-center group-hover:scale-105 transition-transform duration-500"
                      />
                    ) : (
                      <div className="w-full h-full lazy-placeholder flex items-center justify-center">
                        <span className="text-gold-600 font-serif text-lg">R</span>
                      </div>
                    )}
                  </div>
                  <p className="text-xs font-semibold text-gold uppercase tracking-wider mb-1">
                    {product.category?.name || 'Jewellery'}
                  </p>
                  <p className="text-sm font-semibold text-charcoal mb-1">{product.name}</p>
                  <p className="text-xs text-warm-gray">
                    {product.price_on_request ? 'POA' : formatPrice(product.price)}
                  </p>
                </div>
              ))}
            </div>
          </section>
        )}
      </section>

      <Footer />
    </main>
  );
}

function productImage(image: string | { image: string | null | undefined; altText?: string | null } | null | undefined, options: { width?: number; height?: number; quality?: number; format?: string } = {}) {
  const img = typeof image === 'string' ? { image } : image;
  if (!img?.image) return '/images/placeholder-jewel.jpg';
  if (img.image.startsWith('http')) return img.image;
  const endpoint = process.env.NEXT_PUBLIC_IMAGEKIT_URL_ENDPOINT || 'https://ik.imagekit.io/royalshine';
  const transforms: string[] = [];
  if (options.width) transforms.push(`w-${options.width}`);
  if (options.height) transforms.push(`h-${options.height}`);
  if (options.quality) transforms.push(`q-${options.quality}`);
  if (options.format) transforms.push(`f-${options.format}`);
  const transformStr = transforms.length > 0 ? `tr:${transforms.join(',')}` : '';
  const cleanPath = img.image.startsWith('/') ? img.image.slice(1) : img.image;
  return `${endpoint}/${transformStr}/${cleanPath}`;
}
