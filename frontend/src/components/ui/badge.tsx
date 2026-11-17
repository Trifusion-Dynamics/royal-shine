'use client';

import * as React from 'react';
import { cn } from '@/lib/utils';

export interface BadgeProps extends React.HTMLAttributes<HTMLSpanElement> {
  variant?: 'gold' | 'brown' | 'outline';
}

export function Badge({ className, variant = 'gold', ...props }: BadgeProps) {
  const variants = {
    gold: 'bg-white text-brown border border-beige',
    brown: 'bg-brown text-white',
    outline: 'border border-beige text-charcoal bg-transparent',
  };

  return (
    <span
      className={cn(
        'inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-medium uppercase tracking-wider',
        variants[variant],
        className
      )}
      {...props}
    />
  );
}
