// ImageKit utility for optimized image URLs
// Follows ImageKit URL pattern: {endpoint}/tr:{transformations}/{image-path}

const IK_ENDPOINT = process.env.NEXT_PUBLIC_IMAGEKIT_URL_ENDPOINT || 'https://ik.imagekit.io/royalshine';

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
  if (path.startsWith('http')) return path;

  const { width, height, quality = 80, format, crop } = options;

  const transforms: string[] = [];
  if (width) transforms.push(`w-${width}`);
  if (height) transforms.push(`h-${height}`);
  if (quality) transforms.push(`q-${quality}`);
  if (format) transforms.push(`f-${format}`);
  if (crop) transforms.push(`c-${crop}`);

  const transformStr = transforms.length > 0 ? `tr:${transforms.join(',')}` : '';
  const cleanPath = path.startsWith('/') ? path.slice(1) : path;

  return `${IK_ENDPOINT}/${transformStr}/${cleanPath}`;
}

export function productImage(productImage: { image: string | null | undefined; altText?: string | null } | null | undefined, options?: Parameters<typeof imageKitUrl>[1]): string {
  if (!productImage?.image) return '/images/placeholder-jewel.jpg';
  return imageKitUrl(productImage.image, options);
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
