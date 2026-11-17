import Link from 'next/link';
import Image from 'next/image';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';

export const metadata = {
  title: 'Page Not Found | Royal Shine',
  description: 'The page you are looking for does not exist. Browse our jewellery collections or contact us.',
};

export default function NotFound() {
  return (
    <main className="min-h-screen flex flex-col">
      <Header />
      <section className="flex-1 bg-ivory py-20 md:py-32">
        <div className="container-luxury text-center">
          <div className="w-20 h-20 rounded-full bg-champagne flex items-center justify-center mx-auto mb-8 text-gold text-4xl">
            404
          </div>
          <h1 className="font-serif text-4xl md:text-6xl text-charcoal mb-4">Page Not Found</h1>
          <p className="text-warm-gray text-lg mb-10 max-w-md mx-auto leading-relaxed">
            The page you&rsquo;re looking for doesn&rsquo;t exist. It may have been moved or removed.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link href="/" className="btn btn-gold btn-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <path d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2" />
              </svg>
              Go to Homepage
            </Link>
            <Link href="/jewellery" className="btn btn-outline btn-lg">
              Browse Jewellery
            </Link>
          </div>
        </div>
      </section>
      <Footer />
    </main>
  );
}
