'use client';

import { createContext, useContext, useEffect, useState, useCallback, ReactNode } from 'react';
import toast from 'react-hot-toast';
import type { Product, CartState, CartItem } from '@/types';

const CartContext = createContext<CartState | undefined>(undefined);

export function CartProvider({ children }: { children: ReactNode }) {
  const [items, setItems] = useState<CartItem[]>([]);
  const [isOpen, setIsOpen] = useState(false);

  useEffect(() => {
    if (typeof window === 'undefined') return;
    try {
      const raw = localStorage.getItem('royal_shine_cart');
      if (raw) setItems(JSON.parse(raw));
    } catch {}
  }, []);

  useEffect(() => {
    if (typeof window === 'undefined') return;
    localStorage.setItem('royal_shine_cart', JSON.stringify(items));
  }, [items]);

  const itemCount = items.reduce((acc, i) => acc + i.quantity, 0);
  const subtotal = items.reduce(
    (acc, i) => acc + (i.product.price ?? 0) * i.quantity,
    0
  );

  const addItem = useCallback((product: Product, qty = 1) => {
    setItems((prev) => {
      const existing = prev.find((i) => i.product.id === product.id);
      if (existing) {
        return prev.map((i) =>
          i.product.id === product.id ? { ...i, quantity: i.quantity + qty } : i
        );
      }
      toast.success(`${product.name} added to cart`, { duration: 3000 });
      return [...prev, { product, quantity: qty }];
    });
  }, []);

  const removeItem = useCallback((productId: number) => {
    const removed = items.find((i) => i.product.id === productId);
    setItems((prev) => prev.filter((i) => i.product.id !== productId));
    if (removed) toast.error(`${removed.product.name} removed from cart`, { duration: 3000 });
  }, [items]);

  const updateQuantity = useCallback((productId: number, qty: number) => {
    if (qty <= 0) {
      const removed = items.find((i) => i.product.id === productId);
      setItems((prev) => prev.filter((i) => i.product.id !== productId));
      if (removed) toast.error(`${removed.product.name} removed from cart`, { duration: 3000 });
      return;
    }
    setItems((prev) =>
      prev.map((i) => (i.product.id === productId ? { ...i, quantity: qty } : i))
    );
  }, [items]);

  const clearCart = useCallback(() => {
    setItems([]);
    toast.error('Cart cleared', { duration: 3000 });
  }, []);

  const value: CartState = {
    items,
    itemCount,
    subtotal,
    addItem,
    removeItem,
    updateQuantity,
    clearCart,
    isOpen,
    setIsOpen,
  };

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}

export function useCart() {
  const ctx = useContext(CartContext);
  if (!ctx) throw new Error('useCart must be used within a CartProvider');
  return ctx;
}
