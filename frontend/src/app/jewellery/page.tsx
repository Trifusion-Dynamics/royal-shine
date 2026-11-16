import Link from 'next/link';
import Image from 'next/image';
import { productsService } from '@/services/products.service';
import { categoriesService } from '@/services/categories.service';
import { collectionsService } from '@/services/collections.service';
import { cn } from '@/lib/utils';
import { formatPrice } from '@/lib/utils';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import { ProductCard } from '@/components/ui/product-card';
import { Badge } from '@/components/ui/badge';

export const metadata = {
  title: 'Jewellery Collection | Royal Shine',
  description: 'Browse our complete jewellery collection - gold, diamond, silver and bridal pieces.',
};

type ProductItem = {
  id: number;
  name: string;
  slug: string;
  sku: string;
  short_description?: string | null;
  price: number | null;
  price_on_request: boolean;
  is_featured: boolean;
  status: string;
  category?: { id: number; name: string; slug: string };
  images?: Array<{ image: string; alt_text?: string | null; is_primary: boolean; sort_order: number }>;
  primary_image?: { image: string; alt_text?: string | null } | null;
};

async function getProducts(params?: Record<string, string | number | boolean | undefined>) {
  try {
    const cleanParams: Record<string, string | number | boolean> = {};
    if (params) {
      for (const [key, value] of Object.entries(params)) {
        if (value !== undefined) cleanParams[key] = value;
      }
    }
    const res = await productsService.getAll(cleanParams);
    const data = res.data || res;
    return Array.isArray(data) ? data : data.data || [];
  } catch {
    return [];
  }
}

async function getCategories() {
  try {
    const res = await categoriesService.getAll(false);
    const data = res.data || res;
    return Array.isArray(data) ? data : data.data || [];
  } catch {
    return [];
  }
}

async function getCollections() {
  try {
    const res = await collectionsService.getAll(false);
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

  const [products, categories, collections] = await Promise.all([
    getProducts({
      categorySlug: category,
      collectionSlug: collection,
      search,
      metalType: metal,
      status: 'published',
      sortBy: sort,
      limit: 40,
    }),
    getCategories(),
    getCollections(),
  ]);

  return (
    <main className="min-h-screen">
      <Header />
      <section className="section-wrap">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <nav className="flex flex-wrap gap-2 mb-8 text-sm">
            <Link
              href="/jewellery"
              className={cn('px-3 py-1.5 rounded-full border transition-colors', !category && !collection && !search && !metal ? 'border-gold-600 text-gold-600 bg-gold-50' : 'border-dark-200 text-dark-600 hover:border-gold-600')}
            >
              All
            </Link>
            {categories.map((cat: any) => (
              <Link
                key={cat.id}
                href={`/jewellery?category=${cat.slug}`}
                className={cn('px-3 py-1.5 rounded-full border transition-colors', category === cat.slug ? 'border-gold-600 text-gold-600 bg-gold-50' : 'border-dark-200 text-dark-600 hover:border-gold-600')}
              >
                {cat.name}
              </Link>
            ))}
            {collections.map((col: any) => (
              <Link
                key={col.id}
                href={`/jewellery?collection=${col.slug}`}
                className={cn('px-3 py-1.5 rounded-full border transition-colors', collection === col.slug ? 'border-gold-600 text-gold-600 bg-gold-50' : 'border-dark-200 text-dark-600 hover:border-gold-600')}
              >
                {col.name}
              </Link>
            ))}
          </nav>

          <div className="flex flex-col lg:flex-row gap-8">
            <aside className="lg:w-1/4 flex-shrink-0">
              <h3 className="font-serif font-semibold text-dark-900 mb-6">Filter by Metal</h3>
              <div className="space-y-3">
                {['gold', 'silver', 'artificial'].map((m) => (
                  <Link
                    key={m}
                    href={`/jewellery?${category ? `category=${category}&` : ''}metal=${m}${collection ? `&collection=${collection}` : ''}${search ? `&search=${search}` : ''}`}
                    className={cn(
                      'block px-4 py-2 rounded-lg border transition-colors text-sm',
                      metal === m ? 'border-gold-600 bg-gold-50 text-gold-700 font-medium' : 'border-transparent text-dark-600 hover:bg-dark-50 hover:border-dark-200'
                    )}
                  >
                    {m.charAt(0).toUpperCase() + m.slice(1)}
                  </Link>
                ))}
              </div>
            </aside>

            <div className="lg:flex-1">
              <div className="flex items-center justify-between mb-8">
                <h2 className="font-serif font-semibold text-xl text-dark-900">
                  {products.length} piece{products.length !== 1 ? 's' : ''} found
                </h2>
                <div className="flex items-center gap-3">
                  <span className="text-sm text-dark-500">Sort by:</span>
                  <select
                    defaultValue={sort}
                    className="text-sm border border-dark-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:border-gold-600"
                  >
                    <option value="latest">Latest</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="price_high">Price: High to Low</option>
                  </select>
                </div>
              </div>

              <div className="product-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                {products.length > 0 ? (
                  (products as ProductItem[]).map((product) => (
                    <ProductCard key={product.id} product={product as any} />
                  ))
                ) : (
                  <div className="empty-catalog py-20">
                    <span>✧</span>
                    <h3>No jewellery found</h3>
                    <p>Try adjusting your filters or browse our full collection.</p>
                    <Link href="/jewellery" className="button button-dark inline-block mt-6">
                      Reset filters
                    </Link>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </main>
  );
}
