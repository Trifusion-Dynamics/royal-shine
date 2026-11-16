import type { Metadata } from 'next';
import './globals.css';

export const metadata: Metadata = {
  title: { default: 'Royal Shine | Fine Jewellery', template: '%s | Royal Shine' },
  description: 'Explore thoughtfully curated gold, diamond and occasion jewellery at Royal Shine. Discover our collections and enquire with our team.',
  metadataBase: new URL(process.env.NEXT_PUBLIC_SITE_URL || 'https://royal-shine.vercel.app'),
  openGraph: { title: 'Royal Shine | Fine Jewellery', description: 'Discover timeless jewellery and make every occasion memorable.', type: 'website' },
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return <html lang="en"><body>{children}</body></html>;
}
