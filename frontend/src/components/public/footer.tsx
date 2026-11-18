'use client';

import Link from 'next/link';
import Image from 'next/image';
import { settingsService } from '@/services/settings.service';
import { categoriesService } from '@/services/categories.service';
import { useEffect, useState } from 'react';

export default function Footer() {
  const [settings, setSettings] = useState<Record<string, string>>({});
  const [categories, setCategories] = useState<any[]>([]);

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

  const phone = settings.phone;
  const whatsapp = settings.whatsapp_number;
  const email = settings.email;
  const address = settings.address;
  const businessHours = settings.business_hours;
  const instagram = settings.instagram_url;
  const facebook = settings.facebook_url;
  const twitter = settings.twitter_url;

  const socialLinks: Array<{ label: string; url: string | undefined; icon: React.ReactNode }> = [
    {
      label: 'Instagram',
      url: instagram,
      icon: (
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <rect width="20" height="20" x="2" y="2" rx="5" ry="5" /><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" /><line x1="17.5" y1="17.5" x2="17.51" y2="17.5" />
        </svg>
      ),
    },
    {
      label: 'Facebook',
      url: facebook,
      icon: (
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
        </svg>
      ),
    },
    {
      label: 'Twitter',
      url: twitter,
      icon: (
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z" />
        </svg>
      ),
    },
    {
      label: 'WhatsApp',
      url: whatsapp ? `https://wa.me/${whatsapp}` : undefined,
      icon: (
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
      ),
    },
  ];

  return (
    <footer className="border-t border-beige bg-charcoal text-white mt-auto">
      <div className="container-luxury py-12 md:py-18 px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 lg:grid-cols-4 gap-12 mb-14">
          <div className="lg:col-span-2">
            <div className="flex items-center gap-2.5 mb-5">
              <Image
                src="/images/Royal-Shine-Logo.png"
                alt="Royal Shine Logo"
                width={32}
                height={32}
                className="h-auto w-8 h-8 object-contain"
                priority
              />
              <span className="font-serif text-xl text-gold tracking-tight">ROYAL SHINE</span>
            </div>
            <p className="text-warm-gray/70 text-sm leading-relaxed max-w-xs mb-6">
              {settings.footer_text || 'Fine jewellery crafted with passion and worn with pride. Timeless designs for every beautiful moment.'}
            </p>
            <div className="flex gap-5">
              {socialLinks
                .filter((s) => s.url)
                .map((s) => (
                  <a
                    key={s.label}
                    href={s.url!}
                    className="text-warm-gray/60 hover:text-gold transition-colors"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label={s.label}
                  >
                    {s.icon}
                  </a>
                ))}
            </div>
          </div>

          <div>
            <h4 className="text-xs font-semibold text-gold uppercase tracking-wider mb-6">
              Quick Links
            </h4>
            <ul className="space-y-3.5 text-sm text-warm-gray/70">
              <li><Link href="/" className="hover:text-gold transition-colors">Home</Link></li>
              <li><Link href="/about-us" className="hover:text-gold transition-colors">About Us</Link></li>
              <li><Link href="/jewellery" className="hover:text-gold transition-colors">Jewellery</Link></li>
              <li><Link href="/collections" className="hover:text-gold transition-colors">Collections</Link></li>
              <li><Link href="/gallery" className="hover:text-gold transition-colors">Gallery</Link></li>
              <li><Link href="/contact-us" className="hover:text-gold transition-colors">Contact Us</Link></li>
            </ul>
          </div>

          <div>
            <h4 className="text-xs font-semibold text-gold uppercase tracking-wider mb-6">
              Jewellery
            </h4>
            <ul className="space-y-3.5 text-sm text-warm-gray/70">
              {categories.map((cat) => (
                <li key={cat.id}>
                  <Link href={`/jewellery?category=${cat.slug}`} className="hover:text-gold transition-colors">
                    {cat.name}
                  </Link>
                </li>
              ))}
              <li><Link href="/jewellery" className="text-gold hover:underline">View All Categories</Link></li>
            </ul>
          </div>

          <div>
            <h4 className="text-xs font-semibold text-gold uppercase tracking-wider mb-6">
              Customer Support
            </h4>
            <ul className="space-y-4 text-sm text-warm-gray/70">
              {businessHours && (
                <li className="flex items-start gap-3">
                  <span className="w-5 h-5 shrink-0 text-gold mt-0.5 flex items-center justify-center rounded-full bg-gold/10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                  </span>
                  <div>
                    <p className="text-xs font-medium text-charcoal/60 uppercase tracking-wider">Business Hours</p>
                    <p className="text-sm text-white">{businessHours}</p>
                  </div>
                </li>
              )}
              {phone && (
                <li className="flex items-start gap-3">
                  <span className="w-5 h-5 shrink-0 text-gold mt-0.5 flex items-center justify-center rounded-full bg-gold/10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M3 5a2 2 0 0 1 2-2h3.28a1 1 0 0 1 1.515.736l.67 3.277a2 2 0 0 1-.446 1.802l-.545 1.27a.5.5 0 0 0 .133.33c.336.155.55.04.735-.156l1.186-.593c.389-.194.593-.604.593-1.02V5.802a2 2 0 0 0-.593-1.02l-.546-1.27A2 2 0 0 1 8.28 2H5a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-1.172c0-.416-.204-.828-.593-1.02l-.545-1.27a2 2 0 0 1-.446-1.802l.67-3.277a1 1 0 0 1 1.515-.736H19a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2z" />
                    </svg>
                  </span>
                  <div>
                    <p className="text-xs font-medium text-charcoal/60 uppercase tracking-wider">Phone</p>
                    <a href={`tel:${phone}`} className="text-sm text-white hover:text-gold transition-colors">
                      {phone}
                    </a>
                  </div>
                </li>
              )}
              {whatsapp && (
                <li className="flex items-start gap-3">
                  <span className="w-5 h-5 shrink-0 text-gold mt-0.5 flex items-center justify-center rounded-full bg-gold/10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                  </span>
                  <div>
                    <p className="text-xs font-medium text-charcoal/60 uppercase tracking-wider">WhatsApp</p>
                    <a
                      href={`https://wa.me/${whatsapp}?text=${encodeURIComponent('Hi Royal Shine, I would like to enquire about your jewellery collection.')}`}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-sm text-white hover:text-gold transition-colors"
                    >
                      {whatsapp}
                    </a>
                  </div>
                </li>
              )}
              {email && (
                <li className="flex items-start gap-3">
                  <span className="w-5 h-5 shrink-0 text-gold mt-0.5 flex items-center justify-center rounded-full bg-gold/10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" /><polyline points="22,6 12,13 2,6" />
                    </svg>
                  </span>
                  <div>
                    <p className="text-xs font-medium text-charcoal/60 uppercase tracking-wider">Email</p>
                    <a href={`mailto:${email}`} className="text-sm text-white hover:text-gold transition-colors">
                      {email}
                    </a>
                  </div>
                </li>
              )}
              {address && (
                <li className="flex items-start gap-3">
                  <span className="w-5 h-5 shrink-0 text-gold mt-0.5 flex items-center justify-center rounded-full bg-gold/10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <polygon points="3 11 22 18 20 12 18 1 10 13 2 11 3 11" />
                    </svg>
                  </span>
                  <div>
                    <p className="text-xs font-medium text-charcoal/60 uppercase tracking-wider">Address</p>
                    <p className="text-sm text-white">{address}</p>
                  </div>
                </li>
              )}
            </ul>
          </div>
        </div>

        <div className="border-t border-[rgba(233,225,214,0.25)] pt-10 flex flex-col lg:flex-row items-center justify-between gap-6 text-xs text-warm-gray/60">
          <p>&copy; {new Date().getFullYear()} Royal Shine. All rights reserved.</p>
          <div className="flex gap-8">
            <Link href="/privacy-policy" className="hover:text-gold transition-colors">
              Privacy Policy
            </Link>
            <Link href="/terms-and-conditions" className="hover:text-gold transition-colors">
              Terms &amp; Conditions
            </Link>
          </div>
        </div>
      </div>
    </footer>
  );
}
