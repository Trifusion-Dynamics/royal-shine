'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import api from '@/lib/api';
import AdminLayout from '@/components/admin/AdminLayout';
import { Card, CardHeader, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import toast from 'react-hot-toast';
import { Trash2Icon, EditIcon, PlusIcon } from 'lucide-react';

interface Collection {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  status: string;
  is_featured: boolean;
  products_count?: number;
  created_at: string;
}

export default function AdminCollections() {
  const [collections, setCollections] = useState<Collection[]>([]);
  const [loading, setLoading] = useState(true);
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Collection | null>(null);
  const [form, setForm] = useState({ name: '', slug: '', description: '', status: 'active', is_featured: false });

  useEffect(() => {
    api.get('/collections?all=true')
      .then((r) => {
        const data = r.data.data || r.data || [];
        setCollections(Array.isArray(data) ? data : []);
      })
      .catch(() => toast.error('Failed to load collections'))
      .finally(() => setLoading(false));
  }, []);

  const openCreate = () => { setEditing(null); setForm({ name: '', slug: '', description: '', status: 'active', is_featured: false }); setOpen(true); };
  const openEdit = (c: Collection) => { setEditing(c); setForm({ name: c.name, slug: c.slug, description: c.description || '', status: c.status, is_featured: !!c.is_featured }); setOpen(true); };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      if (editing) await api.put(`/collections/${editing.id}`, form);
      else await api.post('/collections', form);
      toast.success(editing ? 'Collection updated' : 'Collection created');
      setOpen(false);
      load();
    } catch (e: any) { toast.error(e.response?.data?.message || 'Failed to save collection'); }
  };

  const handleDelete = async (id: number) => {
    if (!window.confirm('Delete this collection?')) return;
    try {
      await api.delete(`/collections/${id}`);
      toast.success('Collection deleted');
      load();
    } catch (e: any) { toast.error(e.response?.data?.message || 'Failed to delete collection'); }
  };

  const load = () => api.get('/collections?all=true').then((r) => {
    const data = r.data.data || r.data || [];
    setCollections(Array.isArray(data) ? data : []);
  });

  return (
    <AdminLayout>
      <div className="mb-6 flex items-center justify-between">
        <div>
          <p className="text-xs font-semibold text-gold uppercase tracking-wider">Catalogue</p>
          <h1 className="font-serif text-3xl text-charcoal">Collections</h1>
        </div>
        <Button variant="gold" onClick={openCreate} className="inline-flex items-center gap-2">
          <PlusIcon className="w-4 h-4" /> Add Collection
        </Button>
      </div>

      <Card>
        <CardHeader title="Collection List" subtitle="Curated jewellery collections" />
        <CardContent>
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="border-b border-beige bg-champagne">
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Collection</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Slug</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase text-center">Featured</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase text-center">Status</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase text-right">Actions</th>
                </tr>
              </thead>
              <tbody>
                {loading ? (
                  <tr><td colSpan={5} className="px-4 py-12 text-center text-warm-gray">Loading…</td></tr>
                ) : collections.length === 0 ? (
                  <tr><td colSpan={5} className="px-4 py-12 text-center text-warm-gray">No collections found.</td></tr>
                ) : (
                  collections.map((c) => (
                    <tr key={c.id} className="border-b border-beige last:border-0 hover:bg-champagne/50 transition-colors">
                      <td className="px-4 py-3 text-sm font-medium text-charcoal">{c.name}</td>
                      <td className="px-4 py-3 text-sm text-warm-gray">{c.slug}</td>
                      <td className="px-4 py-3 text-center"><Badge variant="outline" className="text-[10px]">{c.is_featured ? 'Yes' : 'No'}</Badge></td>
                      <td className="px-4 py-3 text-center"><Badge variant="outline" className="text-[10px]">{c.status}</Badge></td>
                      <td className="px-4 py-3 text-right">
                        <Button variant="text" size="sm" onClick={() => openEdit(c)} className="text-gold hover:text-brown mr-3"><EditIcon className="w-4 h-4" /></Button>
                        <Button variant="text" size="sm" onClick={() => handleDelete(c.id)} className="text-red-600 hover:text-red-800"><Trash2Icon className="w-4 h-4" /></Button>
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
            <h2 className="font-serif text-2xl text-charcoal">{editing ? 'Edit Collection' : 'Add Collection'}</h2>
            <button onClick={() => setOpen(false)} className="text-charcoal hover:text-gold p-2"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="6" y1="18" x2="18" y2="6" /><line x1="6" y1="6" x2="12" y2="12" /></svg></button>
          </div>
          <form onSubmit={handleSubmit} className="space-y-5">
            <div className="space-y-2">
              <label className="text-xs font-semibold text-warm-gray uppercase">Collection Name</label>
              <input required value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" />
            </div>
            <div className="space-y-2">
              <label className="text-xs font-semibold text-warm-gray uppercase">Slug</label>
              <input required value={form.slug} onChange={(e) => setForm({ ...form, slug: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" placeholder="e.g. bridal-collection" />
            </div>
            <div className="space-y-2">
              <label className="text-xs font-semibold text-warm-gray uppercase">Description</label>
              <textarea rows={3} value={form.description} onChange={(e) => setForm({ ...form, description: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" />
            </div>
            <div className="space-y-2">
              <label className="text-xs font-semibold text-warm-gray uppercase">Status</label>
              <select value={form.status} onChange={(e) => setForm({ ...form, status: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <div className="space-y-2">
              <label className="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" checked={form.is_featured} onChange={(e) => setForm({ ...form, is_featured: e.target.checked })} className="rounded border-beige text-gold focus:ring-gold" />
                Featured Collection
              </label>
            </div>
            <div className="pt-6 flex justify-end gap-3">
              <Button variant="outline" onClick={() => setOpen(false)}>Cancel</Button>
              <Button variant="gold" type="submit">{editing ? 'Update Collection' : 'Create Collection'}</Button>
            </div>
          </form>
        </div>
      </div>
    </AdminLayout>
  );
}
