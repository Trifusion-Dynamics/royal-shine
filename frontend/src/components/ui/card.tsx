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
        'bg-white rounded-2xl border border-dark-100 shadow-sm transition-all duration-200 hover:shadow-md',
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
      {title && <h3 className="text-xl font-serif font-semibold text-dark-900 mb-1">{title}</h3>}
      {subtitle && <p className="text-sm text-dark-500">{subtitle}</p>}
    </div>
  );
}

export interface CardContentProps extends React.HTMLAttributes<HTMLDivElement> {}

export function CardContent({ className, ...props }: CardContentProps) {
  return <div className={cn(className)} {...props} />;
}

export interface CardFooterProps extends React.HTMLAttributes<HTMLDivElement> {}

export function CardFooter({ className, ...props }: CardFooterProps) {
  return (
    <div className={cn('mt-6 pt-4 border-t border-dark-50 flex items-center justify-between', className)} {...props} />
  );
}
