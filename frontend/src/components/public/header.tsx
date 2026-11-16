'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { cn } from '@/lib/utils';

const navItems = [
  { name: 'Home', href: '/' },
  { name: 'Jewellery', href: '/jewellery' },
  { name: 'Collections', href: '/collections' },
  { name: 'Gallery', href: '/gallery' },
  { name: 'Our Story', href: '/about-us' },
  { name: 'Contact', href: '/contact-us' },
];

export default function Header() {
  const pathname = usePathname();

  return (
    <header className="sticky top-0 z-50 border-b border-dark-100 bg-white/95 backdrop-blur-md">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div className="flex h-16 items-center justify-between">
          <Link href="/" className="flex items-center gap-2">
            <span className="font-serif text-2xl font-bold text-dark-900 tracking-tighter">ROYAL</span>
            <span className="font-serif text-2xl font-bold text-gold-600 tracking-tighter">SHINE</span>
          </Link>
          <nav className="hidden md:flex items-center gap-8">
            {navItems.map((item) => (
              <Link
                key={item.name}
                href={item.href}
                className={cn(
                  'text-sm font-medium transition-colors',
                  pathname === item.href ? 'text-gold-600' : 'text-dark-600 hover:text-gold-600'
                )}
              >
                {item.name}
              </Link>
            ))}
          </nav>
          <div className="flex items-center gap-4">
            <Link href="/contact-us" className="hidden sm:flex items-center gap-2 px-5 py-2.5 border border-gold-600 text-gold-600 text-sm font-medium rounded-lg hover:bg-gold-50 transition-colors">
              Enquire ↗
            </Link>
            <button className="md:hidden text-dark-600 hover:text-gold-600">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="18" x2="21" y2="18" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </header>
  );
}
