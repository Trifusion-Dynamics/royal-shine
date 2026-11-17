'use client';

import { useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import { useCart } from '@/contexts/CartContext';
import { enquiriesService } from '@/services/enquiries.service';
import { formatPrice } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { productImage } from '@/lib/imagekit';

export default function CheckoutPage() {
  const { items, subtotal, clearCart } = useCart();
  const router = useRouter();

  const [form, setForm] = useState({
    name: '',
    mobile: '',
    email: '',
    message: 'I would like to enquire about the following jewellery pieces:',
  });
  const [error, setError] = useState('');
  const [busy, setBusy] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setBusy(true);
    try {
      const productRefs = items
        .map((i) => `${i.product.name} (x${i.quantity})`)
        .join(', ');
      const message = `${form.message}\n\nItems:\n${productRefs}\n\nSubtotal: ${
        items.some((i) => i.product.price_on_request || !i.product.price)
          ? 'Price on Request'
          : formatPrice(subtotal)
      }`;

      await enquiriesService.submit({
        name: form.name,
        mobile: form.mobile,
        email: form.email || undefined,
        message,
        subject: `Jewellery Enquiry - ${items.length} item(s)`,
        enquiryType: 'product',
        productReference: productRefs,
      });
      clearCart();
      router.push('/contact-us?enquiry=success');
    } catch (err: any) {
      setError(err?.response?.data?.message || 'Unable to submit enquiry. Please try again.');
    } finally {
      setBusy(false);
    }
  };

  return (
    <main className="min-h-screen">
      <Header />
      <section className="py-12 md:py-20">
        <div className="container-luxury">
          {items.length === 0 ? (
            <div className="text-center py-24 bg-white border border-beige rounded-2xl p-8">
              <div className="w-16 h-16 rounded-full bg-champagne flex items-center justify-center mx-auto mb-6 text-gold text-2xl">
                🛍️
              </div>
              <h2 className="text-xl font-serif text-charcoal mb-3">Your cart is empty</h2>
              <p className="text-warm-gray max-w-sm mx-auto mb-8">
                Add some jewellery pieces to your cart before submitting an enquiry.
              </p>
              <Link href="/jewellery" className="btn btn-gold btn-lg">
                Browse Jewellery
              </Link>
            </div>
          ) : (
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
              <div className="lg:col-span-2 space-y-6">
                <Card padded>
                  <h2 className="font-serif text-2xl text-charcoal mb-6">Your Details</h2>
                  <form onSubmit={handleSubmit} className="space-y-5">
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                      <div className="space-y-2">
                        <label className="text-sm font-medium text-charcoal block">Full Name</label>
                        <input
                          required
                          type="text"
                          value={form.name}
                          onChange={(e) => setForm({ ...form, name: e.target.value })}
                          placeholder="e.g. Aanya Sharma"
                          className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none"
                        />
                      </div>
                      <div className="space-y-2">
                        <label className="text-sm font-medium text-charcoal block">Mobile Number</label>
                        <input
                          required
                          type="tel"
                          value={form.mobile}
                          onChange={(e) => setForm({ ...form, mobile: e.target.value })}
                          placeholder="e.g. +91 98765 43210"
                          className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none"
                        />
                      </div>
                      <div className="space-y-2 sm:col-span-2">
                        <label className="text-sm font-medium text-charcoal block">Email Address</label>
                        <input
                          type="email"
                          value={form.email}
                          onChange={(e) => setForm({ ...form, email: e.target.value })}
                          placeholder="e.g. aanya@example.com"
                          className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none"
                        />
                      </div>
                    </div>
                    <div className="space-y-2">
                      <label className="text-sm font-medium text-charcoal block">Message</label>
                      <textarea
                        required
                        rows={4}
                        value={form.message}
                        onChange={(e) => setForm({ ...form, message: e.target.value })}
                        placeholder="Tell us about your requirements..."
                        className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none"
                      />
                    </div>
                    {error && (
                      <div className="rounded-lg bg-red-50 border border-red-100 p-4 text-sm text-red-800">
                        {error}
                      </div>
                    )}
                    <Button
                      variant="gold"
                      size="lg"
                      type="submit"
                      disabled={busy}
                      className="w-full"
                    >
                      {busy ? 'Submitting...' : 'Submit Enquiry'}
                    </Button>
                  </form>
                </Card>
              </div>

              <div className="lg:col-span-1 bg-white border border-beige rounded-2xl p-8 sticky top-24">
                <h2 className="font-serif text-lg text-charcoal mb-6">Enquiry Summary</h2>
                <div className="space-y-4 mb-8">
                  {items.map((item) => {
                    const product = item.product;
                    return (
                      <div key={product.id} className="flex gap-3 items-start mb-4 pb-4 border-b border-beige last:border-0 last:pb-0 last:mb-0">
                        <div className="flex-shrink-0 w-12 h-12 rounded-md overflow-hidden bg-champagne">
                          {product.images?.[0]?.image ? (
                            <img
                              src={productImage(product.images[0].image, { width: 80, height: 80, quality: 80, format: 'webp' })}
                              alt={product.name}
                              className="w-full h-full object-cover"
                            />
                          ) : (
                            <div className="w-full h-full flex items-center justify-center text-warm-gray text-[10px] font-medium uppercase">
                              {product.name.charAt(0).toUpperCase()}
                            </div>
                          )}
                        </div>
                        <div className="flex-1 min-w-0">
                          <p className="text-sm font-medium text-charcoal truncate">{product.name}</p>
                          <p className="text-xs text-warm-gray">Qty: {item.quantity}</p>
                          {product.price && !product.price_on_request && (
                            <p className="text-xs font-medium text-gold">{formatPrice((product.price ?? 0) * item.quantity)}</p>
                          )}
                        </div>
                      </div>
                    );
                  })}
                  <div className="pt-4 border-t border-beige flex justify-between text-sm font-medium text-charcoal">
                    <span>Total</span>
                    <span>
                      {items.some((i) => i.product.price_on_request || !i.product.price)
                        ? 'Price on Request'
                        : formatPrice(subtotal)
                    }
                    </span>
                  </div>
                </div>
                <div className="rounded-lg bg-champagne p-5 border border-beige">
                  <p className="text-xs text-brown uppercase tracking-wider font-medium mb-2">
                    How it works
                  </p>
                  <p className="text-sm text-charcoal leading-relaxed">
                    Submit your enquiry and our team will contact you via WhatsApp with pricing and availability.
                  </p>
                </div>
                <button
                  onClick={clearCart}
                  className="mt-4 w-full text-xs text-warm-gray underline hover:text-gold transition-colors"
                >
                  Clear enquiry
                </button>
              </div>
            </div>
          )}
        </div>
      </section>
      <Footer />
    </main>
  );
}
