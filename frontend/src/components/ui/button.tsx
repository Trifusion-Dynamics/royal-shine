'use client';

import * as React from 'react';
import { cn } from '@/lib/utils';

export interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'gold' | 'brown' | 'outline' | 'text';
  size?: 'sm' | 'md' | 'lg';
  asChild?: boolean;
}

const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
  ({ className, variant = 'gold', size = 'md', asChild = false, ...props }, ref) => {
    const Comp = asChild ? React.Fragment : 'button';

    const variants = {
      gold: 'bg-gradient-gold text-white shadow-gold',
      brown: 'bg-brown text-white',
      outline: 'border border-beige text-charcoal bg-transparent',
      text: 'bg-transparent text-charcoal p-0',
    };

    const sizes = {
      sm: 'min-height-44 px-3 py-1.5 text-xs',
      md: 'min-height-46 px-6 py-3 text-sm',
      lg: 'min-height-52 px-8 py-4 text-base',
    };

    return (
      <Comp
        {...(asChild ? {} : { ref })}
        className={cn(
          'inline-flex items-center justify-center font-medium transition-all duration-300 active:scale-95 disabled:opacity-50 disabled:pointer-events-none rounded-lg',
          variants[variant],
          sizes[size],
          className
        )}
        {...(asChild ? {} : { type: 'button' })}
        {...props}
      />
    );
  }
);

Button.displayName = 'Button';

export { Button };
