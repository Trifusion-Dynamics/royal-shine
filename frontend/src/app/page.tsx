import Link from 'next/link';
import Image from 'next/image';
import { productsService } from '@/services/products.service';
import { bannersService } from '@/services/banners.service';
import { categoriesService } from '@/services/categories.service';
import { collectionsService } from '@/services/collections.service';
import { galleriesService } from '@/services/galleries.service';
import { testimonialsService } from '@/services/testimonials.service';
import { settingsService } from '@/services/settings.service';
import { cn } from '@/lib/utils';
import { formatPrice } from '@/lib/utils';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import { ProductCard } from '@/components/ui/product-card';

export const metadata = {
  title: 'Royal Shine | Fine Jewellery',
  description: 'Explore thoughtfully curated gold, diamond and occasion jewellery at Royal Shine.',
};

async function getData() {
  const [banners, featuredProducts, categories, collections, galleries, testimonials, settings] =
    await Promise.allSettled([
      bannersService.getAll(false),
      productsService.getFeatured(),
      categoriesService.getAll(false),
      collectionsService.getAll(false),
      galleriesService.getAll(false),
      testimonialsService.getAll(false),
      settingsService.getAll(),
    ]);

  const unwrap = (r: any, fallback: any) =>
    r.status === 'fulfilled' ? (r.value.data || r.value || fallback) : fallback;

  const allBanners: any[] = unwrap(banners, []);
  const allCategories: any[] = unwrap(categories, []);
  const allCollections: any[] = unwrap(collections, []);
  const allGalleries: any[] = unwrap(galleries, []);
  const allTestimonials: any[] = unwrap(testimonials, []);
  const allSettingsObj: Record<string, string> = unwrap(settings, {});
  const allSettings: any[] = Object.entries(allSettingsObj).map(([key, value]) => ({ key, value }));

  return {
    banners: allBanners.filter((b: any) => b.status === 'active').slice(0, 6),
    featuredProducts: unwrap(featuredProducts, []).filter((p: any) => p.status === 'published').slice(0, 8),
    categories: allCategories.filter((c: any) => c.status === 'active' && c.isFeatured).slice(0, 6),
    collections: allCollections.filter((c: any) => c.status === 'active' && c.isFeatured).slice(0, 6),
    galleries: allGalleries.filter((g: any) => g.status === 'active').slice(0, 9),
    testimonials: allTestimonials.filter((t: any) => t.isApproved).slice(0, 6),
    settings: allSettings,
  };
}

export default async function HomePage() {
  const { banners, featuredProducts, categories, collections, galleries, testimonials, settings } =
    await getData();

  const announcement = settings.find((s: any) => s.key === 'announcement_bar_text');
  const announcementEnabled = settings.find((s: any) => s.key === 'announcement_bar_enabled');

  return (
    <main className="min-h-screen">
      {announcementEnabled?.value === 'true' && announcement?.value && (
        <div className="announcement">
          {announcement.value} <span>✦</span>
        </div>
      )}
      <Header />
      <section className="hero">
        <div className="hero-copy">
          {banners.length > 0 ? (
            banners.map((banner: any) => (
              <div key={banner.id} className="mb-8 last:mb-0">
                <p className="eyebrow">{banner.heading}</p>
                <h1 className="mb-4">{banner.subheading}</h1>
                {banner.ctaLabel && banner.ctaUrl && (
                  <Link
                    href={banner.ctaUrl}
                    className="button button-dark inline-block"
                  >
                    {banner.ctaLabel} <span>→</span>
                  </Link>
                )}
              </div>
            ))
          ) : (
            <>
              <p className="eyebrow">THE ART OF ADORNMENT</p>
              <h1>Every piece tells<br /><em>your story.</em></h1>
              <p className="hero-text">
                Discover jewellery that celebrates your moments, your milestones and everything that makes you beautifully you.
              </p>
              <div className="hero-actions">
                <Link href="/jewellery" className="button button-dark inline-block">
                  Explore jewellery <span>→</span>
                </Link>
                <Link href="/about-us" className="text-link inline-block">
                  Our story <span>→</span>
                </Link>
              </div>
            </>
          )}
        </div>
        <div className="hero-art" role="img" aria-label="Elegant gold jewellery editorial composition">
          <div className="art-orbit orbit-one" />
          <div className="art-orbit orbit-two" />
          <div className="art-stone">R</div>
          <div className="art-caption">
            TIMELESS BY DESIGN
            <b>ROYAL SHINE</b>
          </div>
        </div>
      </section>

      <section className="intro section-wrap">
        <p className="eyebrow">A WORLD OF WONDER</p>
        <h2>Find the piece that feels<br /><em>like it was made for you.</em></h2>
        <p>
          From everyday elegance to unforgettable celebrations, explore designs chosen to make every moment shine.
        </p>
      </section>

      {featuredProducts.length > 0 && (
        <section className="collection-section section-wrap">
          <div className="section-heading">
            <div>
              <p className="eyebrow">CURATED FOR YOU</p>
              <h2>Featured <em>pieces</em></h2>
            </div>
            <Link href="/jewellery" className="text-link inline-block">
              View all jewellery →
            </Link>
          </div>
          <div className="product-grid">
            {featuredProducts.map((product: any) => (
              <ProductCard key={product.id} product={product} />
            ))}
          </div>
        </section>
      )}

      {categories.length > 0 && (
        <section className="section-wrap">
          <div className="section-heading">
            <div>
              <p className="eyebrow">SHOP BY CATEGORY</p>
              <h2>Explore our <em>collections</em></h2>
            </div>
          </div>
          <div className="product-grid">
            {categories.map((cat: any) => (
              <Link key={cat.id} href={`/jewellery?category=${cat.slug}`} className="product-card group block">
                <div className="product-image">
                  {cat.image ? (
                    <Image
                      src={cat.image}
                      alt={cat.name}
                      fill
                      sizes="25vw"
                      className="object-cover object-center transition-transform duration-500 group-hover:scale-105"
                      priority
                    />
                  ) : (
                    <div className="w-full h-full flex items-center justify-center bg-dark-50 rounded-2xl">
                      <span className="text-gold-400 font-serif text-2xl">{cat.name.charAt(0)}</span>
                    </div>
                  )}
                </div>
                <p className="product-category mt-4">{cat.name}</p>
                <h3 className="font-serif font-semibold text-dark-900 mb-1 group-hover:text-gold-600 transition-colors">
                  {cat.description?.length! > 0 ? cat.description : 'Premium collection'}
                </h3>
              </Link>
            ))}
          </div>
        </section>
      )}

      <section className="story-band">
        <p className="eyebrow">THE ROYAL SHINE PROMISE</p>
        <h2>Beauty in every detail.<br /><em>Meaning in every moment.</em></h2>
        <Link href="/about-us" className="button button-light inline-block">
          Discover our story ↗
        </Link>
      </section>

      {testimonials.length > 0 && (
        <section className="section-wrap">
          <div className="section-heading">
            <div>
              <p className="eyebrow">CLIENT STORIES</p>
              <h2>What they <em>say</em></h2>
            </div>
          </div>
          <div className="grid grid-cols-2 md:grid-cols-3 gap-8">
            {testimonials.map((t: any) => (
              <Card key={t.id} padded={false}>
                <div className="flex items-center gap-4 mb-4">
                  {t.image ? (
                    <Image src={t.image} alt={t.customer_name} width={48} height={48} className="rounded-full object-cover" />
                  ) : (
                    <div className="w-12 h-12 rounded-full bg-dark-50 flex items-center justify-center">
                      <span className="text-gold-600 font-serif font-bold">{t.customer_name.charAt(0)}</span>
                    </div>
                  )}
                  <div>
                    <p className="font-semibold text-dark-900 text-sm">{t.customer_name}</p>
                    <p className="text-xs text-dark-400">{t.customer_location}</p>
                  </div>
                </div>
                <div className="flex text-gold-600 mb-3">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <span key={star} className="text-xs">
                      {star <= (t.rating || 5) ? '★' : '☆'}
                    </span>
                  ))}
                </div>
                <p className="text-dark-600 text-sm italic">{t.testimonial}</p>
              </Card>
            ))}
          </div>
        </section>
      )}

      {galleries.length > 0 && (
        <section className="section-wrap">
          <div className="section-heading">
            <div>
              <p className="eyebrow">INSPIRATION</p>
              <h2>Moments captured in <em>gold</em></h2>
            </div>
            <Link href="/gallery" className="text-link inline-block">
              View gallery →
            </Link>
          </div>
          <div className="grid grid-cols-2 md:grid-cols-3 gap-6">
            {galleries.map((g: any) => (
              <div key={g.id} className="aspect-[1] overflow-hidden rounded-2xl bg-dark-50 group">
                <Image
                  src={g.image}
                  alt={g.title || 'Gallery'}
                  fill
                  sizes="25vw"
                  className="object-cover object-center transition-transform duration-500 group-hover:scale-105"
                  priority
                />
                <div className="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-all duration-300 flex items-end p-4">
                  <h3 className="text-white font-serif font-semibold mb-1">{g.title}</h3>
                  <p className="text-sm text-dark-100">{g.description}</p>
                </div>
              </div>
            ))}
          </div>
        </section>
      )}

      <Footer />
    </main>
  );
}

function Card({ children, padded = true }: { children: React.ReactNode; padded?: boolean }) {
  return (
    <div className={cn('bg-white rounded-2xl border border-dark-100 shadow-sm p-6', padded ? '' : 'p-0')}>
      {children}
    </div>
  );
}
