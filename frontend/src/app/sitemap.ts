import type { MetadataRoute } from 'next';
export default function sitemap(): MetadataRoute.Sitemap {
  const base = process.env.NEXT_PUBLIC_SITE_URL || 'https://royal-shine.vercel.app';
  const paths = ['', '/about-us', '/jewellery', '/collections', '/gallery', '/contact-us', '/privacy-policy', '/terms-and-conditions'];
  return paths.map((path) => ({ url: `${base}${path}`, lastModified: new Date(), changeFrequency: path ? 'monthly' : 'weekly', priority: path ? 0.7 : 1 }));
}
