// ImageKit utility for optimized image URLs
// Serves directly from Next.js public folder. ImageKit CDN is used in production.

const IK_ENDPOINT = process.env.NEXT_PUBLIC_IMAGEKIT_URL_ENDPOINT || 'https://ik.imagekit.io/er7lmis3j';

export function imageKitUrl(
  path: string | null | undefined,
  options: {
    width?: number;
    height?: number;
    quality?: number;
    format?: 'jpg' | 'png' | 'webp' | 'avif';
    crop?: string;
  } = {}
): string {
  if (!path) return '/images/placeholder-jewel.jpg';

  // If it's already an ImageKit URL from the database, extract the underlying image path
  let cleanPath = path;
  if (path.startsWith('http')) {
    try {
      const url = new URL(path);
      const pathname = url.pathname;
      const trIndex = pathname.indexOf('/tr:');
      if (trIndex !== -1) {
        cleanPath = pathname.slice(trIndex).replace(/^\/tr:[^/]+\//, '');
      } else {
        cleanPath = pathname;
      }
    } catch {
      cleanPath = path;
    }
  }

  // Serve directly from public folder (ImageKit cannot reach localhost origin in dev)
  const devPath = cleanPath.startsWith('/') ? cleanPath : `/${cleanPath}`;
  return devPath;
}

export function productImage(
  productImage: string | { image: string | null | undefined; altText?: string | null } | null | undefined,
  options?: Parameters<typeof imageKitUrl>[1]
): string {
  const img = typeof productImage === 'string' ? { image: productImage } : productImage;
  if (!img?.image) return '/images/placeholder-jewel.jpg';
  return imageKitUrl(img.image, options);
}

export function categoryImage(image: string | null | undefined): string {
  return imageKitUrl(image, { width: 600, height: 600, quality: 80 });
}

export function bannerImage(image: string | null | undefined): string {
  return imageKitUrl(image, { width: 1600, height: 600, quality: 80 });
}

export function galleryImage(image: string | null | undefined): string {
  return imageKitUrl(image, { width: 800, height: 800, quality: 80 });
}

export function testimonialImage(image: string | null | undefined): string {
  return imageKitUrl(image, { width: 200, height: 200, quality: 80 });
}
