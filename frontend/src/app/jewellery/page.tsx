import Link from 'next/link';
import Image from 'next/image';
import { productsService } from '@/services/products.service';
import { categoriesService } from '@/services/categories.service';
import { collectionsService } from '@/services/collections.service';
import { settingsService } from '@/services/settings.service';
import { formatPrice } from '@/lib/utils';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import { ProductCard } from '@/components/ui/product-card';
import { SectionHeading } from '@/components/ui/section-heading';
import { Badge } from '@/components/ui/badge';

export const metadata = {
  title: 'Jewellery Collection | Royal Shine',
  description: 'Browse our complete jewellery collection - gold, diamond, silver and bridal pieces.',
};

async function getData() {
  const [categoriesRes, collectionsRes, settingsRes] = await Promise.allSettled([
    categoriesService.getAll(false),
    collectionsService.getAll(false),
    settingsService.getAll(),
  ]);

  const unwrap = (r: any, fallback: any) =>
    r.status === 'fulfilled' ? (r.value.data || r.value || fallback) : fallback;

  const allCategories: any[] = unwrap(categoriesRes, []);
  const allCollections: any[] = unwrap(collectionsRes, []);
  const allSettingsObj: Record<string, string> = unwrap(settingsRes, {});

  return {
    categories: allCategories.filter((c: any) => c.status === 'active'),
    collections: allCollections.filter((c: any) => c.status === 'active'),
    whatsapp: allSettingsObj.whatsapp_number,
  };
}

async function getProducts(params: Record<string, string | number | boolean | undefined>) {
  const cleanParams: Record<string, string | number | boolean> = {};
  for (const [key, value] of Object.entries(params)) {
    if (value !== undefined) cleanParams[key] = value;
  }
  try {
    const res = await productsService.getAll(cleanParams);
    const data = res.data || res;
    return Array.isArray(data) ? data : data.data || [];
  } catch {
    return [];
  }
}

export default async function JewelleryPage({ searchParams }: { searchParams: Promise<{ [key: string]: string | string[] | undefined }> }) {
  const params = await searchParams;
  const category = typeof params.category === 'string' ? params.category : undefined;
  const collection = typeof params.collection === 'string' ? params.collection : undefined;
  const search = typeof params.search === 'string' ? params.search : undefined;
  const metal = typeof params.metal === 'string' ? params.metal : undefined;
  const sort = typeof params.sort === 'string' ? params.sort : 'latest';

  const [products, categories, collections, { whatsapp }] = await Promise.all([
    getProducts({
      categorySlug: category,
      collectionSlug: collection,
      search,
      metalType: metal,
      status: 'published',
      sortBy: sort,
      limit: 40,
    }),
    categoriesService.getAll(false),
    collectionsService.getAll(false),
    (async () => {
      const res = await settingsService.getAll();
      const data = res.data || res;
      const map: Record<string, string> =
        typeof data === 'object' && !Array.isArray(data) ? data : {};
      return { whatsapp: map.whatsapp_number };
    })(),
  ]);

  return (
    <main className="min-h-screen">
      <Header />
      <section className="relative h-[500px] md:h-[600px] overflow-hidden mb-16 bg-[#fdfaf5]">
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-center">
          <div className="text-center">
            <h1 className="text-5xl md:text-6xl font-serif text-[#1a1a1a] mb-6">Our Jewellery Collections</h1>
            <p className="text-lg text-[#666] max-w-2xl mx-auto mb-8">
              Explore our curated collections of fine jewellery. From timeless necklaces to elegant earrings, each piece is crafted with passion and precision.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link href="/jewellery" className="btn btn-gold btn-lg">Shop All Categories</Link>
              {whatsapp && (
                <a
                  href={`https://wa.me/${whatsapp}?text=${encodeURIComponent('Hello Royal Shine, I would like to know more about your jewellery collections.')}`}
                  className="btn btn-outline text-white border-white hover:bg-white hover:text-charcoal btn-lg"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  WhatsApp Us
                </a>
              )}
            </div>
          </div>
        </div>
      </section>

      <section className="mb-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl md:text-4xl font-serif text-[#1a1a1a] mb-6">Discover Our Collections</h2>
          <p className="text-[#666] leading-relaxed mb-8 max-w-2xl mx-auto">
            Royal Shine offers a wide range of jewellery across categories, each designed to celebrate life&rsquo;s special moments. Whether you&rsquo;re looking for bridal jewellery, everyday wear, or something unique, find your perfect piece here.
          </p>
        </div>
      </section>

      {/* Our Jewellery Collections */}
      {collections.length > 0 && (
        <section className="mb-20 bg-[#f7f1e8] py-16 md:py-24">
          <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div className="mx-auto mb-12 max-w-2xl text-center">
              <p className="mb-3 text-[10px] font-semibold uppercase tracking-[.28em] text-[#a77b43]">The Royal Shine Edit</p>
              <h2 className="font-serif text-3xl leading-tight text-[#29231e] md:text-5xl">Our Jewellery Collections</h2>
              <p className="mx-auto mt-4 max-w-xl text-sm leading-7 text-[#766b60] md:text-base">
                Explore thoughtfully curated edits for celebrations, meaningful gifts and everyday elegance.
              </p>
              <span className="mx-auto mt-6 block h-px w-14 bg-[#bd955e]" />
            </div>
            <div className="grid grid-cols-1 items-stretch gap-6 sm:grid-cols-2 lg:grid-cols-3">
              {collections.map((collection: any) => (
                <Link
                  key={collection.id}
                  href={`/collections/${collection.slug}`}
                  className="group flex h-full min-w-0 flex-col overflow-hidden border border-[#e8ddcf] bg-white shadow-[0_4px_18px_rgba(50,35,20,.05)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_16px_38px_rgba(50,35,20,.12)]"
                >
                  <div className="relative aspect-[4/3] overflow-hidden bg-[#eee5d8]">
                    {collection.image ? (
                      <Image
                        src={collection.image}
                        alt={collection.name}
                        fill
                        sizes="(max-width: 768px) 100vw, 33vw"
                        className="object-cover object-center transition-transform duration-700 group-hover:scale-105"
                        priority
                      />
                    ) : (
                      <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#f3eadc] to-[#e3d1b6]">
                        <span className="font-serif text-5xl text-[#a77b43]">{collection.name.charAt(0).toUpperCase() + collection.name.charAt(1).toUpperCase()}</span>
                      </div>
                    )}
                    <span className="absolute left-4 top-4 bg-white/95 px-3 py-1.5 text-[9px] font-semibold uppercase tracking-[.2em] text-[#8f6a3d]">
                      Royal Shine
                    </span>
                  </div>
                  <div className="flex flex-1 items-center justify-between gap-4 px-5 py-5 md:px-6">
                    <div className="min-w-0">
                      <h3 className="font-serif text-xl text-[#29231e] transition-colors group-hover:text-[#9a713e] md:text-2xl">
                        {collection.name}
                      </h3>
                      <p className="mt-2 line-clamp-2 text-xs leading-5 text-[#81766a] md:text-sm">
                        {collection.description || 'Discover the designs in this collection.'}
                      </p>
                    </div>
                    <span className="grid h-10 w-10 shrink-0 place-items-center rounded-full border border-[#dfceb5] text-lg text-[#9a713e] transition-all group-hover:border-[#9a713e] group-hover:bg-[#9a713e] group-hover:text-white" aria-hidden="true">
                      ↗
                    </span>
                  </div>
                </Link>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* All Categories */}
      <section className="mb-20">
        <SectionHeading title="All Categories" subtitle="Browse by category" />
        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
          {categories.map((cat: any) => (
            <Link
              key={cat.id}
              href={`/jewellery?category=${cat.slug}`}
              className="group block"
            >
              <div className="aspect-square relative mb-4 overflow-hidden mx-auto w-32 h-32">
                {cat.image ? (
                  <Image
                    src={cat.image}
                    alt={cat.name}
                    fill
                    sizes="80px"
                    className="object-cover object-center group-hover:scale-110 transition-transform duration-300"
                    priority
                  />
                ) : (
                  <div className="w-full h-full bg-[#f3f3f3] flex items-center justify-center">
                    <span className="text-[#999] text-xs uppercase tracking-wider">
                      {cat.name.charAt(0).toUpperCase() + cat.name.charAt(1).toUpperCase()}
                    </span>
                  </div>
                )}
                <div className="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors" />
              </div>
              <h3 className="text-lg font-serif text-center text-[#1a1a1a] group-hover:text-[#d4a373] transition-colors">
                {cat.name}
              </h3>
            </Link>
          ))}
        </div>
      </section>

      {/* Featured Products */}
      {products.length > 0 && (
        <section className="mb-20 bg-[#fdfaf5] py-16">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <SectionHeading title="Featured Products" subtitle="Handpicked for you" />
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8">
              {products.map((product: any) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          </div>
        </section>
      )}

      {/* WhatsApp CTA */}
      {whatsapp && (
        <section className="bg-[#d4a373] text-white py-20 px-6 text-center">
          <div className="relative z-10">
            <h2 className="text-4xl md:text-5xl font-serif font-bold mb-4">Have a Question?</h2>
            <p className="text-xl mb-8 max-w-2xl mx-auto">WhatsApp us for enquiries, custom orders, and more.</p>
            <a
              href={`https://wa.me/${whatsapp}?text=${encodeURIComponent('Hello Royal Shine, I have an enquiry about your jewellery collection.')}`}
              className="bg-white text-[#d4a373] px-8 py-4 font-bold text-lg inline-block hover:bg-[#fdfaf5] transition-colors shadow-lg"
              target="_blank"
              rel="noopener noreferrer"
            >
              Message on WhatsApp
            </a>
          </div>
        </section>
      )}

      <Footer />
    </main>
  );
}
