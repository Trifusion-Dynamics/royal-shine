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

interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  is_admin: boolean;
  created_at: string;
}

export default function AdminUsers() {
  const [users, setUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState(true);
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<User | null>(null);
  const [form, setForm] = useState({ name: '', email: '', password: '', role: 'content_manager' });

  useEffect(() => {
    api.get('/users')
      .then((r) => {
        const data = r.data.data || r.data || [];
        setUsers(Array.isArray(data) ? data : []);
      })
      .catch(() => toast.error('Failed to load users'))
      .finally(() => setLoading(false));
  }, []);

  const openCreate = () => { setEditing(null); setForm({ name: '', email: '', password: '', role: 'content_manager' }); setOpen(true); };
  const openEdit = (u: User) => { setEditing(u); setForm({ name: u.name, email: u.email, password: '', role: u.role }); setOpen(true); };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const payload = { name: form.name, email: form.email, role: form.role, ...(form.password ? { password: form.password } : {}) };
      if (editing) await api.put(`/users/${editing.id}`, payload);
      else await api.post('/users', payload);
      toast.success(editing ? 'User updated' : 'User created');
      setOpen(false);
      load();
    } catch (e: any) { toast.error(e.response?.data?.message || 'Failed to save user'); }
  };

  const handleDelete = async (id: number) => {
    if (!window.confirm('Delete this user?')) return;
    try {
      await api.delete(`/users/${id}`);
      toast.success('User deleted');
      load();
    } catch (e: any) { toast.error(e.response?.data?.message || 'Failed to delete user'); }
  };

  const load = () => api.get('/users').then((r) => {
    const data = r.data.data || r.data || [];
    setUsers(Array.isArray(data) ? data : []);
  });

  const roleOptions = ['super_admin', 'admin', 'content_manager', 'enquiry_manager'];

  return (
    <AdminLayout>
      <div className="mb-6 flex items-center justify-between">
        <div>
          <p className="text-xs font-semibold text-gold uppercase tracking-wider">Team</p>
          <h1 className="font-serif text-3xl text-charcoal">Users</h1>
        </div>
        <Button variant="gold" onClick={openCreate} className="inline-flex items-center gap-2">
          <PlusIcon className="w-4 h-4" /> Add User
        </Button>
      </div>

      <Card>
        <CardHeader title="Admin Users" subtitle="Manage team members and access roles" />
        <CardContent>
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="border-b border-beige bg-champagne">
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Name</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase">Email</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase text-center">Role</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase text-center">Admin</th>
                  <th className="px-4 py-3 text-xs font-semibold text-warm-gray uppercase text-right">Actions</th>
                </tr>
              </thead>
              <tbody>
                {loading ? (
                  <tr><td colSpan={5} className="px-4 py-12 text-center text-warm-gray">Loading…</td></tr>
                ) : users.length === 0 ? (
                  <tr><td colSpan={5} className="px-4 py-12 text-center text-warm-gray">No users found.</td></tr>
                ) : (
                  users.map((u) => (
                    <tr key={u.id} className="border-b border-beige last:border-0 hover:bg-champagne/50 transition-colors">
                      <td className="px-4 py-3 text-sm font-medium text-charcoal">{u.name}</td>
                      <td className="px-4 py-3 text-sm text-warm-gray">{u.email}</td>
                      <td className="px-4 py-3 text-center"><Badge variant="outline" className="text-[10px]">{u.role}</Badge></td>
                      <td className="px-4 py-3 text-center"><Badge variant="outline" className="text-[10px]">{u.is_admin ? 'Yes' : 'No'}</Badge></td>
                      <td className="px-4 py-3 text-right">
                        <Button variant="text" size="sm" onClick={() => openEdit(u)} className="text-gold hover:text-brown mr-3"><EditIcon className="w-4 h-4" /></Button>
                        <Button variant="text" size="sm" onClick={() => handleDelete(u.id)} className="text-red-600 hover:text-red-800"><Trash2Icon className="w-4 h-4" /></Button>
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
            <h2 className="font-serif text-2xl text-charcoal">{editing ? 'Edit User' : 'Add User'}</h2>
            <button onClick={() => setOpen(false)} className="text-charcoal hover:text-gold p-2"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="6" y1="18" x2="18" y2="6" /><line x1="6" y1="6" x2="12" y2="12" /></svg></button>
          </div>
          <form onSubmit={handleSubmit} className="space-y-5">
            <div className="space-y-2">
              <label className="text-xs font-semibold text-warm-gray uppercase">Full Name</label>
              <input required value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" />
            </div>
            <div className="space-y-2">
              <label className="text-xs font-semibold text-warm-gray uppercase">Email Address</label>
              <input required type="email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" />
            </div>
            {!editing && (
              <div className="space-y-2">
                <label className="text-xs font-semibold text-warm-gray uppercase">Password</label>
                <input required type="password" value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none" />
              </div>
            )}
            <div className="space-y-2">
              <label className="text-xs font-semibold text-warm-gray uppercase">Role</label>
              <select value={form.role} onChange={(e) => setForm({ ...form, role: e.target.value })} className="w-full px-4 py-3 rounded-lg border border-beige bg-white text-charcoal focus:border-gold focus:ring-1 focus:ring-gold outline-none">
                {roleOptions.map((r) => <option key={r} value={r}>{r.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase())}</option>)}
              </select>
            </div>
            <div className="pt-6 flex justify-end gap-3">
              <Button variant="outline" onClick={() => setOpen(false)}>Cancel</Button>
              <Button variant="gold" type="submit">{editing ? 'Update User' : 'Create User'}</Button>
            </div>
          </form>
        </div>
      </div>
    </AdminLayout>
  );
}
