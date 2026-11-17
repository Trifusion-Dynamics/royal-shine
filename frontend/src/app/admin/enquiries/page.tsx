'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import api from '@/lib/api';
import AdminLayout from '@/components/admin/AdminLayout';
import { Card, CardHeader, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import toast from 'react-hot-toast';
import { Trash2Icon, EditIcon } from 'lucide-react';

interface Enquiry {
  id: number;
  name: string;
  mobile: string;
  email: string | null;
  subject: string;
  message: string;
  enquiry_type: string;
  status: string;
  product_reference: string | null;
  product?: { name: string } | null;
  created_at: string;
}

const statusOptions = ['new', 'contacted', 'in_progress', 'converted', 'closed'] as const;

export default function AdminEnquiries() {
  const [enquiries, setEnquiries] = useState<Enquiry[]>([]);
  const [loading, setLoading] = useState(true);
  const [filterStatus, setFilterStatus] = useState<string>('');
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Enquiry | null>(null);
  const [form, setForm] = useState({ status: '', internal_notes: '' });

  useEffect(() => {
    load();
  }, [filterStatus]);

  const load = async () => {
    try {
      const res = await api.get('/enquiries', { params: { status: filterStatus || undefined, page: 1, limit: 100 } });
      const data = res.data.data || res.data || [];
      setEnquiries(Array.isArray(data) ? data : []);
    } catch (e: any) {
      toast.error(e.response?.data?.message || 'Failed to load enquiries');
    } finally {
      setLoading(false);
    }
  };

  const handleUpdate = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!editing) return;
    try {
      await api.put(`/enquiries/${editing.id}`, form);
      toast.success('Enquiry updated');
      setOpen(false);
      load();
    } catch (e: any) {
      toast.error(e.response?.data?.message || 'Failed to update enquiry');
    }
  };

  const handleDelete = async (id: number) => {
    if (!window.confirm('Delete this enquiry?')) return;
    try {
      await api.delete(`/enquiries/${id}`);
      toast.success('Enquiry deleted');
      load();
    } catch (e: any) {
      toast.error(e.response?.data?.message || 'Failed to delete enquiry');
    }
  };

  return (
    <AdminLayout>
      <div className="mb-6 flex items-center justify-between">
        <div>
          <p className="text-xs font-semibold text-gold uppercase tracking-wider">Customers</p>
          <h1 className="font-serif text-3xl text-charcoal">Enquiries</h1>
        </div>
      </div>

      <div className="mb-6 flex gap-3">
        <select value={filterStatus} onChange={(e) => setFilterStatus(e.target.value)} className="px-4 py-3 rounded-lg border border-beige bg-white text-sm text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none">
          <option value="">All statuses</option>
          {statusOptions.map((s) => <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1)}</option>)}
        </select>
      </div>

      <Card>
        <CardHeader title="Enquiry List" subtitle="Customer conversations and product enquiries" />
        <CardContent>
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="border-b border-beige bg-champagne">
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Customer</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Mobile</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Subject</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase text-center">Type</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase text-center">Status</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase text-right">Actions</th>
                </tr>
              </thead>
              <tbody>
                {loading ? (
                  <tr><td colSpan={6} className="px-4 py-12 text-center text-warm-gray">Loading…</td></tr>
                ) : enquiries.length === 0 ? (
                  <tr><td colSpan={6} className="px-4 py-12 text-center text-warm-gray">No enquiries found.</td></tr>
                ) : (
                  enquiries.map((e) => (
                    <tr key={e.id} className="border-b border-beige last:border-0 hover:bg-champagne/50 transition-colors">
                      <td className="px-4 py-3 text-sm font-medium text-charcoal">{e.name}</td>
                      <td className="px-4 py-3 text-sm text-warm-gray">{e.mobile}</td>
                      <td className="px-4 py-3 text-sm text-warm-gray truncate max-w-[200px]">{e.subject}</td>
                      <td className="px-4 py-3 text-center"><Badge variant="outline" className="text-[10px]">{e.enquiry_type}</Badge></td>
                      <td className="px-4 py-3 text-center"><Badge variant="outline" className="text-[10px]">{e.status}</Badge></td>
                      <td className="px-4 py-3 text-right">
                        <Button variant="text" size="sm" onClick={() => { setEditing(e); setForm({ status: e.status, internal_notes: '' }); setOpen(true); }} className="text-gold hover:text-brown"><EditIcon className="w-4 h-4" /></Button>
                        <Button variant="text" size="sm" onClick={() => handleDelete(e.id)} className="text-red-600 hover:text-red-800 ml-2"><Trash2Icon className="w-4 h-4" /></Button>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </CardContent>
      </Card>

      <div className={`fixed inset-0 z-50 ${open ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none invisible transition-opacity duration-300'}`}>
        <div className="fixed inset-0 bg-black/40 backdrop-blur-sm" onClick={() => setOpen(false)} />
        <div className="fixed left-[50%] top-[50%] -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl border border-beige shadow-2xl p-8 max-h-[90vh] overflow-y-auto">
          <div className="flex items-center justify-between mb-6">
            <h2 className="font-serif text-2xl text-charcoal">Update Enquiry</h2>
            <button onClick={() => setOpen(false)} className="text-charcoal hover:text-gold p-2"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="6" y1="18" x2="18" y2="6" /><line x1="6" y1="6" x2="12" y2="12" /></svg></button>
          </div>
          <form onSubmit={handleUpdate} className="space-y-5">
            <div className="space-y-2">
              <label className="text-xs font-semibold text-warm-gray uppercase">Status</label>
              <select required value={form.status} onChange={(e) => setForm({ ...form, status: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none">
                {statusOptions.map((s) => <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1)}</option>)}
              </select>
            </div>
            <div className="space-y-2">
              <label className="text-xs font-semibold text-warm-gray uppercase">Internal Notes</label>
              <textarea rows={3} value={form.internal_notes} onChange={(e) => setForm({ ...form, internal_notes: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" placeholder="Add internal notes..." />
            </div>
            <div className="pt-6 flex justify-end gap-3">
              <Button variant="outline" onClick={() => setOpen(false)}>Cancel</Button>
              <Button variant="gold" type="submit">Update Enquiry</Button>
            </div>
          </form>
        </div>
      </div>
    </AdminLayout>
  );
}
