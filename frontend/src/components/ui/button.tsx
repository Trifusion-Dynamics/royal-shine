'use client';

import * as React from 'react';
import { cn } from '@/lib/utils';

export interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'primary' | 'secondary' | 'outline' | 'ghost' | 'dark';
  size?: 'sm' | 'md' | 'lg';
  asChild?: boolean;
}

const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
  ({ className, variant = 'primary', size = 'md', asChild = false, ...props }, ref) => {
    const Comp = asChild ? React.Fragment : 'button';

    const variants = {
      primary: 'bg-gold-500 text-white hover:bg-gold-600 shadow-gold',
      secondary: 'bg-dark-100 text-dark-900 hover:bg-dark-200',
      outline: 'border border-dark-200 text-dark-900 hover:bg-dark-50',
      ghost: 'text-dark-600 hover:bg-dark-100',
      dark: 'bg-dark-900 text-white hover:bg-dark-800',
    };

    const sizes = {
      sm: 'px-3 py-1.5 text-xs',
      md: 'px-6 py-3 text-sm',
      lg: 'px-8 py-4 text-base',
    };

    return (
      <Comp
        ref={ref}
        className={cn(
          'inline-flex items-center justify-center rounded-lg font-medium transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none',
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
