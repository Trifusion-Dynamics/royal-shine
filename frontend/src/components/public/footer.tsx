'use client';

import Link from 'next/link';

const footerLinks = {
  Shop: ['Jewellery', 'Collections', 'Gallery'],
  Support: ['Contact Us', 'Privacy Policy', 'Terms & Conditions'],
  About: ['Our Story', 'Careers', 'Press'],
};

export default function Footer() {
  return (
    <footer className="border-t border-dark-100 bg-dark-900 text-dark-200">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 mb-12">
          <div className="col-span-2 md:col-span-1">
            <Link href="/" className="flex items-center gap-2 mb-4">
              <span className="font-serif text-xl font-bold text-white tracking-tighter">ROYAL</span>
              <span className="font-serif text-xl font-bold text-gold-400 tracking-tighter">SHINE</span>
            </Link>
            <p className="text-sm text-dark-400 max-w-xs">
              Exquisite jewellery crafted with passion. Hallmarked gold, certified diamonds, and handcrafted silver for every moment that matters.
            </p>
            <div className="flex gap-4 mt-6">
              <a href="#" className="w-8 h-8 rounded-lg bg-dark-800 flex items-center justify-center hover:bg-gold-600 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="17.5" x2="17.51" y2="17.5"/></svg>
              </a>
              <a href="#" className="w-8 h-8 rounded-lg bg-dark-800 flex items-center justify-center hover:bg-gold-600 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
              </a>
              <a href="#" className="w-8 h-8 rounded-lg bg-dark-800 flex items-center justify-center hover:bg-gold-600 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-11 8-11-8z"/><path d="M12 3v15M3 12h18"/></svg>
              </a>
            </div>
          </div>
          {Object.entries(footerLinks).map(([group, links]) => (
            <div key={group}>
              <h3 className="text-white font-semibold mb-6">{group}</h3>
              <ul className="space-y-3">
                {links.map((link) => (
                  <li key={link}>
                    <Link href={link.toLowerCase().replace(/\s+/g, '-')} className="text-sm text-dark-400 hover:text-gold-400 transition-colors">
                      {link}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>
        <div className="border-t border-dark-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
          <p className="text-sm text-dark-500">
            © {new Date().getFullYear()} Royal Shine Jewellers. All rights reserved.
          </p>
          <p className="text-sm text-dark-500">
            Hallmarked • Certified • Trusted
          </p>
        </div>
      </div>
    </footer>
  );
}
