'use client';

import { useState } from 'react';
import { enquiriesService } from '@/services/enquiries.service';
import { settingsService } from '@/services/settings.service';
import { getWhatsAppUrl } from '@/lib/utils';
import Header from '@/components/public/header';
import Footer from '@/components/public/footer';

export default function ContactForm() {
  const [submitting, setSubmitting] = useState(false);
  const [submitted, setSubmitted] = useState(false);
  const [error, setError] = useState('');
  const [settings, setSettings] = useState<Record<string, string>>({});

  async function loadSettings() {
    try {
      const data = await settingsService.getAll();
      const map: Record<string, string> = typeof data === 'object' && !Array.isArray(data) ? data : {};
      setSettings(map);
    } catch {}
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setSubmitting(true);
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
      setError(err.response?.data?.message || 'Failed to submit enquiry. Please try again.');
    } finally {
      setSubmitting(false);
    }
  }

  const phone = settings.phone;
  const email = settings.email;
  const address = settings.address;

  return (
    <main className="min-h-screen">
      <Header />
      {submitted ? (
        <section className="section-wrap">
          <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-20">
            <div className="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
              <span className="text-green-700 font-bold text-2xl">✓</span>
            </div>
            <h2 className="font-serif text-3xl text-dark-900 mb-4">Thank you!</h2>
            <p className="text-dark-600 mb-8">
              Your enquiry has been submitted successfully. Our team will get back to you shortly.
            </p>
            <a href="/jewellery" className="button button-dark inline-block">
              Browse collection
            </a>
          </div>
        </section>
      ) : (
        <section className="section-wrap">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="lg:grid lg:grid-cols-12 gap-12">
              <div className="lg:col-span-6">
                <h2 className="font-serif text-4xl text-dark-900 mb-6">Get in Touch</h2>
                <p className="text-dark-600 mb-8 leading-relaxed">
                  Have a question about our jewellery? Want to discuss a custom design? We'd love to hear from you.
                </p>
                <div className="space-y-6">
                  {phone && (
                    <div className="flex items-start gap-4">
                      <div className="w-10 h-10 rounded-full bg-dark-50 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.79 19.79 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 1 0 7H4.11a2 2 0 0 1-2-1.72 19.79 19.79 0 0 1 3.07-8.63 19.79 19.79 0 0 1 8.63-3.07 19.79 19.79 0 0 1 6 6c0 .85-.11 1.66-.31 2.49A12.84 12.84 0 0 1 20.18 14a2 2 0 0 1-1.92 2.21z"/></svg>
                      </div>
                      <div>
                        <p className="font-semibold text-dark-900 mb-1">Call us</p>
                        <p className="text-dark-600 text-sm">{phone}</p>
                      </div>
                    </div>
                  )}
                  {email && (
                    <div className="flex items-start gap-4">
                      <div className="w-10 h-10 rounded-full bg-dark-50 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                      </div>
                      <div>
                        <p className="font-semibold text-dark-900 mb-1">Email us</p>
                        <p className="text-dark-600 text-sm">{email}</p>
                      </div>
                    </div>
                  )}
                  {address && (
                    <div className="flex items-start gap-4">
                      <div className="w-10 h-10 rounded-full bg-dark-50 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polygon points="3 11 22 18 20 12 18 1 10 13 2 11 3 11"/></svg>
                      </div>
                      <div>
                        <p className="font-semibold text-dark-900 mb-1">Visit us</p>
                        <p className="text-dark-600 text-sm">{address}</p>
                      </div>
                    </div>
                  )}
                </div>
              </div>

              <div className="lg:col-span-6">
                <form onSubmit={handleSubmit} className="bg-white rounded-2xl border border-dark-100 p-8 shadow-sm">
                  <h3 className="font-serif text-2xl text-dark-900 mb-6">Send us a message</h3>
                  {error && (
                    <div className="mb-6 p-4 rounded-lg bg-red-50 border border-red-100 text-red-700 text-sm">
                      {error}
                    </div>
                  )}
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div className="flex flex-col gap-2">
                      <label className="text-sm font-medium text-dark-700">Full Name</label>
                      <input
                        name="name"
                        type="text"
                        required
                        className="px-4 py-3 rounded-lg border border-dark-200 bg-white focus:outline-none focus:border-gold-600 focus:ring-1 focus:ring-gold-600 text-sm"
                      />
                    </div>
                    <div className="flex flex-col gap-2">
                      <label className="text-sm font-medium text-dark-700">Mobile Number</label>
                      <input
                        name="mobile"
                        type="tel"
                        required
                        className="px-4 py-3 rounded-lg border border-dark-200 bg-white focus:outline-none focus:border-gold-600 focus:ring-1 focus:ring-gold-600 text-sm"
                      />
                    </div>
                  </div>
                  <div className="flex flex-col gap-2 mb-6">
                    <label className="text-sm font-medium text-dark-700">Email Address</label>
                    <input
                      name="email"
                      type="email"
                      className="px-4 py-3 rounded-lg border border-dark-200 bg-white focus:outline-none focus:border-gold-600 focus:ring-1 focus:ring-gold-600 text-sm"
                    />
                  </div>
                  <div className="flex flex-col gap-2 mb-6">
                    <label className="text-sm font-medium text-dark-700">Subject</label>
                    <input
                      name="subject"
                      type="text"
                      required
                      className="px-4 py-3 rounded-lg border border-dark-200 bg-white focus:outline-none focus:border-gold-600 focus:ring-1 focus:ring-gold-600 text-sm"
                    />
                  </div>
                  <div className="flex flex-col gap-2 mb-8">
                    <label className="text-sm font-medium text-dark-700">Message</label>
                    <textarea
                      name="message"
                      rows={5}
                      required
                      className="px-4 py-3 rounded-lg border border-dark-200 bg-white focus:outline-none focus:border-gold-600 focus:ring-1 focus:ring-gold-600 text-sm"
                    />
                  </div>
                  <button
                    type="submit"
                    disabled={submitting}
                    className="button button-dark w-full py-4 text-base disabled:opacity-60"
                  >
                    {submitting ? 'Sending...' : 'Send Enquiry'}
                  </button>
                </form>
              </div>
            </div>
          </div>
        </section>
      )}
      <Footer />
    </main>
  );
}
