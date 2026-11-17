'use client';

import Link from 'next/link';
import { useCart } from '@/contexts/CartContext';
import { formatPrice } from '@/lib/utils';
import { productImage } from '@/lib/imagekit';
import { Button } from '@/components/ui/button';

export default function MiniCart() {
  const { items, itemCount, subtotal, isOpen, setIsOpen, removeItem, updateQuantity, clearCart } = useCart();

  return (
    <>
      <button
        onClick={() => setIsOpen(true)}
        className="relative p-2 text-charcoal hover:text-gold transition-colors"
        aria-label="Shopping cart"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="22"
          height="22"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          strokeWidth="2"
          strokeLinecap="round"
          strokeLinejoin="round"
        >
          <circle cx="9" cy="19" r="2" />
          <circle cx="15" cy="19" r="2" />
          <path d="M2 5h3l2 5h14l2-5H2" />
        </svg>
        {itemCount > 0 && (
          <span className="absolute top-0 right-0 h-5 w-5 rounded-full bg-gold text-[10px] text-white flex items-center justify-center font-bold border-2 border-white">
            {itemCount}
          </span>
        )}
      </button>

      <div
        className={`fixed inset-0 z-[100] ${isOpen ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none invisible transition-opacity duration-300'}`}
        onClick={(e) => {
          if (e.target === e.currentTarget) setIsOpen(false);
        }}
      >
        <div className="absolute inset-0 bg-black/40 backdrop-blur-sm" onClick={() => setIsOpen(false)} />

        <div className="absolute right-0 top-0 bottom-0 w-full md:w-[420px] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ${isOpen ? 'translate-x-0' : ''} z-50">
          <div className="p-6 border-b border-beige flex justify-between items-center bg-white">
            <h2 className="font-serif font-bold text-charcoal text-lg">Shopping Cart</h2>
            <button onClick={() => setIsOpen(false)} className="text-charcoal hover:text-gold p-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <line x1="6" y1="18" x2="18" y2="6" />
                <line x1="6" y1="6" x2="12" y2="12" />
              </svg>
            </button>
          </div>

          <div className="p-6 overflow-y-auto max-h-[70vh]">
            {items.length === 0 ? (
              <div className="text-center py-20">
                <p className="text-warm-gray">Your cart is empty</p>
                <Link href="/jewellery" className="mt-4 inline-flex items-center gap-2 text-gold font-medium hover:underline">
                  Browse Jewellery <span>→</span>
                </Link>
              </div>
            ) : (
              <>
                {items.map((item) => {
                  const product = item.product;
                  const firstImage = product.images?.[0] || product.primary_image;
                  const imgUrl = productImage(firstImage?.image, { width: 80, height: 80, quality: 80, format: 'webp' });
                  const lineTotal = (product.price ?? 0) * item.quantity;
                  return (
                    <div key={product.id} className="flex gap-4 items-start mb-5 pb-5 border-b border-beige last:border-0 last:pb-0 last:mb-0">
                      <div className="flex-shrink-0 w-20 h-20 rounded-md overflow-hidden bg-champagne">
                        {firstImage?.image ? (
                          <img src={imgUrl} alt={product.name} className="w-full h-full object-cover" />
                        ) : (
                          <div className="w-full h-full flex items-center justify-center text-warm-gray text-xs font-medium uppercase">
                            {product.name.charAt(0).toUpperCase()}
                          </div>
                        )}
                      </div>
                      <div className="flex-1 min-w-0">
                        <Link href={`/product/${product.slug}`} className="text-charcoal font-medium text-sm leading-tight group-hover:text-gold transition-colors hover:no-underline">
                          {product.name}
                        </Link>
                        <p className="mt-1 text-sm text-warm-gray">
                          {product.price_on_request ? 'Price on Request' : formatPrice(product.price)}
                        </p>
                        <div className="mt-3 flex items-center gap-3">
                          <button
                            onClick={() => updateQuantity(product.id, Math.max(1, item.quantity - 1))}
                            className="w-8 h-8 rounded border border-beige flex items-center justify-center text-charcoal hover:bg-champagne hover:text-gold transition-colors"
                          >
                            −
                          </button>
                          <span className="text-xs font-medium w-4 text-center">{item.quantity}</span>
                          <button
                            onClick={() => updateQuantity(product.id, item.quantity + 1)}
                            className="w-8 h-8 rounded border border-beige flex items-center justify-center text-charcoal hover:bg-champagne hover:text-gold transition-colors"
                          >
                            +
                          </button>
                          <button
                            onClick={() => removeItem(product.id)}
                            className="ml-3 text-xs text-warm-gray hover:text-red-600 underline"
                          >
                            Remove
                          </button>
                        </div>
                      </div>
                    </div>
                  );
                })}

                <div className="mt-8 pt-6 border-t border-beige">
                  <div className="flex justify-between mb-2">
                    <span className="text-sm text-warm-gray">Subtotal</span>
                    <span className="text-sm font-medium text-charcoal">
                      {items.some((i) => i.product.price_on_request || !i.product.price)
                        ? 'Price on Request'
                        : formatPrice(subtotal)
                    }
                    </span>
                  </div>
                </div>
              </>
            )}
          </div>

          <div className="p-6 border-t border-beige bg-white">
            {items.length === 0 ? (
              <Button variant="brown" className="w-full" disabled onClick={() => setIsOpen(false)}>
                Continue Shopping
              </Button>
            ) : (
              <>
                <Link
                  href="/checkout"
                  className="block w-full text-center"
                >
                  <Button variant="gold" size="lg" className="w-full">
                    Proceed to Enquire
                  </Button>
                </Link>
                <p className="mt-3 text-center text-xs text-warm-gray">
                  Enquire about all items in your cart via WhatsApp.
                </p>
              </>
            )}
          </div>
        </div>
      </div>
    </>
  );
}
