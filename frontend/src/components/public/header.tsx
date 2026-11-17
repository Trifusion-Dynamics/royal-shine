'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { settingsService } from '@/services/settings.service';
import { categoriesService } from '@/services/categories.service';
import MiniCart from '@/components/ui/mini-cart';
import { useCart } from '@/contexts/CartContext';

export default function Header() {
  const pathname = usePathname();
  const [scrolled, setScrolled] = useState(false);
  const [open, setOpen] = useState(false);
  const [settings, setSettings] = useState<Record<string, string>>({});
  const [categories, setCategories] = useState<any[]>([]);
  const { itemCount } = useCart();

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 20);
    window.addEventListener('scroll', onScroll);
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  useEffect(() => {
    let mounted = true;
    (async () => {
      try {
        const [sRes, cRes] = await Promise.all([
          settingsService.getAll(),
          categoriesService.getAll(false),
        ]);
        if (!mounted) return;
        const sData = sRes.data || sRes;
        const sMap: Record<string, string> =
          typeof sData === 'object' && !Array.isArray(sData) ? sData : {};
        setSettings(sMap);

        const cData = cRes.data || cRes;
        const cList: any[] = Array.isArray(cData) ? cData : cData.data || [];
        setCategories(cList.filter((c: any) => c.status === 'active'));
      } catch {}
    })();
    return () => { mounted = false; };
  }, []);

  const whatsapp = settings.whatsapp_number;
  const announcementEnabled = settings.announcement_enabled === 'true';
  const announcementText = settings.announcement_text;
  const announcementLink = settings.announcement_link;

  const primaryCategories = categories.slice(0, 6);
  const secondaryCategories = categories.slice(6, 12);

  const navItems = [
    { name: 'Home', href: '/' },
    { name: 'About Us', href: '/about-us' },
    { name: 'Jewellery', href: '/jewellery', hasMega: true },
    { name: 'Collections', href: '/collections' },
    { name: 'Gallery', href: '/gallery' },
    { name: 'Contact Us', href: '/contact-us' },
  ];

  const waLink = whatsapp
    ? `https://wa.me/${whatsapp}?text=${encodeURIComponent('Hi Royal Shine, I would like to enquire about your jewellery collection.')}`
    : '#';

  return (
    <>
      {announcementEnabled && announcementText && (
        <div className="bg-champagne text-charcoal border-b border-beige px-4 py-2">
          <div className="container-luxury flex items-center gap-3 text-sm font-medium">
            {announcementLink ? (
              <a href={announcementLink} className="hover:text-gold transition-colors">
                {announcementText}
              </a>
            ) : (
              announcementText
            )}
          </div>
        </div>
      )}

      <header
        className={`sticky top-0 z-[60] bg-white/80 backdrop-blur-md border-b border-beige transition-all duration-300 ${scrolled ? 'shadow-sm' : ''}`}
      >
        <div className="container-luxury">
          <div className="flex justify-between items-center h-18 md:h-20 px-4 sm:px-6 lg:px-8">
            <div className="flex-shrink-0 flex items-center lg:mr-8">
              <Link href="/" className="flex items-center gap-2">
                <span className="font-serif text-[1.5rem] text-gold tracking-tight">ROYAL SHINE</span>
              </Link>
            </div>

            <nav className="hidden lg:flex items-center space-x-1">
              {navItems.map((item) => {
                if (item.hasMega) {
                  return (
                    <div key={item.name} className="relative group">
                      <Link
                        href={item.href}
                        className="text-sm font-medium text-charcoal hover:text-gold transition-colors px-3 py-2"
                      >
                        {item.name}
                      </Link>
                      <div className="absolute top-full left-0 mt-2 w-[640px] rounded-lg border border-beige bg-white shadow-luxury-lg overflow-hidden hidden group-hover:block z-50">
                        <div className="grid grid-cols-2 gap-0">
                          <div className="border-r border-beige p-6">
                            <h4 className="text-xs font-semibold text-gold uppercase tracking-wider mb-5">
                              Categories
                            </h4>
                            <nav className="space-y-3">
                              {primaryCategories.map((cat) => (
                                <Link
                                  key={cat.id}
                                  href={`/jewellery?category=${cat.slug}`}
                                  className="flex items-center gap-3 px-3 py-2.5 text-sm text-warm-gray hover:bg-champagne hover:text-gold transition-colors rounded-md"
                                >
                                  <div className="w-6 h-6 shrink-0 overflow-hidden rounded-sm border border-beige flex-shrink-0">
                                    {cat.image ? (
                                      <img src={cat.image} alt={cat.name} className="w-full h-full object-cover" />
                                    ) : (
                                      <div className="w-6 h-6 shrink-0 bg-beige rounded-sm flex items-center justify-center">
                                        <span className="text-gold text-[10px] font-bold uppercase">
                                          {cat.name.charAt(0).toUpperCase()}
                                        </span>
                                      </div>
                                    )}
                                  </div>
                                  {cat.name}
                                </Link>
                              ))}
                            </nav>
                          </div>
                          <div className="p-6">
                            <h4 className="text-xs font-semibold text-gold uppercase tracking-wider mb-5">
                              Shop by
                            </h4>
                            <nav className="space-y-3">
                              {secondaryCategories.map((cat) => (
                                <Link
                                  key={cat.id}
                                  href={`/jewellery?category=${cat.slug}`}
                                  className="flex items-center gap-3 px-3 py-2.5 text-sm text-warm-gray hover:bg-champagne hover:text-gold transition-colors rounded-md"
                                >
                                  <div className="w-6 h-6 shrink-0 overflow-hidden rounded-sm border border-beige flex-shrink-0">
                                    {cat.image ? (
                                      <img src={cat.image} alt={cat.name} className="w-full h-full object-cover" />
                                    ) : (
                                      <div className="w-6 h-6 shrink-0 bg-beige rounded-sm flex items-center justify-center">
                                        <span className="text-gold text-[10px] font-bold uppercase">
                                          {cat.name.charAt(0).toUpperCase()}
                                        </span>
                                      </div>
                                    )}
                                  </div>
                                  {cat.name}
                                </Link>
                              ))}
                              <div className="mt-6 pt-6 border-t border-beige flex gap-3">
                                <Link
                                  href="/jewellery"
                                  className="flex-1 px-4 py-3 bg-brown text-white text-sm font-medium text-center hover:bg-gold transition-colors rounded-md"
                                >
                                  View All Jewellery
                                </Link>
                                {whatsapp && (
                                  <a
                                    href={waLink}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="flex-1 px-4 py-3 border border-beige text-brown text-sm font-medium text-center hover:bg-gold hover:text-white transition-colors rounded-md"
                                  >
                                    WhatsApp Enquiry
                                  </a>
                                )}
                              </div>
                            </nav>
                          </div>
                        </div>
                      </div>
                    </div>
                  );
                }
                return (
                  <Link
                    key={item.name}
                    href={item.href}
                    className="text-sm font-medium text-charcoal hover:text-gold transition-colors px-3 py-2"
                  >
                    {item.name}
                  </Link>
                );
              })}
            </nav>

            <div className="hidden lg:flex items-center gap-4">
              <form
                action="/jewellery"
                method="GET"
                role="search"
                className="flex items-center gap-2 border border-beige rounded-md bg-white px-3 py-1.5 focus-within:border-gold transition-colors"
              >
                <input
                  type="search"
                  name="search"
                  placeholder="Search jewellery"
                  className="w-40 text-sm outline-none placeholder-warm-gray/60"
                />
                <button type="submit" className="text-warm-gray hover:text-gold transition-colors">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  >
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                  </svg>
                </button>
              </form>

              <MiniCart />

              {whatsapp && (
                <a
                  href={waLink}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex items-center gap-2 text-sm font-medium text-brown hover:text-gold transition-colors whitespace-nowrap"
                  title="WhatsApp"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  >
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                  WhatsApp
                </a>
              )}

              <Link href="/contact-us" className="btn btn-gold btn-sm text-brown whitespace-nowrap">
                Enquire Now
              </Link>
            </div>

            <div className="lg:hidden flex items-center gap-2">
              {whatsapp && (
                <a href={waLink} target="_blank" rel="noopener noreferrer" className="text-charcoal hover:text-gold p-2" title="WhatsApp">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  >
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </a>
              )}
              <a href="/jewellery?search=" className="text-charcoal hover:text-gold p-2" aria-label="Search products">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="2"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                >
                  <circle cx="11" cy="11" r="8" />
                  <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
              </a>
              <button
                onClick={() => setOpen(!open)}
                className="text-charcoal hover:text-gold"
                aria-label="Toggle navigation"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="2"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                >
                  <line x1="4" y1="6" x2="20" y2="6" />
                  <line x1="4" y1="12" x2="20" y2="12" />
                  <line x1="4" y1="18" x2="20" y2="18" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <div
          className={`fixed inset-0 z-[70] bg-white/95 backdrop-blur-sm lg:hidden ${
            open
              ? 'opacity-100 translate-y-0 pointer-events-auto'
              : 'opacity-0 -translate-y-2 pointer-events-none transition-all duration-300'
          }`}
        >
          <div className="flex flex-col h-full">
            <div className="container-luxury flex justify-between items-center h-20 border-b border-beige">
              <Link href="/" onClick={() => setOpen(false)} className="flex items-center gap-2">
                <span className="font-serif text-[1.5rem] text-gold tracking-tight">ROYAL SHINE</span>
              </Link>
              <button onClick={() => setOpen(false)} className="text-charcoal hover:text-gold">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="2"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                >
                  <line x1="6" y1="18" x2="18" y2="6" />
                  <line x1="6" y1="6" x2="12" y2="12" />
                </svg>
              </button>
            </div>

            <div className="flex-1 overflow-y-auto px-6 py-8 space-y-6">
              <form
                action="/jewellery"
                method="GET"
                role="search"
                className="flex items-center gap-2 border border-beige rounded-md bg-ivory px-3 py-2"
              >
                <input
                  type="search"
                  name="search"
                  placeholder="Search jewellery"
                  className="flex-1 text-sm outline-none placeholder-warm-gray/60"
                />
                <button type="submit" className="text-warm-gray hover:text-gold transition-colors">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  >
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                  </svg>
                </button>
              </form>

              <nav className="space-y-2">
                <Link
                  href="/"
                  onClick={() => setOpen(false)}
                  className="block px-3 py-3 text-lg font-medium text-charcoal hover:bg-champagne hover:text-gold rounded-md transition-colors"
                >
                  Home
                </Link>
                <Link
                  href="/about-us"
                  onClick={() => setOpen(false)}
                  className="block px-3 py-3 text-lg font-medium text-charcoal hover:bg-champagne hover:text-gold rounded-md transition-colors"
                >
                  About Us
                </Link>
                <div className="border-t border-beige pt-4">
                  <h4 className="px-3 py-3 text-xs font-semibold text-gold uppercase tracking-wider">
                    Jewellery
                  </h4>
                  <nav className="space-y-1">
                    {categories.map((cat) => (
                      <Link
                        key={cat.id}
                        href={`/jewellery?category=${cat.slug}`}
                        onClick={() => setOpen(false)}
                        className="flex items-center gap-3 px-3 py-2.5 text-sm text-warm-gray hover:bg-champagne hover:text-gold transition-colors rounded-md"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="16"
                          height="16"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          strokeWidth="2"
                          strokeLinecap="round"
                          strokeLinejoin="round"
                        >
                          <path d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2" />
                        </svg>
                        {cat.name}
                      </Link>
                    ))}
                  </nav>
                </div>
                <Link
                  href="/collections"
                  onClick={() => setOpen(false)}
                  className="block px-3 py-3 text-lg font-medium text-charcoal hover:bg-champagne hover:text-gold rounded-md transition-colors"
                >
                  Collections
                </Link>
                <Link
                  href="/gallery"
                  onClick={() => setOpen(false)}
                  className="block px-3 py-3 text-lg font-medium text-charcoal hover:bg-champagne hover:text-gold rounded-md transition-colors"
                >
                  Gallery
                </Link>
                <Link
                  href="/contact-us"
                  onClick={() => setOpen(false)}
                  className="block px-3 py-3 text-lg font-medium text-charcoal hover:bg-champagne hover:text-gold rounded-md transition-colors"
                >
                  Contact Us
                </Link>
              </nav>

              <div className="border-t border-beige pt-6">
                <h4 className="px-3 py-3 text-xs font-semibold text-charcoal uppercase tracking-wider">
                  Quick Actions
                </h4>
                <div className="space-y-2">
                  {whatsapp && (
                    <a
                      href={waLink}
                      target="_blank"
                      rel="noopener noreferrer"
                      onClick={() => setOpen(false)}
                      className="block px-4 py-4 rounded-md text-base font-medium text-brown bg-champagne border border-beige hover:bg-gold hover:text-white transition-colors flex items-center gap-3"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeWidth="2"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                      >
                        <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                      </svg>
                      WhatsApp Us
                    </a>
                  )}
                  <Link
                    href="/contact-us"
                    onClick={() => setOpen(false)}
                    className="block px-4 py-4 rounded-md text-base font-medium text-charcoal bg-ivory border border-beige hover:bg-gold hover:text-white transition-colors flex items-center gap-3"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="20"
                      height="20"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      strokeWidth="2"
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    >
                      <path d="M3 8l7.89 5.26a2 2 0 002.22-2.22L6 18m-3 3v1h12V3z" />
                    </svg>
                    Enquire Now
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>
    </>
  );
}
