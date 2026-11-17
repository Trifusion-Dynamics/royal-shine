'use client';

import * as React from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { cn } from '@/lib/utils';
import { categoryImage } from '@/lib/imagekit';

export interface CategoryCardProps {
  category: { id: number; name: string; slug: string; image?: string | null; description?: string | null };
  link?: boolean;
}

export function CategoryCard({ category, link = true }: CategoryCardProps) {
  const imgUrl = categoryImage(category.image);

  if (link) {
    return (
      <Link href={`/jewellery?category=${category.slug}`} className="block group">
        <div className="image-wrap aspect-[1] mb-3">
          {category.image ? (
            <Image
              src={imgUrl}
              alt={category.name}
              fill
              sizes="(max-width: 768px) 50vw, 25vw"
              className="object-cover object-center group-hover:scale-105 transition-transform duration-300"
              priority
            />
          ) : (
            <div className="w-full h-full lazy-placeholder flex items-center justify-center">
              <span className="text-warm-gray text-xs font-medium uppercase tracking-wider">
                {category.name.charAt(0).toUpperCase() + category.name.charAt(1).toUpperCase()}
              </span>
            </div>
          )}
          <div className="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-opacity duration-500" />
        </div>

        <h3 className="font-serif text-lg text-charcoal leading-tight group-hover:text-gold transition-colors">
          {category.name}
        </h3>

        {category.description && (
          <p className="text-sm text-warm-gray mt-1.5 line-clamp-2 leading-relaxed">
            {category.description}
          </p>
        )}
      </Link>
    );
  }

  return (
    <div className="block group">
      <div className="image-wrap aspect-[1] mb-3">
        {category.image ? (
          <Image
            src={imgUrl}
            alt={category.name}
            fill
            sizes="(max-width: 768px) 50vw, 25vw"
            className="object-cover object-center group-hover:scale-105 transition-transform duration-300"
            priority
          />
        ) : (
          <div className="w-full h-full lazy-placeholder flex items-center justify-center">
            <span className="text-warm-gray text-xs font-medium uppercase tracking-wider">
              {category.name.charAt(0).toUpperCase() + category.name.charAt(1).toUpperCase()}
            </span>
          </div>
        )}
        <div className="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-opacity duration-500" />
      </div>

      <h3 className="font-serif text-lg text-charcoal leading-tight group-hover:text-gold transition-colors">
        {category.name}
      </h3>

      {category.description && (
        <p className="text-sm text-warm-gray mt-1.5 line-clamp-2 leading-relaxed">
          {category.description}
        </p>
      )}
    </div>
  );
}
