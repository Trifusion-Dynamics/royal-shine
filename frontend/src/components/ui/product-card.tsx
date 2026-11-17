'use client';

import * as React from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { cn } from '@/lib/utils';
import { productImage } from '@/lib/imagekit';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import type { Product } from '@/types';

export interface ProductCardProps {
  product: Product;
  link?: boolean;
}

export function ProductCard({ product, link = true }: ProductCardProps) {
  const firstImage = product.images?.[0] || product.primary_image;
  const imgUrl = productImage(firstImage?.image, { width: 400, height: 400, quality: 85, format: 'webp' });
  const categoryName = product.category?.name;
  const price = product.price_on_request || !product.price ? 'Price on Request' : `₹${product.price?.toLocaleString('en-IN') ?? '—'}`;

  const cardContent = (
    <>
      <div className="image-wrap aspect-square">
        {firstImage?.image ? (
          <Image
            src={imgUrl}
            alt={firstImage.alt_text || product.name}
            fill
            sizes="(max-width: 768px) 50vw, 25vw"
            className="object-cover object-center group-hover:scale-105 transition-transform duration-700"
            priority={link}
          />
        ) : (
          <div className="w-full h-full lazy-placeholder flex items-center justify-center">
            <span className="text-warm-gray text-xs font-medium uppercase tracking-wider">
              {product.name.charAt(0).toUpperCase()}
            </span>
          </div>
        )}
        {product.price_on_request && (
          <Badge className="absolute top-3 left-3 z-10 inline-flex items-center gap-1 px-2.5 py-1">Price on Request</Badge>
        )}
        <div className="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-opacity duration-500" />
      </div>

      <div className="p-5">
        {categoryName && (
          <p className="text-xs font-medium text-gold uppercase tracking-wider mb-1.5">
            {categoryName}
          </p>
        )}
        <h3 className="font-serif text-base text-charcoal mb-2 leading-tight group-hover:text-gold transition-colors">
          {product.name}
        </h3>
        <div className="flex items-baseline gap-2 mb-3">
          <span className="text-lg font-bold text-charcoal">{price}</span>
        </div>
        <div className="flex gap-2">
          <Button
            asChild
            variant="text"
            className="flex-1 btn-text text-gold hover:text-brown focus:ring-gold"
          >
            <a href={`/product/${product.slug}`}>Enquire</a>
          </Button>
        </div>
      </div>
    </>
  );

  if (link) {
    return (
      <Link href={`/product/${product.slug}`} className={cn('card group block')}>
        {cardContent}
      </Link>
    );
  }

  return <div className={cn('card group block')}>{cardContent}</div>;
}
