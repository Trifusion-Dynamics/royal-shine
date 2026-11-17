import type { Metadata } from 'next';
import { CartProvider } from '@/contexts/CartContext';
import { Toaster } from 'react-hot-toast';
import './globals.css';

export const metadata: Metadata = {
  title: { default: 'Royal Shine | Fine Jewellery', template: '%s | Royal Shine' },
  description: 'Explore thoughtfully curated gold, diamond and occasion jewellery at Royal Shine. Discover our collections and enquire with our team.',
  metadataBase: new URL(process.env.NEXT_PUBLIC_SITE_URL || 'http://localhost:3000'),
  openGraph: {
    title: 'Royal Shine | Fine Jewellery',
    description: 'Discover timeless jewellery and make every occasion memorable.',
    type: 'website',
    url: '/',
    images: [{ url: '/images/placeholder-jewel.jpg', width: 1200, height: 630, alt: 'Royal Shine Jewellery' }],
  },
  twitter: {
    card: 'summary_large_image',
    title: 'Royal Shine | Fine Jewellery',
    description: 'Explore thoughtfully curated gold, diamond and occasion jewellery at Royal Shine.',
    images: ['/images/placeholder-jewel.jpg'],
  },
  icons: {
    icon: '/favicon.ico',
    apple: '/apple-touch-icon.png',
  },
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="en" className="scroll-smooth">
      <body className="overflow-x-hidden">
        <CartProvider>{children}</CartProvider>
        <Toaster position="bottom-right" />
      </body>
    </html>
  );
}
