'use client';

import Link from 'next/link';
import Image from 'next/image';
import { useState } from 'react';
import { galleriesService } from '@/services/galleries.service';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import { SectionHeading } from '@/components/ui/section-heading';

async function getGalleries() {
  try {
    const res = await galleriesService.getAll(false);
    const data = res.data || res;
    return Array.isArray(data) ? data : data.data || [];
  } catch {
    return [];
  }
}

export default function GalleryClient() {
  const [galleries, setGalleries] = useState<any[]>([]);
  const [open, setOpen] = useState(false);
  const [currentIndex, setCurrentIndex] = useState(0);
  const [loading, setLoading] = useState(true);

  const imageUrls = galleries.map((g: any) => g.image);

  async function loadGalleries() {
    try {
      const data = await getGalleries();
      setGalleries(data);
    } finally {
      setLoading(false);
    }
  }

  // Use a timeout to simulate async data loading in client component
  // In production, this would be fetched server-side and passed as props
  if (typeof window !== 'undefined') {
    loadGalleries();
  }

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

  return (
    <main className="min-h-screen">
      <Header />
      <section className="py-16">
        <div className="container-luxury">
          <div className="text-center mb-14">
            <h1 className="text-5xl md:text-6xl font-serif text-charcoal mb-4 leading-tight">
              Our Gallery
            </h1>
            <div className="w-20 h-[1px] bg-gold mx-auto mb-6" />
            <p className="text-lg text-warm-gray max-w-2xl mx-auto leading-relaxed">
              Moments captured in gold, light and elegance.
            </p>
          </div>

          {galleries.length > 0 ? (
            <div
              className="relative"
              onKeyDown={(e) => {
                if (e.key === 'ArrowRight' && open) setCurrentIndex((i) => (i + 1) % galleries.length);
                if (e.key === 'ArrowLeft' && open) setCurrentIndex((i) => (i + galleries.length - 1) % galleries.length);
                if (e.key === 'Escape') setOpen(false);
              }}
            >
              <div className="masonry-grid grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                {galleries.map((item: any, index: number) => (
                  <button
                    key={item.id}
                    type="button"
                    onClick={() => { setCurrentIndex(index); setOpen(true); }}
                    className="group relative aspect-[4/5] overflow-hidden rounded-lg border border-beige bg-white"
                    aria-label={`View ${item.title || 'Gallery image'}`}
                  >
                    <Image
                      src={item.image}
                      alt={item.altText || item.title || 'Jewellery gallery image'}
                      fill
                      sizes="(max-width: 768px) 100vw, 25vw"
                      className="object-cover object-center group-hover:scale-105 transition-transform duration-700"
                      priority={index < 4}
                    />
                    <div className="absolute inset-x-0 bottom-0 p-5 pt-14 bg-gradient-to-t from-[#25221f]/85 to-transparent">
                      <span className="text-sm font-medium text-white">{item.title}</span>
                      {item.description && (
                        <p className="text-xs text-white/70 mt-1 line-clamp-1">{item.description}</p>
                      )}
                    </div>
                  </button>
                ))}
              </div>

              {open && (
                <div
                  className="fixed inset-0 z-50 flex items-center justify-center bg-[#25221f]/95 p-4 lg:p-6"
                  role="dialog"
                  aria-modal="true"
                  aria-label="Gallery image"
                  onClick={(e) => { if (e.target === e.currentTarget) setOpen(false); }}
                >
                  <button type="button" onClick={() => setOpen(false)} className="absolute right-4 top-4 z-10 text-3xl text-white" aria-label="Close gallery">
                    &times;
                  </button>
                  {galleries.length > 1 && (
                    <>
                      <button
                        type="button"
                        onClick={() => setCurrentIndex((i) => (i + galleries.length - 1) % galleries.length)}
                        className="absolute left-4 top-1/2 z-10 -translate-y-1/2 text-4xl text-white hover:text-gold"
                        aria-label="Previous image"
                      >
                        &lsaquo;
                      </button>
                      <button
                        type="button"
                        onClick={() => setCurrentIndex((i) => (i + 1) % galleries.length)}
                        className="absolute right-4 top-1/2 z-10 -translate-y-1/2 text-4xl text-white hover:text-gold"
                        aria-label="Next image"
                      >
                        &rsaquo;
                      </button>
                    </>
                  )}
                  <div className="relative flex max-h-[90vh] max-w-4xl items-center justify-center">
                    {galleries.map((item: any, index: number) => (
                      <img
                        key={item.id}
                        src={item.image}
                        style={{ display: open && currentIndex === index ? 'block' : 'none' }}
                        alt={item.altText || item.title || 'Gallery'}
                        className="max-h-[85vh] max-w-full object-contain shadow-2xl"
                        loading="eager"
                      />
                    ))}
                  </div>
                  <div className="absolute bottom-4 left-1/2 -translate-x-1/2 z-10 rounded-full bg-[#25221f]/70 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm">
                    {currentIndex + 1} / {galleries.length}
                  </div>
                </div>
              )}
            </div>
          ) : (
            <div className="text-center py-20">
              <div className="text-gold text-6xl mb-6">📸</div>
              <h2 className="text-3xl font-serif text-charcoal mb-4">No Gallery Images</h2>
              <p className="text-warm-gray max-w-md mx-auto leading-relaxed">
                Gallery images will appear here once added from the admin panel.
              </p>
            </div>
          )}
        </div>
      </section>

      <Footer />
    </main>
  );
}
