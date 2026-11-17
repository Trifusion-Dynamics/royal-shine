'use client';

import Link from 'next/link';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import { useCart } from '@/contexts/CartContext';
import { formatPrice } from '@/lib/utils';
import { productImage } from '@/lib/imagekit';
import { Button } from '@/components/ui/button';

export default function CartPage() {
  const { items, subtotal, itemCount, removeItem, updateQuantity, clearCart } = useCart();

  return (
    <main className="min-h-screen">
      <Header />
      <section className="py-16 md:py-24">
        <div className="container-luxury">
          <div className="mb-12 flex items-center gap-2">
            <span className="w-8 h-8 rounded-full bg-champagne flex items-center justify-center text-gold font-bold text-sm">
              🛒
            </span>
            <h1 className="font-serif text-3xl md:text-4xl text-charcoal">Shopping Cart</h1>
          </div>

          {itemCount === 0 ? (
            <div className="text-center py-24 bg-white border border-beige rounded-2xl p-8">
              <div className="w-16 h-16 rounded-full bg-champagne flex items-center justify-center mx-auto mb-6 text-gold text-2xl">
                🛍️
              </div>
              <h2 className="text-xl font-serif text-charcoal mb-3">Your cart is empty</h2>
              <p className="text-warm-gray max-w-sm mx-auto mb-8">
                No items have been added yet. Browse our collections and add your favourite pieces.
              </p>
              <Link href="/jewellery" className="btn btn-gold btn-lg">
                Browse Jewellery
              </Link>
            </div>
          ) : (
            <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
              <div className="lg:col-span-3 space-y-6">
                <div className="bg-white border border-beige rounded-2xl p-8 shadow-sm">
                  <div className="flex justify-between items-center mb-6 border-b border-beige pb-4">
                    <h2 className="font-serif text-xl text-charcoal">Items ({itemCount})</h2>
                    <button
                      onClick={clearCart}
                      className="text-xs text-warm-gray hover:text-red-600 underline"
                    >
                      Clear cart
                    </button>
                  </div>
                  <div className="space-y-6">
                    {items.map((item) => {
                      const product = item.product;
                      const firstImage = product.images?.[0] || product.primary_image;
                      const imgUrl = productImage(firstImage?.image, { width: 300, height: 300, quality: 80, format: 'webp' });
                      const lineTotal = (product.price ?? 0) * item.quantity;
                      return (
                        <div key={product.id} className="flex gap-6 items-start">
                          <div className="flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden bg-champagne">
                            {firstImage?.image ? (
                              <img src={imgUrl} alt={product.name} className="w-full h-full object-cover" />
                            ) : (
                              <div className="w-full h-full flex items-center justify-center text-warm-gray text-xs font-medium uppercase">
                                {product.name.charAt(0).toUpperCase()}
                              </div>
                            )}
                          </div>
                          <div className="flex-1 min-w-0">
                            <Link href={`/product/${product.slug}`} className="block">
                              <h3 className="font-serif text-base text-charcoal leading-tight group-hover:text-gold transition-colors">
                                {product.name}
                              </h3>
                              {product.category?.name && (
                                <p className="text-xs text-gold uppercase tracking-wider mt-1">
                                  {product.category.name}
                                </p>
                              )}
                            </Link>
                            <p className="mt-2 text-sm text-warm-gray">
                              {product.price_on_request
                                ? 'Price on Request'
                                : formatPrice(product.price)
                            }
                            </p>
                            <div className="mt-4 flex items-center gap-3 w-48">
                              <button
                                onClick={() => updateQuantity(product.id, Math.max(1, item.quantity - 1))}
                                className="w-10 h-10 rounded border border-beige flex items-center justify-center text-charcoal hover:bg-champagne hover:text-gold transition-colors"
                              >
                                −
                              </button>
                              <input
                                type="number"
                                value={item.quantity}
                                onChange={(e) => {
                                  const v = parseInt(e.target.value, 10);
                                  if (!isNaN(v) && v > 0) updateQuantity(product.id, v);
                                }}
                                min="1"
                                className="w-12 h-10 text-center border border-beige rounded text-sm text-charcoal"
                              />
                              <button
                                onClick={() => updateQuantity(product.id, item.quantity + 1)}
                                className="w-10 h-10 rounded border border-beige flex items-center justify-center text-charcoal hover:bg-champagne hover:text-gold transition-colors"
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
                          {product.price && !product.price_on_request && (
                            <div className="text-right hidden sm:block">
                              <p className="text-sm font-medium text-charcoal">{formatPrice(lineTotal)}</p>
                            </div>
                          )}
                        </div>
                      );
                    })}
                  </div>
                </div>
              </div>

              <div className="lg:col-span-1 bg-white border border-beige rounded-2xl p-8 sticky top-24">
                <h2 className="font-serif text-lg text-charcoal mb-6">Order Summary</h2>
                <div className="space-y-4">
                  <div className="flex justify-between text-sm">
                    <span className="text-warm-gray">Subtotal</span>
                    <span className="font-medium text-charcoal">
                      {items.some((i) => i.product.price_on_request || !i.product.price)
                        ? 'Price on Request'
                        : formatPrice(subtotal)
                    }
                    </span>
                  </div>
                  <div className="pt-4 border-t border-beige">
                    <Link href="/checkout" className="block">
                      <Button variant="gold" size="lg" className="w-full">
                        Proceed to Enquire
                      </Button>
                    </Link>
                    <p className="mt-3 text-center text-xs text-warm-gray">
                      Submit an enquiry for all items in your cart. We&rsquo;ll contact you via WhatsApp.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          )}
        </div>
      </section>
      <Footer />
    </main>
  );
}
