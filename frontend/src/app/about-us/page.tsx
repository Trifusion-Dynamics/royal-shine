import Link from 'next/link';
import { pagesService } from '@/services/pages.service';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';

type PageItem = {
  id: number;
  title: string;
  slug: string;
  content: string;
  seo_title?: string | null;
  seo_description?: string | null;
};

async function getPage(slug: string) {
  try {
    const res = await pagesService.getBySlug(slug);
    const data = res.data || res;
    return data;
  } catch {
    return null;
  }
}

export const metadata = {
  title: 'About Us | Royal Shine',
  description: 'Discover the story and heritage behind Royal Shine jewellery.',
};

export default async function AboutPage() {
  const page = await getPage('about-us');

  return (
    <main className="min-h-screen">
      <Header />
      <section className="section-wrap">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="font-serif text-4xl text-dark-900 mb-8">
            {page?.title || 'Our Story'}
          </h2>
          <div className="prose dark:prose-invert max-w-none text-dark-600 leading-relaxed">
            {page?.content ? (
              <div dangerouslySetInnerHTML={{ __html: page.content }} />
            ) : (
              <>
                <p>
                  Royal Shine is a premier jewellery boutique known for pure hallmarked gold, certified diamonds,
                  and handcrafted silver jewellery. Every piece is crafted with passion and uncompromising quality.
                </p>
                <p>
                  From everyday elegance to unforgettable celebrations, our designs are chosen to make every moment
                  shine. Visit us to experience the art of fine adornment.
                </p>
              </>
            )}
          </div>
        </div>
      </section>
      <Footer />
    </main>
  );
}
