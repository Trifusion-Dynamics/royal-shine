'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import api from '@/lib/api';
import AdminLayout from '@/components/admin/AdminLayout';
import { Card, CardHeader, CardContent, CardFooter } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
  BarChart, Bar, PieChart, Pie, ResponsiveContainer, Cell,
} from 'recharts';

const statItems = [
  { label: 'Total Products', key: 'total_products', icon: '📦' },
  { label: 'Categories', key: 'total_categories', icon: '🏷️' },
  { label: 'Collections', key: 'total_collections', icon: '🎀' },
  { label: 'Total Enquiries', key: 'total_enquiries', icon: '💬' },
  { label: 'New Enquiries', key: 'new_enquiries', icon: '🆕' },
  { label: 'Total Users', key: 'total_users', icon: '👥' },
];

const COLORS = ['#B68A4A', '#8F6A3D', '#D4A373', '#766B60', '#A77A3E', '#563D2B'];

export default function AdminHome() {
  const [data, setData] = useState<any>(null);
  const [error, setError] = useState('');

  useEffect(() => {
    if (!localStorage.getItem('royal_shine_token')) return;
    api.get('/dashboard/stats')
      .then((r) => setData(r.data))
      .catch((e) => {
        if (e.response?.status === 401) {
          localStorage.removeItem('royal_shine_token');
          window.location.href = '/admin/login';
        } else {
          setError('Unable to load dashboard data.');
        }
      });
  }, []);

  if (!data) {
    return (
      <AdminLayout>
        <div className="flex flex-col items-center justify-center py-24 text-center">
          <div className="w-16 h-16 rounded-full bg-champagne flex items-center justify-center mx-auto mb-6 text-gold text-2xl">
            ⏳
          </div>
          <h2 className="font-serif text-2xl text-charcoal mb-2">Loading dashboard…</h2>
          <p className="text-warm-gray">Please wait while we fetch your store statistics.</p>
        </div>
      </AdminLayout>
    );
  }

  const stats: any = data.stats ?? {};
  const enquiryByStatus: any[] = data.enquiry_by_status ?? [];
  const productsByCategory: any[] = data.products_by_category ?? [];
  const monthlyEnquiries: any[] = data.monthly_enquiries ?? [];
  const recentEnquiries: any[] = data.recent_enquiries ?? [];
  const recentProducts: any[] = data.recent_products ?? [];

  return (
    <AdminLayout>
      <div className="mb-8 flex items-center justify-between">
        <div>
          <p className="text-xs font-semibold text-gold uppercase tracking-wider">Overview</p>
          <h1 className="font-serif text-3xl text-charcoal">Your workspace.</h1>
        </div>
        <Link href="/admin/products" className="btn btn-gold btn-sm inline-flex items-center gap-2">
          Add Product <span>→</span>
        </Link>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {statItems.map((s) => {
          const value = stats[s.key] ?? 0;
          return (
            <Card key={s.key} padded={false}>
              <CardContent className="pt-6 pb-4 px-6">
                <div className="flex justify-between items-start">
                  <div>
                    <p className="text-xs font-medium text-warm-gray uppercase tracking-wider">{s.label}</p>
                    <p className="text-3xl font-serif font-bold text-charcoal mt-1">{value}</p>
                  </div>
                  <div className="w-10 h-10 rounded-xl bg-champagne flex items-center justify-center text-gold text-xl">
                    {s.icon}
                  </div>
                </div>
              </CardContent>
            </Card>
          );
        })}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <Card padded>
          <CardHeader title="Enquiries by Status" subtitle="Distribution of customer enquiries" />
          <CardContent className="h-64">
            <ResponsiveContainer width="100%" height="100%">
              <PieChart>
                <Pie
                  data={enquiryByStatus}
                  dataKey="count"
                  nameKey="status"
                  cx="50%"
                  cy="50%"
                  innerRadius={80}
                  outerRadius={100}
                  fill="#8884d8"
                  label
                >
                  {enquiryByStatus.map((entry, index) => (
                    <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                  ))}
                </Pie>
              </PieChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>

        <Card padded>
          <CardHeader title="Monthly Enquiries" subtitle="Customer enquiries over time" />
          <CardContent className="h-64">
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={monthlyEnquiries}>
                <Bar dataKey="count" fill="#B68A4A" radius={[4, 4, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <Card padded>
          <CardHeader title="Recent Enquiries" subtitle="Latest customer conversations" />
          <CardContent className="p-0">
            <div className="overflow-hidden">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="border-b border-beige bg-champagne">
                    <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Customer</th>
                    <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Mobile</th>
                    <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Status</th>
                    <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Date</th>
                  </tr>
                </thead>
                <tbody>
                  {recentEnquiries.length > 0 ? (
                    recentEnquiries.map((e: any) => (
                      <tr key={e.id} className="border-b border-beige last:border-0 hover:bg-champagne/50 transition-colors">
                        <td className="px-4 py-3 text-sm font-medium text-charcoal">{e.name}</td>
                        <td className="px-4 py-3 text-sm text-warm-gray">{e.mobile}</td>
                        <td className="px-4 py-3">
                          <Badge variant="outline" className="text-[10px]">
                            {e.status}
                          </Badge>
                        </td>
                        <td className="px-4 py-3 text-sm text-warm-gray">{new Date(e.created_at).toLocaleDateString()}</td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan={4} className="px-4 py-8 text-center text-warm-gray">No recent enquiries.</td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </CardContent>
          <CardFooter>
            <Link href="/admin/enquiries" className="text-sm text-gold font-medium hover:underline">
              View all enquiries →
            </Link>
          </CardFooter>
        </Card>

        <Card padded>
          <CardHeader title="Recent Products" subtitle="Latest catalogue additions" />
          <CardContent className="p-0">
            <div className="overflow-hidden">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="border-b border-beige bg-champagne">
                    <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Product</th>
                    <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Price</th>
                    <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Status</th>
                  </tr>
                </thead>
                <tbody>
                  {recentProducts.length > 0 ? (
                    recentProducts.map((p: any) => (
                      <tr key={p.id} className="border-b border-beige last:border-0 hover:bg-champagne/50 transition-colors">
                        <td className="px-4 py-3 text-sm font-medium text-charcoal truncate max-w-[200px]">{p.name}</td>
                        <td className="px-4 py-3 text-sm text-warm-gray">
                          {p.price_on_request ? 'Price on Request' : formatPrice(p.price)}
                        </td>
                        <td className="px-4 py-3">
                          <Badge variant="outline" className="text-[10px]">
                            {p.status}
                          </Badge>
                        </td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan={3} className="px-4 py-8 text-center text-warm-gray">No products yet.</td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </CardContent>
          <CardFooter>
            <Link href="/admin/products" className="text-sm text-gold font-medium hover:underline">
              View all products →
            </Link>
          </CardFooter>
        </Card>
      </div>

      {error && <div className="rounded-lg bg-red-50 border border-red-100 p-4 text-sm text-red-800">{error}</div>}
    </AdminLayout>
  );
}

function formatPrice(price: number | null | undefined): string {
  if (price == null) return 'Price on request';
  return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 }).format(price);
}
