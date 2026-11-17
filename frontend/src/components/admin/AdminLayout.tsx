'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import { useRouter, usePathname } from 'next/navigation';
import { LayoutDashboardIcon, UsersIcon, PackageIcon, ShoppingBagIcon, MailIcon, CogIcon, LogOutIcon } from 'lucide-react';

const navItems = [
  { name: 'Dashboard', href: '/admin', icon: LayoutDashboardIcon },
  { name: 'Products', href: '/admin/products', icon: PackageIcon },
  { name: 'Categories', href: '/admin/categories', icon: ShoppingBagIcon },
  { name: 'Collections', href: '/admin/collections', icon: PackageIcon },
  { name: 'Enquiries', href: '/admin/enquiries', icon: MailIcon },
  { name: 'Users', href: '/admin/users', icon: UsersIcon },
  { name: 'Settings', href: '/admin/settings', icon: CogIcon },
];

export default function AdminLayout({ children }: { children: React.ReactNode }) {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const router = useRouter();
  const pathname = usePathname();

  useEffect(() => {
    if (!localStorage.getItem('royal_shine_token')) {
      router.replace('/admin/login');
    }
  }, [router]);

  const handleLogout = () => {
    localStorage.removeItem('royal_shine_token');
    localStorage.removeItem('royal_shine_user');
    document.cookie = 'royal_shine_token=; Path=/; Max-Age=0';
    router.replace('/admin/login');
  };

  const isActive = (href: string) => pathname === href || pathname.startsWith(href + '/');

  return (
    <div className="min-h-screen bg-ivory text-charcoal">
      <nav className="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-beige transform transition-transform duration-300 ease-in-out lg:translate-x-0 ${sidebarOpen ? 'translate-x-0' : '-translate-x-full'} lg:static lg:inset-0 lg:z-auto lg:w-auto lg:border-0 lg:shadow-none">
        <div className="flex items-center justify-between h-20 px-6 border-b border-beige">
          <Link href="/" className="flex items-center gap-2">
            <span className="font-serif text-xl text-gold tracking-tight">ROYAL SHINE</span>
          </Link>
          <button
            onClick={() => setSidebarOpen(false)}
            className="lg:hidden p-2 text-charcoal hover:text-gold"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <line x1="6" y1="18" x2="18" y2="6" />
              <line x1="6" y1="6" x2="12" y2="12" />
            </svg>
          </button>
        </div>
        <div className="flex-1 overflow-y-auto p-4 space-y-2">
          {navItems.map((item) => {
            const Icon = item.icon;
            return (
              <Link
                key={item.name}
                href={item.href}
                onClick={() => setSidebarOpen(false)}
                className={cn(
                  'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors',
                  isActive(item.href)
                    ? 'bg-gold text-white shadow-md'
                    : 'text-warm-gray hover:bg-champagne hover:text-gold'
                )}
              >
                <Icon className="w-5 h-5" />
                {item.name}
              </Link>
            );
          })}
          <div className="mt-8 pt-6 border-t border-beige">
            <button onClick={handleLogout} className="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
              <LogOutIcon className="w-5 h-5" />
              Sign out
            </button>
          </div>
        </div>
      </nav>

      <div className="lg:pl-64 pt-20">
        <header className="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-beige px-6 py-4">
          <div className="flex items-center justify-between">
            <div className="hidden lg:flex items-center gap-4 text-sm text-warm-gray">
              <span>Royal Shine Admin</span>
            </div>
            <div className="flex items-center gap-3">
              <button
                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                className="lg:hidden p-2 text-charcoal hover:text-gold"
                aria-label="Menu"
              >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <line x1="4" y1="6" x2="20" y2="6" />
                  <line x1="4" y1="12" x2="20" y2="12" />
                  <line x1="4" y1="18" x2="20" y2="18" />
                </svg>
              </button>
              <button onClick={handleLogout} className="text-xs font-medium text-charcoal hover:text-gold transition-colors whitespace-nowrap">
                Sign out
              </button>
            </div>
          </div>
        </header>

        <main className="container-luxury py-8">{children}</main>
      </div>

      {mobileMenuOpen && (
        <div className="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm lg:hidden" onClick={() => setMobileMenuOpen(false)} />
      )}
      {mobileMenuOpen && (
        <div className="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-beige transform transition-transform duration-300 lg:hidden">
          <div className="p-4 space-y-2">
            {navItems.map((item) => {
              const Icon = item.icon;
              return (
                <Link
                  key={item.name}
                  href={item.href}
                  onClick={() => setMobileMenuOpen(false)}
                  className={cn(
                    'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors',
                    isActive(item.href)
                      ? 'bg-gold text-white'
                      : 'text-warm-gray hover:bg-champagne hover:text-gold'
                  )}
                >
                  <Icon className="w-5 h-5" />
                  {item.name}
                </Link>
              );
            })}
            <div className="mt-6 pt-4 border-t border-beige">
              <button onClick={handleLogout} className="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                <LogOutIcon className="w-5 h-5" />
                Sign out
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

function cn(...classes: (string | boolean | undefined | null)[]) {
  return classes.filter(Boolean).join(' ');
}
