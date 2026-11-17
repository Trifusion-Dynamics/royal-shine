'use client';

import Link from 'next/link';
import { useState, useEffect } from 'react';
import { productsService } from '@/services/products.service';
import { settingsService } from '@/services/settings.service';
import { formatPrice } from '@/lib/utils';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import toast from 'react-hot-toast';
import { useCart } from '@/contexts/CartContext';
import { Button } from '@/components/ui/button';
import type { Product } from '@/types';

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
  category?: { id: number; name: string; slug: string } | null;
  images?: Array<{ id: number; image: string; alt_text?: string | null; is_primary: boolean }>;
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

export default function ProductClient({ slug }: { slug: string }) {
  const [product, setProduct] = useState<ProductDetail | null>(null);
  const [loading, setLoading] = useState(true);
  const [settings, setSettings] = useState<Record<string, string>>({});
  const [qty, setQty] = useState(1);
  const { addItem } = useCart();

  useEffect(() => {
    let mounted = true;
    (async () => {
      try {
        const data = await getProduct(slug);
        if (!mounted) return;
        setProduct(data);
      } finally {
        if (mounted) setLoading(false);
      }
      try {
        const sRes = await settingsService.getAll();
        const sMap: Record<string, string> = typeof sRes === 'object' && !Array.isArray(sRes) ? sRes : {};
        if (mounted) setSettings(sMap);
      } catch {}
    })();
    return () => { mounted = false; };
  }, [slug]);

  if (loading) {
    return (
      <main className="min-h-screen">
        <Header />
        <section className="py-16">
          <div className="container-luxury text-center py-20">
            <div className="animate-pulse bg-champagne rounded-xl h-96 w-full mx-auto max-w-4xl" />
          </div>
        </section>
        <Footer />
      </main>
    );
  }

  if (!product) {
    return (
      <main className="min-h-screen flex items-center justify-center">
        <Header />
        <div className="text-center py-20">
          <h1 className="font-serif text-3xl text-charcoal mb-4">Product not found</h1>
          <p className="text-warm-gray mb-6">The jewellery piece you are looking for does not exist.</p>
          <Link href="/jewellery" className="btn btn-gold inline-block">Browse collection</Link>
        </div>
        <Footer />
      </main>
    );
  }

  const p = product as ProductDetail;
  const whatsapp = settings.whatsapp_number;
  const catName = p.category?.name;

  return (
    <main className="min-h-screen">
      <Header />
      <section className="section">
        <div className="container-luxury">
          <nav className="mb-10">
            <ol className="flex flex-wrap gap-2 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-warm-gray">
              <li><Link href="/" className="hover:text-gold transition-colors">Home</Link></li>
              {catName && (
                <>
                  <li aria-hidden="true" className="text-beige">/</li>
                  <li><Link href={`/jewellery?category=${p.category?.slug}`} className="hover:text-gold transition-colors">{catName}</Link></li>
                  <li aria-hidden="true" className="text-beige">/</li>
                </>
              )}
              <li><span className="text-charcoal font-medium">{p.name}</span></li>
            </ol>
          </nav>

          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="grid lg:grid-cols-2 gap-12">
              <div className="space-y-5">
                <div className="relative aspect-square overflow-hidden rounded-lg border border-beige bg-champagne shadow-sm">
                  {p.images?.[0]?.image ? (
                    <div className="w-full h-full flex items-center justify-center bg-champagne">
                      <span className="text-gold-600 font-serif text-4xl">R</span>
                    </div>
                  ) : (
                    <div className="w-full h-full flex items-center justify-center bg-champagne">
                      <span className="text-gold-600 font-serif text-4xl">R</span>
                    </div>
                  )}
                </div>
              </div>

              <div className="space-y-8">
                <div>
                  {catName && (
                    <Link href={`/jewellery?category=${p.category?.slug}`} className="inline-block text-sm font-medium text-gold uppercase tracking-wider hover:underline mb-3">
                      {catName}
                    </Link>
                  )}
                  <h1 className="text-4xl md:text-5xl font-serif text-charcoal mb-4 leading-tight">{p.name}</h1>
                  <p className="text-sm text-warm-gray uppercase tracking-wider font-medium">SKU: {p.sku}</p>
                </div>

                <div className="space-y-2">
                  {p.price_on_request ? (
                    <p className="text-2xl font-medium text-gold">Price on Request</p>
                  ) : p.price ? (
                    <div>
                      <p className="text-4xl font-bold text-charcoal">{formatPrice(p.price)}</p>
                      <p className="text-sm text-warm-gray">Inclusive of all taxes</p>
                    </div>
                  ) : (
                    <p className="text-2xl font-medium text-gold">Price on Request</p>
                  )}
                </div>

                <div className="mt-6 flex flex-col sm:flex-row gap-3">
                  {!p.price_on_request && p.price && (
                    <div className="flex items-center gap-3">
                      <button
                        onClick={() => setQty((q) => Math.max(1, q - 1))}
                        className="w-10 h-10 rounded border border-beige flex items-center justify-center text-charcoal hover:bg-champagne hover:text-gold transition-colors"
                      >
                        −
                      </button>
                      <input
                        type="number"
                        value={qty}
                        onChange={(e) => {
                          const v = parseInt(e.target.value, 10);
                          if (!isNaN(v) && v > 0) setQty(v);
                        }}
                        min="1"
                        className="w-12 h-10 text-center border border-beige rounded text-sm text-charcoal"
                      />
                      <button
                        onClick={() => setQty((q) => q + 1)}
                        className="w-10 h-10 rounded border border-beige flex items-center justify-center text-charcoal hover:bg-champagne hover:text-gold transition-colors"
                      >
                        +
                      </button>
                    </div>
                  )}
                  <Button
                    variant={p.price_on_request || !p.price ? 'outline' : 'gold'}
                    size="lg"
                    className="w-full"
                    onClick={() => {
                      if (p) {
                        const cartProduct = {
                          id: p.id,
                          category_id: p.category?.id ?? 0,
                          collection_id: p.category?.id ?? null,
                          name: p.name,
                          slug: p.slug,
                          sku: p.sku,
                          short_description: p.short_description ?? null,
                          description: p.description ?? null,
                          metal_type: p.metal_type,
                          gold_purity: p.gold_purity,
                          weight: p.weight,
                          weight_unit: p.weight_unit ?? null,
                          stone_details: p.stone_details ?? null,
                          price: p.price,
                          price_on_request: p.price_on_request,
                          is_featured: p.is_featured,
                          status: p.status,
                          seo_title: null,
                          seo_description: null,
                          sort_order: 0,
                          category: p.category,
                          images: p.images ?? [],
                          primary_image: p.images?.[0] ?? null,
                          created_at: new Date().toISOString(),
                          updated_at: new Date().toISOString(),
                        } as Product;
                        addItem(cartProduct, qty);
                        toast.success(`${p.name} added to cart`);
                      }
                    }}
                  >
                    {p.price_on_request || !p.price ? 'Enquire Now' : 'Add to Cart'}
                  </Button>
                  {p.price_on_request && (
                    <a
                      href={`/contact-us?product=${p.slug}`}
                      className="mt-3 block text-center text-xs text-warm-gray underline"
                    >
                      Prefer to message us? → Contact Us
                    </a>
                  )}
                </div>

                {(p.metal_type || p.gold_purity || p.weight || p.stone_details) && (
                  <div className="border border-beige bg-white p-6 rounded-lg">
                    <h3 className="font-serif font-bold text-charcoal mb-4 text-lg">Specifications</h3>
                    <dl className="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                      {p.metal_type && (
                        <div>
                          <dt className="text-warm-gray font-medium">Material</dt>
                          <dd className="text-charcoal">{p.metal_type.charAt(0).toUpperCase() + p.metal_type.slice(1)}</dd>
                        </div>
                      )}
                      {p.gold_purity && (
                        <div>
                          <dt className="text-warm-gray font-medium">Gold Purity</dt>
                          <dd className="text-charcoal">{p.gold_purity} Gold</dd>
                        </div>
                      )}
                      {p.weight && p.weight_unit && (
                        <div>
                          <dt className="text-warm-gray font-medium">Weight</dt>
                          <dd className="text-charcoal">{p.weight} {p.weight_unit}</dd>
                        </div>
                      )}
                      {p.stone_details && (
                        <div>
                          <dt className="text-warm-gray font-medium">Stone Details</dt>
                          <dd className="text-charcoal">{p.stone_details}</dd>
                        </div>
                      )}
                    </dl>
                  </div>
                )}

                {p.description && (
                  <div>
                    <h3 className="font-serif font-bold text-charcoal mb-4 text-lg">Description</h3>
                    <div className="text-warm-gray leading-relaxed">{p.description}</div>
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
