import Link from 'next/link';
import Image from 'next/image';
import { pagesService } from '@/services/pages.service';
import { settingsService } from '@/services/settings.service';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import { SectionHeading } from '@/components/ui/section-heading';

export const metadata = {
  title: 'About Us | Royal Shine',
  description: 'Discover the story and heritage behind Royal Shine jewellery.',
};

async function getData() {
  const [pageRes, settingsRes] = await Promise.allSettled([
    pagesService.getBySlug('about-us'),
    settingsService.getAll(),
  ]);

  const unwrap = (r: any, fallback: any) =>
    r.status === 'fulfilled' ? (r.value.data || r.value || fallback) : fallback;

  const page = unwrap(pageRes, null);
  const settingsObj: Record<string, string> = unwrap(settingsRes, {});

  return { page, settings: settingsObj };
}

export default async function AboutPage() {
  const { page, settings } = await getData();
  const aboutImage = settings.about_image;

  return (
    <main className="min-h-screen">
      <Header />
      <section className="py-16">
        <div className="container-luxury">
          <div className="text-center mb-16">
            <h1 className="text-5xl md:text-6xl font-serif text-charcoal mb-4 leading-tight">
              {page?.title || 'About Royal Shine'}
            </h1>
            <div className="w-20 h-[1px] bg-gold mx-auto mb-6" />
          </div>

          <div className="grid lg:grid-cols-2 gap-16 items-center">
            <div className="order-last lg:order-first">
              {aboutImage && (
                <div className="relative h-[600px] overflow-hidden rounded-lg border border-beige shadow-luxury-lg reveal reveal-delay-1">
                  <Image
                    src={aboutImage}
                    alt={page?.title || 'Royal Shine'}
                    fill
                    sizes="(max-width: 768px) 100vw, 50vw"
                    className="object-cover object-center group-hover:scale-105 transition-transform duration-700"
                    priority
                  />
                </div>
              )}
              {!aboutImage && (
                <div className="relative h-[600px] bg-champagne rounded-lg border border-beige flex items-center justify-center reveal reveal-delay-1">
                  <span className="font-serif text-8xl text-gold/20">RS</span>
                </div>
              )}
            </div>

            <div className="reveal reveal-delay-2">
              <div className="inline-flex items-center gap-1.5 px-3.5 py-1 bg-gold/10 text-gold text-xs font-semibold uppercase tracking-wider mb-5 border border-gold/30 rounded-full">
                Our Story
              </div>
              <h2 className="text-4xl md:text-5xl font-serif text-charcoal mb-6 leading-tight">
                Crafted with Passion.<br className="hidden md:block" />
                <span className="text-gold">Worn with Pride.</span>
              </h2>
              {page?.content ? (
                <div className="prose text-warm-gray leading-relaxed max-w-none mb-8" dangerouslySetInnerHTML={{ __html: page.content }} />
              ) : (
                <>
                  <p className="text-lg text-warm-gray leading-relaxed mb-6 max-w-lg">
                    Royal Shine brings together fine jewellery for celebrations, thoughtful gifts, and everyday style.
                    Our catalogue makes it easy to explore each piece and ask our team for details.
                  </p>
                  <p className="text-lg text-warm-gray leading-relaxed mb-6 max-w-lg">
                    Discover a range of designs shaped by timeless forms and contemporary details. Each product page
                    shares the available materials and specifications so you can make an informed enquiry.
                  </p>
                </>
              )}
              <Link href="/contact-us" className="btn btn-gold inline-block">
                Get in Touch
              </Link>
            </div>
          </div>

          <div className="mt-18">
            <h2 className="text-3xl md:text-4xl font-serif text-charcoal text-center mb-12 leading-tight">
              Our Commitment
            </h2>
            <div className="grid md:grid-cols-3 gap-8">
              <div className="text-center p-8 border border-beige rounded-xl card">
                <div className="w-12 h-12 bg-gold/10 text-gold rounded-full flex items-center justify-center mx-auto mb-5">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <h4 className="font-serif font-bold text-charcoal mb-2">Quality Assured</h4>
                <p className="text-warm-gray text-sm leading-relaxed">
                  Every piece undergoes strict quality checks before reaching you.
                </p>
              </div>
              <div className="text-center p-8 border border-beige rounded-xl card">
                <div className="w-12 h-12 bg-gold/10 text-gold rounded-full flex items-center justify-center mx-auto mb-5">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                </div>
                <h4 className="font-serif font-bold text-charcoal mb-2">Certified Purity</h4>
                <p className="text-warm-gray text-sm leading-relaxed">
                  Product specifications are listed individually and can be confirmed with our team before you enquire.
                </p>
              </div>
              <div className="text-center p-8 border border-beige rounded-xl card">
                <div className="w-12 h-12 bg-gold/10 text-gold rounded-full flex items-center justify-center mx-auto mb-5">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                </div>
                <h4 className="font-serif font-bold text-charcoal mb-2">Customer First</h4>
                <p className="text-warm-gray text-sm leading-relaxed">
                  Contact details appear here once configured by the Royal Shine team.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </main>
  );
}
