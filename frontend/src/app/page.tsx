import Link from 'next/link';
import Image from 'next/image';
import { useEffect, useState } from 'react';
import { productsService } from '@/services/products.service';
import { bannersService } from '@/services/banners.service';
import { categoriesService } from '@/services/categories.service';
import { collectionsService } from '@/services/collections.service';
import { galleriesService } from '@/services/galleries.service';
import { testimonialsService } from '@/services/testimonials.service';
import { settingsService } from '@/services/settings.service';
import { formatPrice } from '@/lib/utils';
import { productImage } from '@/lib/imagekit';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import { ProductCard } from '@/components/ui/product-card';
import { CategoryCard } from '@/components/ui/category-card';
import { SectionHeading } from '@/components/ui/section-heading';
import { Badge } from '@/components/ui/badge';

export const metadata = {
  title: 'Royal Shine | Fine Jewellery',
  description: 'Explore thoughtfully curated gold, diamond and occasion jewellery at Royal Shine.',
};

async function getData() {
  const [banners, featuredProducts, categories, collections, galleries, testimonials, settingsRes] =
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
  const allSettingsObj: Record<string, string> = unwrap(settingsRes, {});
  const allSettings: any[] = Object.entries(allSettingsObj).map(([key, value]) => ({ key, value }));

  return {
    banners: allBanners.filter((b: any) => b.status === 'active').slice(0, 6),
    featuredProducts: unwrap(featuredProducts, []).filter((p: any) => p.status === 'published').slice(0, 8),
    categories: allCategories.filter((c: any) => c.status === 'active' && c.isFeatured).slice(0, 12),
    collections: allCollections.filter((c: any) => c.status === 'active' && c.isFeatured).slice(0, 6),
    galleries: allGalleries.filter((g: any) => g.status === 'active').slice(0, 8),
    testimonials: allTestimonials.filter((t: any) => t.isApproved).slice(0, 6),
    settings: allSettings,
  };
}

export default async function HomePage() {
  const { banners, featuredProducts, categories, collections, galleries, testimonials, settings } =
    await getData();

  const announcement = settings.find((s: any) => s.key === 'announcement_bar_text');
  const announcementEnabled = settings.find((s: any) => s.key === 'announcement_bar_enabled');

  const [newArrivalsRes] = await Promise.allSettled([productsService.getAll({ status: 'published', limit: 8, sortBy: 'createdAt', sortOrder: 'desc' })]);
  const newArrivals = newArrivalsRes.status === 'fulfilled'
    ? ((newArrivalsRes.value.data || newArrivalsRes.value || []) as any[]).filter((p: any) => p.status === 'published').slice(0, 8)
    : [];

  const aboutPage = settings.find((s: any) => s.key === 'about_page_content');
  const aboutImage = settings.find((s: any) => s.key === 'about_image');

  return (
    <main className="min-h-screen">
      {announcementEnabled?.value === 'true' && announcement?.value && (
        <div className="bg-champagne text-charcoal border-b border-beige px-4 py-2">
          <div className="container-luxury flex items-center gap-3 text-sm font-medium">
            {announcement.value} <span className="text-gold">✦</span>
          </div>
        </div>
      )}
      <Header />

      {/* Hero Editorial Swiper */}
      {banners.length > 0 ? (
        <section className="relative overflow-hidden bg-charcoal">
          <div className="relative h-[700px] md:h-[820px] lg:h-[950px] overflow-hidden">
            <div className="relative h-full w-full overflow-hidden">
              {banners.map((banner: any, idx: number) => (
                <div key={banner.id} className="absolute inset-0 w-full h-full opacity-0 transition-opacity duration-1000 ease-in-out">
                  <Image
                    src={banner.image}
                    alt={banner.altText || banner.heading}
                    fill
                    sizes="(max-width: 768px) 100vw, 100vw"
                    className="object-cover object-center"
                    priority={idx === 0}
                    loading={idx === 0 ? 'eager' : 'lazy'}
                  />
                  {banner.mobileImage && (
                    <Image
                      src={banner.mobileImage}
                      alt={banner.altText || banner.heading}
                      fill
                      sizes="(max-width: 768px) 100vw"
                      className="object-cover object-center hidden md:block"
                      priority={idx === 0}
                      loading={idx === 0 ? 'eager' : 'lazy'}
                    />
                  )}
                  <div className="absolute inset-0 bg-gradient-to-t from-charcoal/95 via-charcoal/60 to-charcoal/20" />
                  <div className="absolute inset-0 px-6 md:px-12 pt-16 md:pt-32 pb-24 flex items-end lg:items-center z-10">
                    <div className="max-w-2xl lg:max-w-3xl">
                      <span className="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white/10 backdrop-blur-sm text-gold text-[11px] font-semibold uppercase tracking-[0.18em] mb-6 border border-gold/40 rounded-full backdrop-filter">
                        New Collection
                      </span>
                      <h1 className="font-serif text-5xl md:text-7xl lg:text-8xl text-white mb-6 leading-[1.05] tracking-tight">
                        {banner.heading}
                      </h1>
                      {banner.subheading && (
                        <p className="text-lg md:text-xl text-white/90 mb-10 leading-relaxed max-w-lg">
                          {banner.subheading}
                        </p>
                      )}
                      <div className="flex flex-col sm:flex-row gap-4">
                        {banner.ctaLabel && banner.ctaUrl && (
                          <a href={banner.ctaUrl} className="btn btn-gold btn-lg">
                            {banner.ctaLabel}
                          </a>
                        )}
                        <Link href="/about-us" className="btn btn-outline text-white border-white hover:bg-white hover:text-charcoal btn-lg">
                          Discover Our Story
                        </Link>
                      </div>
                    </div>
                  </div>
                </div>
              ))}
              {banners.length > 1 && (
                <>
                  <button className="swiper-button-prev absolute left-6 top-1/2 -translate-y-1/2 z-30 w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-charcoal transition-all flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M15 19l-7-7 7-7" />
                    </svg>
                  </button>
                  <button className="swiper-button-next absolute right-6 top-1/2 -translate-y-1/2 z-30 w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-charcoal transition-all flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M9 5l7 7-7 7" />
                    </svg>
                  </button>
                  <div className="swiper-pagination bottom-6 left-1/2 -translate-x-1/2 z-30 flex gap-3">
                    {banners.map((_, idx) => (
                      <button
                        key={idx}
                        className={`w-2.5 h-2.5 rounded-full transition-colors ${idx === 0 ? 'bg-gold' : 'bg-white/40'}`}
                      />
                    ))}
                  </div>
                </>
              )}
            </div>
          </div>
        </section>
      ) : (
        <section className="relative h-[700px] md:h-[820px] lg:h-[950px] overflow-hidden bg-charcoal">
          <div className="absolute inset-0 w-full h-full">
            <Image
              src="/images/placeholder-jewel.jpg"
              alt="Royal Shine jewellery collection"
              fill
              sizes="(max-width: 768px) 100vw, 100vw"
              className="object-cover object-center"
              priority
              loading="eager"
            />
          </div>
          <div className="absolute inset-0 bg-gradient-to-t from-charcoal/95 via-charcoal/60 to-charcoal/20" />
          <div className="absolute inset-0 px-6 md:px-12 pt-16 md:pt-32 pb-24 flex items-end lg:items-center z-10">
            <div className="max-w-2xl lg:max-w-3xl">
              <span className="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white/10 backdrop-blur-sm text-gold text-[11px] font-semibold uppercase tracking-[0.18em] mb-6 border border-gold/40 rounded-full backdrop-filter">
                New Collection
              </span>
              <h1 className="font-serif text-5xl md:text-7xl lg:text-8xl text-white mb-6 leading-[1.05] tracking-tight">
                Elegance That Lives Forever
              </h1>
              <p className="text-lg md:text-xl text-white/90 mb-10 leading-relaxed max-w-lg">
                Discover exquisite jewellery crafted to celebrate your most beautiful moments.
              </p>
              <div className="flex flex-col sm:flex-row gap-4">
                <Link href="/jewellery" className="btn btn-gold btn-lg">Explore Collection</Link>
                <Link href="/about-us" className="btn btn-outline text-white border-white hover:bg-white hover:text-charcoal btn-lg">
                  Discover Our Story
                </Link>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* Shop by Category */}
      {categories.length > 0 && (
        <section className="bg-champagne section">
          <div className="container-luxury">
            <SectionHeading eyebrow="Curated Collections" title="Shop by Category" subtitle="Explore our curated jewellery collections, crafted for every moment." />
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 md:gap-8">
              {categories.map((cat: any) => (
                <CategoryCard key={cat.id} category={cat} />
              ))}
            </div>
            <div className="mt-16 text-center">
              <Link href="/jewellery" className="btn btn-outline">
                View All Categories
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </Link>
            </div>
          </div>
        </section>
      )}

      {/* Featured Collections */}
      {collections.length > 0 && (
        <section className="bg-[#f7f1e8] py-16 md:py-24">
          <div className="container-luxury">
            <div className="mx-auto mb-10 max-w-2xl text-center md:mb-14">
              <p className="mb-3 text-[10px] font-semibold uppercase tracking-[.28em] text-[#a77b43]">The Royal Shine edit</p>
              <h2 className="font-serif text-3xl leading-tight text-[#29231e] md:text-5xl">Featured Collections</h2>
              <p className="mx-auto mt-4 max-w-lg text-sm leading-7 text-[#766b60] md:text-base">
                Handpicked jewellery collections for celebrations, everyday elegance and unforgettable moments.
              </p>
              <span className="mx-auto mt-6 block h-px w-14 bg-[#bd955e]" />
            </div>

            <div className="grid grid-cols-1 items-stretch gap-5 sm:grid-cols-2 lg:grid-cols-3 lg:gap-7">
              {collections.map((collection: any) => (
                <Link
                  key={collection.id}
                  href={`/collections/${collection.slug}`}
                  className="group flex h-full min-w-0 flex-col overflow-hidden border border-[#e8ddcf] bg-white shadow-[0_4px_18px_rgba(50,35,20,.05)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_16px_38px_rgba(50,35,20,.12)]"
                >
                  <div className="relative aspect-[4/3] w-full overflow-hidden bg-[#eee5d8]">
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
                      {collection.description && (
                        <p className="mt-2 line-clamp-2 text-xs leading-5 text-[#81766a] md:text-sm">{collection.description}</p>
                      )}
                      {!collection.description && (
                        <p className="mt-2 text-xs leading-5 text-[#81766a] md:text-sm">Discover the designs in this collection.</p>
                      )}
                    </div>
                    <span className="grid h-10 w-10 shrink-0 place-items-center rounded-full border border-[#dfceb5] text-lg text-[#9a713e] transition-all group-hover:border-[#9a713e] group-hover:bg-[#9a713e] group-hover:text-white" aria-hidden="true">
                      ↗
                    </span>
                  </div>
                </Link>
              ))}
            </div>

            <div className="mt-10 text-center">
              <Link href="/collections" className="inline-flex items-center gap-2 border border-[#a77b43] px-6 py-3 text-[10px] font-semibold uppercase tracking-[.18em] text-[#76542d] transition hover:bg-[#a77b43] hover:text-white">
                View all collections<span aria-hidden="true"> →</span>
              </Link>
            </div>
          </div>
        </section>
      )}

      {/* Featured Products */}
      {featuredProducts.length > 0 && (
        <section className="bg-ivory section">
          <div className="container-luxury">
            <div className="flex flex-col lg:flex-row items-end justify-between gap-8 mb-16">
              <div className="max-w-2xl">
                <SectionHeading eyebrow="Curator's Picks" title="Featured Pieces" subtitle="Handpicked designs our clients love most." />
              </div>
              <Link href="/jewellery" className="btn btn-outline whitespace-nowrap">
                Browse All
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </Link>
            </div>

            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-8 md:gap-10">
              {featuredProducts.map((product: any) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          </div>
        </section>
      )}

      {/* New Arrivals */}
      {newArrivals.length > 0 && (
        <section className="section">
          <div className="container-luxury">
            <SectionHeading eyebrow="Just In" title="New Arrivals" subtitle="The latest additions to our collection." />
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-8 md:gap-10">
              {newArrivals.map((product: any) => (
                <article key={product.id} className="card group">
                  <div className="image-wrap aspect-square relative">
                    {product.images?.[0]?.image ? (
                      <Image
                        src={productImage(product.images[0], { width: 400, height: 400, quality: 85, format: 'webp' })}
                        alt={product.images[0].altText || product.name}
                        fill
                        className="object-cover object-center transition-transform duration-700 group-hover:scale-105"
                      />
                    ) : (
                      <div className="w-full h-full lazy-placeholder flex items-center justify-center">
                        <span className="text-warm-gray text-xs font-medium uppercase tracking-[0.2em]">
                          {product.name.charAt(0).toUpperCase()}
                        </span>
                      </div>
                    )}
                    <span className="absolute top-3 left-3 z-10 inline-flex items-center px-2.5 py-1 bg-charcoal text-white text-[10px] font-semibold uppercase tracking-[0.16em] rounded-full shadow-sm">
                      New
                    </span>
                  </div>
                  <div className="p-6">
                    {product.category?.name && (
                      <p className="text-[11px] font-semibold text-gold uppercase tracking-[0.16em] mb-1.5">
                        {product.category.name}
                      </p>
                    )}
                    <h3 className="font-serif text-lg text-charcoal mb-3 leading-tight group-hover:text-gold transition-colors">
                      <Link href={`/product/${product.slug}`}>{product.name}</Link>
                    </h3>
                    <div className="flex items-baseline gap-2 mb-6">
                      {product.price_on_request || !product.price ? (
                        <span className="text-sm font-medium text-gold">Price on Request</span>
                      ) : (
                        <span className="text-xl font-semibold text-charcoal">{formatPrice(product.price)}</span>
                      )}
                    </div>
                    <Link
                      href={`/contact-us?product=${product.id}`}
                      className="btn btn-text text-gold hover:text-brown text-sm inline-flex items-center gap-1.5"
                    >
                      Enquire
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M13 7l5 5m0 0l-5 5m5-5H6" />
                      </svg>
                    </Link>
                  </div>
                </article>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Brand Story */}
      {(aboutImage?.value || aboutPage?.value) && (
        <section className="section">
          <div className="container-luxury">
            <div className="grid lg:grid-cols-2 gap-12 lg:gap-24 items-center">
              {aboutImage?.value && (
                <div className="relative h-[560px] md:h-[660px] overflow-hidden rounded-xl border border-beige shadow-luxury-lg reveal reveal-delay-1">
                  <Image
                    src={aboutImage.value}
                    alt={aboutPage?.value || 'Royal Shine Brand Story'}
                    fill
                    sizes="(max-width: 768px) 100vw, 50vw"
                    className="object-cover object-center transition-transform duration-[900ms] ease-out"
                    priority
                  />
                </div>
              )}
              <div className="reveal reveal-delay-2 max-w-lg">
                <span className="inline-block text-[11px] font-semibold text-gold uppercase tracking-[0.2em] mb-5 block">
                  Our Story
                </span>
                <h2 className="font-serif text-3xl md:text-5xl text-charcoal mb-7 leading-tight">
                  Crafted with Passion.<br className="hidden md:block" /><span className="text-gold">Worn with Pride.</span>
                </h2>
                {aboutPage?.value ? (
                  <div className="prose text-warm-gray leading-relaxed max-w-none mb-8" dangerouslySetInnerHTML={{ __html: aboutPage.value }} />
                ) : (
                  <>
                    <p className="text-base md:text-lg text-warm-gray leading-relaxed mb-6 max-w-lg">
                      Jewellery can hold a memory, mark a milestone, or simply make an ordinary day feel special.
                      Explore the Royal Shine catalogue to discover pieces for celebrations and everyday expression.
                    </p>
                    <p className="text-base md:text-lg text-warm-gray leading-relaxed mb-8 max-w-lg">
                      Browse necklaces, earrings, rings, bangles and more. Each product page shares the available
                      details and an easy way to ask our team a question.
                    </p>
                  </>
                )}
                <Link href="/about-us" className="btn btn-outline">
                  Our Story
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M13 7l5 5m0 0l-5 5m5-5H6" />
                  </svg>
                </Link>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* Gallery */}
      {galleries.length > 0 && (
        <section className="bg-champagne section">
          <div className="container-luxury">
            <SectionHeading eyebrow="Captured Moments" title="Our Gallery" subtitle="Moments captured in gold, light and elegance." />
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
              {galleries.map((g: any) => (
                <div key={g.id} className="relative aspect-[4/5] overflow-hidden rounded-xl border border-beige bg-white group card">
                  <Image
                    src={g.image}
                    alt={g.altText || g.title || 'Gallery'}
                    fill
                    sizes="(max-width: 768px) 100vw, 25vw"
                    className="object-cover object-center transition-transform duration-[900ms] ease-out group-hover:scale-105"
                    priority
                  />
                  <div className="absolute inset-x-0 bottom-0 p-5 pt-16 bg-gradient-to-t from-[#25221f]/90 to-transparent">
                    <span className="text-sm font-medium text-white block mb-1">{g.title}</span>
                    {g.description && (
                      <p className="text-xs text-white/70 line-clamp-1">{g.description}</p>
                    )}
                  </div>
                </div>
              ))}
            </div>
            <div className="mt-16 text-center">
              <Link href="/gallery" className="btn btn-text text-gold hover:text-brown">
                View Gallery
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </Link>
            </div>
          </div>
        </section>
      )}

      {/* Testimonials */}
      {testimonials.length > 0 && (
        <section className="section">
          <div className="container-luxury">
            <SectionHeading eyebrow="Client Experiences" title="Kind Words" subtitle="Experiences shared by our customers." />
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
              {testimonials.map((t: any) => (
                <figure key={t.id} className="border border-beige bg-white p-8 rounded-lg card">
                  <div className="flex items-center gap-4 mb-6">
                    {t.image ? (
                      <div className="w-10 h-10 shrink-0 rounded-full overflow-hidden border border-beige">
                        <Image
                          src={t.image}
                          alt={t.customerName}
                          fill
                          className="object-cover"
                          sizes="40px"
                        />
                      </div>
                    ) : (
                      <div className="w-10 h-10 shrink-0 rounded-full bg-beige flex items-center justify-center">
                        <span className="text-warm-gray text-xs font-medium uppercase tracking-wider">
                          {t.customerName.charAt(0).toUpperCase()}
                        </span>
                      </div>
                    )}
                    <div>
                      <p className="text-sm font-medium text-charcoal leading-tight">{t.customerName}</p>
                      {t.customerLocation && (
                        <p className="text-xs text-warm-gray uppercase tracking-wider">{t.customerLocation}</p>
                      )}
                    </div>
                  </div>
                  <div className="flex text-gold mb-6">
                    {[1, 2, 3, 4, 5].map((star) => (
                      <span key={star} className="w-4 h-4 text-[12px]">
                        {star <= (t.rating || 5) ? '★' : '☆'}
                      </span>
                    ))}
                  </div>
                  <p className="text-warm-gray leading-relaxed italic">{`“${t.testimonial}”`}</p>
                </figure>
              ))}
            </div>
            <div className="mt-14 text-center">
              <Link href="/gallery" className="btn btn-text text-gold hover:text-brown">
                Read All Stories
              </Link>
            </div>
          </div>
        </section>
      )}

      {/* Contact / WhatsApp CTA */}
      <section className="section">
        <div className="container-luxury">
          <div className="border border-beige bg-ivory rounded-2xl p-8 md:p-14 text-center lg:text-left shadow-sm">
            <div className="lg:flex lg:items-center lg:justify-between gap-10">
              <div className="lg:max-w-2xl">
                <h2 className="text-3xl md:text-4xl font-serif text-charcoal mb-5 leading-tight">
                  Let&rsquo;s Find Something Beautiful
                </h2>
                <p className="text-warm-gray leading-relaxed mb-8 max-w-lg">
                  Have a question about a design or looking for something special? Our team is here to help.
                </p>
              </div>
              <div className="flex flex-col sm:flex-row gap-3 justify-center lg:justify-end">
                <a
                  href={`https://wa.me/${settings.find((s: any) => s.key === 'whatsapp_number')?.value}?text=${encodeURIComponent('Hi Royal Shine, I would like to enquire about your jewellery collection.')}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="btn btn-brown btn-lg whitespace-nowrap flex items-center gap-2"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                  Chat on WhatsApp
                </a>
                <Link href="/contact-us" className="btn btn-outline btn-lg whitespace-nowrap">
                  Contact Us
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </main>
  );
}
