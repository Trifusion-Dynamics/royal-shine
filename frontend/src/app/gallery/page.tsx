import Link from 'next/link';
import Image from 'next/image';
import { galleriesService } from '@/services/galleries.service';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';

type GalleryItem = {
  id: number;
  title?: string | null;
  description?: string | null;
  image: string;
  alt_text?: string | null;
};

async function getGalleries() {
  try {
    const res = await galleriesService.getAll(false);
    const data = res.data || res;
    return Array.isArray(data) ? data : data.data || [];
  } catch {
    return [];
  }
}

export const metadata = {
  title: 'Gallery | Royal Shine',
  description: 'Moments captured in gold - browse our jewellery gallery.',
};

export default async function GalleryPage() {
  const galleries = await getGalleries();

  return (
    <main className="min-h-screen">
      <Header />
      <section className="section-wrap">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="font-serif text-4xl text-dark-900 mb-8">Gallery</h2>
          <p className="text-dark-600 max-w-2xl mb-16">
            Real moments, real celebrations. See our jewellery adorning brides, festival-goers, and everyday elegance.
          </p>

          <div className="grid grid-cols-2 md:grid-cols-3 gap-6">
            {(galleries as GalleryItem[]).map((gallery) => (
              <div key={gallery.id} className="group relative overflow-hidden rounded-2xl bg-dark-50 aspect-[1]">
                <Image
                  src={gallery.image}
                  alt={gallery.alt_text || gallery.title || 'Gallery'}
                  fill
                  sizes="(max-width: 768px) 100vw, 25vw"
                  className="object-cover object-center transition-transform duration-500 group-hover:scale-105"
                  priority
                />
                <div className="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-all duration-300 flex flex-col items-end justify-end p-6">
                  <h3 className="text-white font-serif font-semibold text-lg mb-1">{gallery.title}</h3>
                  <p className="text-dark-100 text-sm">{gallery.description}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
      <Footer />
    </main>
  );
}
