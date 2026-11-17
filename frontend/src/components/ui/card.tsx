'use client';

import * as React from 'react';
import { cn } from '@/lib/utils';

export interface CardProps extends React.HTMLAttributes<HTMLDivElement> {
  padded?: boolean;
}

export function Card({ className, padded = true, ...props }: CardProps) {
  return (
    <div
      className={cn(
        'bg-white border border-beige rounded-xl overflow-hidden transition-all duration-350 hover:border-gold hover:shadow-[0_18px_42px_rgba(37,34,31,0.09)] hover:-translate-y-3',
        padded ? 'p-6' : 'p-0',
        className
      )}
      {...props}
    />
  );
}

export interface CardHeaderProps extends React.HTMLAttributes<HTMLDivElement> {
  title?: string;
  subtitle?: string;
}

export function CardHeader({ className, title, subtitle, ...props }: CardHeaderProps) {
  return (
    <div className={cn('mb-4', className)} {...props}>
      {title && <h3 className="font-serif font-bold text-charcoal text-lg mb-1">{title}</h3>}
      {subtitle && <p className="text-sm text-warm-gray">{subtitle}</p>}
    </div>
  );
}

export interface CardContentProps extends React.HTMLAttributes<HTMLDivElement> {}
export function CardContent({ className, ...props }: CardContentProps) {
  return <div className={cn(className)} {...props} />;
}

export interface CardFooterProps extends React.HTMLAttributes<HTMLDivElement> {}
export function CardFooter({ className, ...props }: CardFooterProps) {
  return <div className={cn('mt-6 pt-4 border-t border-beige', className)} {...props} />;
}
