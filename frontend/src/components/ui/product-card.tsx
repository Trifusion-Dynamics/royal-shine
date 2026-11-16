'use client';

import * as React from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { cn } from '@/lib/utils';
import { productImage } from '@/lib/imagekit';
import { Badge } from '@/components/ui/badge';
import type { Product } from '@/types';

export interface ProductCardProps {
  product: Product;
  variant?: 'default' | 'compact';
}

export function ProductCard({ product, variant = 'default' }: ProductCardProps) {
  const primaryImage = product.images?.[0] || product.primary_image;
  const imgUrl = productImage(primaryImage, { width: 400, height: 400, quality: 85, format: 'webp' });
  const price = product.price_on_request ? 'Price on request' : `₹${product.price?.toLocaleString('en-IN') ?? '—'}`;

  const base = 'group relative overflow-hidden rounded-2xl border border-dark-100 bg-white transition-all duration-300 hover:shadow-gold-lg hover:border-gold-300';
  const compact = 'flex flex-col h-full';

  return (
    <article className={cn(base, variant === 'compact' ? compact : '')}>
      <div className="relative aspect-[1] overflow-hidden bg-dark-50">
        <Image
          src={imgUrl}
          alt={primaryImage?.alt_text || product.name}
          fill
          sizes="(max-width: 768px) 100vw, (max-width: 1200px) 33vw, 25vw"
          priority={variant === 'default'}
          className="object-cover object-center transition-transform duration-500 group-hover:scale-105"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
        {product.is_featured && (
          <Badge className="absolute top-4 left-4 z-10">Featured</Badge>
        )}
        {product.price_on_request && (
          <Badge variant="warning" className="absolute top-4 right-4 z-10">
            POA
          </Badge>
        )}
      </div>
      <div className="p-4">
        <div className="flex items-center justify-between mb-2">
          <p className="text-xs uppercase tracking-wider text-dark-400">{product.category?.name || 'Jewellery'}</p>
        </div>
        <Link href={`/product/${product.slug}`} className="group">
          <h3 className="font-serif font-semibold text-dark-900 mb-1 group-hover:text-gold-600 transition-colors">
            {product.name}
          </h3>
        </Link>
        {product.short_description && (
          <p className="text-sm text-dark-500 line-clamp-2 mb-3">{product.short_description}</p>
        )}
        <div className="flex items-center justify-between">
          <span className="font-serif font-bold text-dark-900">{price}</span>
          <Link href={`/product/${product.slug}`} className="text-gold-600 text-sm font-medium hover:text-gold-700">
            View →
          </Link>
        </div>
      </div>
    </article>
  );
}
