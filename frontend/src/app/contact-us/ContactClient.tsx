'use client';

import { useState } from 'react';
import Link from 'next/link';
import { enquiriesService } from '@/services/enquiries.service';
import { settingsService } from '@/services/settings.service';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';
import { SectionHeading } from '@/components/ui/section-heading';

export default function ContactClient() {
  const [submitting, setSubmitting] = useState(false);
  const [submitted, setSubmitted] = useState(false);
  const [error, setError] = useState('');
  const [settings, setSettings] = useState<Record<string, string>>({});
  const [loading, setLoading] = useState(true);

  async function loadSettings() {
    try {
      const data = await settingsService.getAll();
      const map: Record<string, string> =
        typeof data === 'object' && !Array.isArray(data) ? data : {};
      setSettings(map);
    } finally {
      setLoading(false);
    }
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setError('');
    try {
      const formData = new FormData(e.currentTarget as HTMLFormElement);
      const payload: any = {
        name: formData.get('name'),
        mobile: formData.get('mobile'),
        email: formData.get('email') || null,
        subject: formData.get('subject'),
        message: formData.get('message'),
        enquiry_type: 'contact',
      };
      await enquiriesService.submit(payload);
      setSubmitted(true);
    } catch (err: any) {
      setError(err.response?.data?.message || 'Failed to send message. Please try again.');
    } finally {
      setSubmitting(false);
    }
  }

  if (loading) {
    return (
      <main className="min-h-screen">
        <Header />
        <section className="py-16">
          <div className="container-luxury text-center py-20">
            <div className="animate-pulse bg-champagne rounded-xl h-96 w-full mx-auto max-w-4xl" />
          </div>
        </section>
        <Footer />
      </main>
    );
  }

  const phone = settings.phone;
  const whatsapp = settings.whatsapp_number;
  const email = settings.email;
  const address = settings.address;
  const businessHours = settings.business_hours;

  if (submitted) {
    return (
      <main className="min-h-screen">
        <Header />
        <section className="py-16">
          <div className="container-luxury text-center py-20">
            <div className="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
              <span className="text-green-700 font-bold text-2xl">✓</span>
            </div>
            <h2 className="font-serif text-3xl text-charcoal mb-4">Thank you!</h2>
            <p className="text-warm-gray mb-8">
              Your message has been sent successfully. Our team will get back to you shortly.
            </p>
            <Link href="/jewellery" className="btn btn-gold inline-block">
              Browse collection
            </Link>
          </div>
        </section>
        <Footer />
      </main>
    );
  }

  return (
    <main className="min-h-screen">
      <Header />
      <section className="py-16">
        <div className="container-luxury">
          <div className="text-center mb-16">
            <h1 className="text-5xl md:text-6xl font-serif text-charcoal mb-4 leading-tight">
              Contact Us
            </h1>
            <div className="w-20 h-[1px] bg-gold mx-auto mb-6" />
            <p className="text-lg text-warm-gray max-w-2xl mx-auto leading-relaxed">
              Have a question about our jewellery collection? Want to place a custom order? We&rsquo;d love to hear from you.
            </p>
          </div>

          <div className="grid lg:grid-cols-2 gap-12">
            <div className="space-y-10">
              <div>
                <h2 className="text-3xl font-serif text-charcoal mb-6 leading-tight">
                  Get in Touch
                </h2>
                <p className="text-warm-gray leading-relaxed mb-6">
                  Reach out to us through any of the channels below. Our team is here to help with designs, custom orders,
                  and any questions about our collection.
                </p>
              </div>

              <div className="space-y-6">
                {businessHours && (
                  <div className="flex items-start gap-4">
                    <div className="w-10 h-10 shrink-0 bg-gold/10 text-gold rounded-full flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <div>
                      <p className="font-medium text-charcoal">Business Hours</p>
                      <p className="text-warm-gray">{businessHours}</p>
                    </div>
                  </div>
                )}
                {phone && (
                  <div className="flex items-start gap-4">
                    <div className="w-10 h-10 shrink-0 bg-gold/10 text-gold rounded-full flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M3 5a2 2 0 012-2h3.28a1 1 0 011.515.736l.67 3.277a2 2 0 01-.446 1.802l-.545 1.27a.5.5 0 00.133.33c.336.155.55.04.735-.156l1.186-.593c.389-.194.593-.604.593-1.02V5.802a2 2 0 00-.593-1.02l-.546-1.27A2 2 0 018.28 2H5a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2v-1.172c0-.416-.204-.828-.593-1.02l-.545-1.27a2 2 0 01-.446-1.802l.67-3.277a1 1 0 011.515-.736H19a2 2 0 012 2v2a2 2 0 01-2 2z" />
                      </svg>
                    </div>
                    <div>
                      <p className="font-medium text-charcoal">Phone</p>
                      <a href={`tel:${phone}`} className="text-warm-gray hover:text-gold transition-colors">
                        {phone}
                      </a>
                    </div>
                  </div>
                )}
                {whatsapp && (
                  <div className="flex items-start gap-4">
                    <div className="w-10 h-10 shrink-0 bg-gold/10 text-gold rounded-full flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                      </svg>
                    </div>
                    <div>
                      <p className="font-medium text-charcoal">WhatsApp</p>
                      <a
                        href={`https://wa.me/${whatsapp}?text=${encodeURIComponent('Hi Royal Shine, I would like to enquire about your jewellery collection.')}`}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-warm-gray hover:text-gold transition-colors"
                      >
                        {whatsapp}
                      </a>
                    </div>
                  </div>
                )}
                {email && (
                  <div className="flex items-start gap-4">
                    <div className="w-10 h-10 shrink-0 bg-gold/10 text-gold rounded-full flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" /><polyline points="22,6 12,13 2,6" />
                      </svg>
                    </div>
                    <div>
                      <p className="font-medium text-charcoal">Email</p>
                      <a href={`mailto:${email}`} className="text-warm-gray hover:text-gold transition-colors">
                        {email}
                      </a>
                    </div>
                  </div>
                )}
                {address && (
                  <div className="flex items-start gap-4">
                    <div className="w-10 h-10 shrink-0 bg-gold/10 text-gold rounded-full flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <polygon points="3 11 22 18 20 12 18 1 10 13 2 11 3 11" />
                      </svg>
                    </div>
                    <div>
                      <p className="font-medium text-charcoal">Address</p>
                      <p className="text-warm-gray">{address}</p>
                    </div>
                  </div>
                )}
              </div>
            </div>

            <div className="border border-beige bg-white rounded-lg p-8 md:p-12 shadow-sm">
              <h2 className="font-serif font-bold text-charcoal mb-6 text-2xl leading-tight">
                Send Us a Message
              </h2>
              {error && (
                <div className="mb-6 p-4 rounded-lg bg-red-50 border border-red-100 text-red-700 text-sm">
                  {error}
                </div>
              )}
              <form onSubmit={handleSubmit} className="space-y-6">
                <input type="text" name="website" value="" className="hidden" tabIndex={-1} autoComplete="off" aria-hidden={true} />

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                  <div>
                    <label className="block text-sm font-medium text-charcoal mb-1.5">
                      Full Name <span className="text-gold">*</span>
                    </label>
                    <input
                      name="name"
                      type="text"
                      required
                      className="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-charcoal mb-1.5">
                      Mobile Number <span className="text-gold">*</span>
                    </label>
                    <input
                      name="mobile"
                      type="tel"
                      required
                      className="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20"
                    />
                  </div>
                </div>
                <div className="sm:col-span-2">
                  <label className="block text-sm font-medium text-charcoal mb-1.5">
                    Email <span className="text-gold">*</span>
                  </label>
                  <input
                    name="email"
                    type="email"
                    required
                    className="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20"
                  />
                </div>
                <div className="sm:col-span-2">
                  <label className="block text-sm font-medium text-charcoal mb-1.5">
                    Subject <span className="text-gold">*</span>
                  </label>
                  <input
                    name="subject"
                    type="text"
                    required
                    maxLength={255}
                    className="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20"
                  />
                </div>
                <div className="sm:col-span-2">
                  <label className="block text-sm font-medium text-charcoal mb-1.5">
                    Message <span className="text-gold">*</span>
                  </label>
                  <textarea
                    name="message"
                    rows={5}
                    className="w-full px-4 py-3 border border-beige rounded-md text-sm text-charcoal outline-none focus:border-gold focus:ring-2 focus:ring-gold/20"
                  />
                </div>
                <button
                  type="submit"
                  disabled={submitting}
                  className="btn btn-gold w-full sm:w-auto px-8 py-3 disabled:opacity-60"
                >
                  {submitting ? 'Sending...' : 'Send Message'}
                </button>
              </form>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </main>
  );
}
