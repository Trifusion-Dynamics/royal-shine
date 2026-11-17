'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import api from '@/lib/api';
import AdminLayout from '@/components/admin/AdminLayout';
import { Card, CardHeader, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import toast from 'react-hot-toast';
import { formatPrice } from '@/lib/utils';
import { Trash2Icon, EditIcon, PlusIcon } from 'lucide-react';

const statusOptions = ['draft', 'published', 'inactive'] as const;
const metalOptions = ['gold', 'silver', 'artificial', 'other'] as const;
const purityOptions = ['9K', '14K', '18K', '22K'] as const;

interface Product {
  id: number;
  name: string;
  slug: string;
  sku: string;
  price: number | null;
  price_on_request: boolean;
  status: string;
  category?: { name: string } | null;
  images?: Array<{ image: string }>;
  created_at: string;
}

export default function AdminProducts() {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Product | null>(null);
  const [form, setForm] = useState({
    name: '',
    slug: '',
    sku: '',
    category_id: '',
    metal_type: '',
    gold_purity: '',
    weight: '',
    weight_unit: '',
    stone_details: '',
    price: '',
    price_on_request: false,
    status: 'draft' as string,
    short_description: '',
    description: '',
  });

  useEffect(() => {
    fetchProducts();
  }, []);

  const fetchProducts = async () => {
    try {
      const res = await api.get('/products?limit=100');
      const data = res.data.data || res.data || [];
      setProducts(Array.isArray(data) ? data : []);
    } catch (e: any) {
      toast.error(e.response?.data?.message || 'Failed to load products');
    } finally {
      setLoading(false);
    }
  };

  const openCreate = () => {
    setEditing(null);
    setForm({
      name: '', slug: '', sku: '', category_id: '', metal_type: '', gold_purity: '',
      weight: '', weight_unit: '', stone_details: '', price: '', price_on_request: false,
      status: 'draft', short_description: '', description: '',
    });
    setOpen(true);
  };

  const openEdit = (p: Product) => {
    setEditing(p);
    setForm({
      name: p.name, slug: p.slug, sku: p.sku, category_id: String(p.category?.name ? 0 : ''),
      metal_type: '', gold_purity: '', weight: '', weight_unit: '', stone_details: '',
      price: String(p.price ?? ''), price_on_request: !!p.price_on_request, status: p.status,
      short_description: '', description: '',
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      if (editing) {
        await api.put(`/products/${editing.id}`, form);
        toast.success('Product updated');
      } else {
        await api.post('/products', form);
        toast.success('Product created');
      }
      setOpen(false);
      fetchProducts();
    } catch (e: any) {
      toast.error(e.response?.data?.message || 'Failed to save product');
    }
  };

  const handleDelete = async (id: number) => {
    if (!window.confirm('Delete this product?')) return;
    try {
      await api.delete(`/products/${id}`);
      toast.success('Product deleted');
      fetchProducts();
    } catch (e: any) {
      toast.error(e.response?.data?.message || 'Failed to delete product');
    }
  };

  return (
    <AdminLayout>
      <div className="mb-6 flex items-center justify-between">
        <div>
          <p className="text-xs font-semibold text-gold uppercase tracking-wider">Catalogue</p>
          <h1 className="font-serif text-3xl text-charcoal">Products</h1>
        </div>
        <Button variant="gold" onClick={openCreate} className="inline-flex items-center gap-2">
          <PlusIcon className="w-4 h-4" /> Add Product
        </Button>
      </div>

      <Card>
        <CardHeader title="Product List" subtitle="Manage your jewellery catalogue" />
        <CardContent>
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="border-b border-beige bg-champagne">
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Product</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">SKU</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Category</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase text-right">Price</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Status</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase text-right">Actions</th>
                </tr>
              </thead>
              <tbody>
                {loading ? (
                  <tr><td colSpan={6} className="px-4 py-12 text-center text-warm-gray">Loading…</td></tr>
                ) : products.length === 0 ? (
                  <tr><td colSpan={6} className="px-4 py-12 text-center text-warm-gray">No products found.</td></tr>
                ) : (
                  products.map((p) => (
                    <tr key={p.id} className="border-b border-beige last:border-0 hover:bg-champagne/50 transition-colors">
                      <td className="px-4 py-3 text-sm font-medium text-charcoal truncate max-w-[200px]">{p.name}</td>
                      <td className="px-4 py-3 text-sm text-warm-gray">{p.sku}</td>
                      <td className="px-4 py-3 text-sm text-warm-gray">{p.category?.name || '—'}</td>
                      <td className="px-4 py-3 text-sm text-right font-medium text-charcoal">
                        {p.price_on_request ? 'Price on Request' : formatPrice(p.price)}
                      </td>
                      <td className="px-4 py-3">
                        <Badge variant="outline" className="text-[10px]">{p.status}</Badge>
                      </td>
                      <td className="px-4 py-3 text-right">
                        <Button variant="text" size="sm" onClick={() => openEdit(p)} className="text-gold hover:text-brown mr-3">
                          <EditIcon className="w-4 h-4" />
                        </Button>
                        <Button variant="text" size="sm" onClick={() => handleDelete(p.id)} className="text-red-600 hover:text-red-800">
                          <Trash2Icon className="w-4 h-4" />
                        </Button>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </CardContent>
      </Card>

      {/* Product Form Modal */}
      <div className={`fixed inset-0 z-50 ${open ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none invisible transition-opacity duration-300'}`}>
        <div className="fixed inset-0 bg-black/40 backdrop-blur-sm" onClick={() => setOpen(false)} />
        <div className="fixed left-[50%] top-[50%] -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl bg-white rounded-2xl border border-beige shadow-2xl p-8 max-h-[90vh] overflow-y-auto">
          <div className="flex items-center justify-between mb-6">
            <h2 className="font-serif text-2xl text-charcoal">{editing ? 'Edit Product' : 'Add Product'}</h2>
            <button onClick={() => setOpen(false)} className="text-charcoal hover:text-gold p-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <line x1="6" y1="18" x2="18" y2="6" />
                <line x1="6" y1="6" x2="12" y2="12" />
              </svg>
            </button>
          </div>
          <form onSubmit={handleSubmit} className="space-y-5">
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div className="space-y-2">
                <label className="text-xs font-semibold text-warm-gray uppercase">Product Name</label>
                <input required value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" />
              </div>
              <div className="space-y-2">
                <label className="text-xs font-semibold text-warm-gray uppercase">SKU</label>
                <input required value={form.sku} onChange={(e) => setForm({ ...form, sku: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" />
              </div>
              <div className="space-y-2 sm:col-span-2">
                <label className="text-xs font-semibold text-warm-gray uppercase">Slug</label>
                <input required value={form.slug} onChange={(e) => setForm({ ...form, slug: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" placeholder="e.g. gold-necklace-set" />
              </div>
              <div className="space-y-2">
                <label className="text-xs font-semibold text-warm-gray uppercase">Category ID</label>
                <input value={form.category_id} onChange={(e) => setForm({ ...form, category_id: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" placeholder="numeric category id" />
              </div>
              <div className="space-y-2">
                <label className="text-xs font-semibold text-warm-gray uppercase">Metal Type</label>
                <select value={form.metal_type} onChange={(e) => setForm({ ...form, metal_type: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none">
                  <option value="">Select</option>
                  {metalOptions.map((m) => <option key={m} value={m}>{m.charAt(0).toUpperCase() + m.slice(1)}</option>)}
                </select>
              </div>
              <div className="space-y-2">
                <label className="text-xs font-semibold text-warm-gray uppercase">Gold Purity</label>
                <select value={form.gold_purity} onChange={(e) => setForm({ ...form, gold_purity: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none">
                  <option value="">Select</option>
                  {purityOptions.map((p) => <option key={p} value={p}>{p}</option>)}
                </select>
              </div>
              <div className="space-y-2">
                <label className="text-xs font-semibold text-warm-gray uppercase">Price (INR)</label>
                <input type="number" value={form.price} onChange={(e) => setForm({ ...form, price: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" />
              </div>
              <div className="space-y-2">
                <label className="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" checked={form.price_on_request} onChange={(e) => setForm({ ...form, price_on_request: e.target.checked })} className="rounded border-beige text-gold focus:ring-gold" />
                  Price on Request
                </label>
              </div>
              <div className="space-y-2">
                <label className="text-xs font-semibold text-warm-gray uppercase">Status</label>
                <select value={form.status} onChange={(e) => setForm({ ...form, status: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none">
                  {statusOptions.map((s) => <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1)}</option>)}
                </select>
              </div>
            </div>
            <div className="space-y-2 sm:col-span-2">
              <label className="text-xs font-semibold text-warm-gray uppercase">Short Description</label>
              <textarea rows={3} value={form.short_description} onChange={(e) => setForm({ ...form, short_description: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" />
            </div>
            <div className="space-y-2 sm:col-span-2">
              <label className="text-xs font-semibold text-warm-gray uppercase">Description</label>
              <textarea rows={4} value={form.description} onChange={(e) => setForm({ ...form, description: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" />
            </div>
            <div className="pt-6 flex justify-end gap-3">
              <Button variant="outline" onClick={() => setOpen(false)}>Cancel</Button>
              <Button variant="gold" type="submit">{editing ? 'Update Product' : 'Create Product'}</Button>
            </div>
          </form>
        </div>
      </div>
    </AdminLayout>
  );
}
