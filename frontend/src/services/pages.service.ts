import api from '../lib/api';

export const pagesService = {
  async getAll() {
    const res = await api.get('/pages');
    return res.data;
  },

  async getBySlug(slug: string) {
    const res = await api.get(`/pages/slug/${slug}`);
    return res.data;
  },

  async getById(id: number) {
    const res = await api.get(`/pages/${id}`);
    return res.data;
  },

  async create(data: any) {
    const res = await api.post('/pages', data);
    return res.data;
  },

  async update(id: number, data: any) {
    const res = await api.put(`/pages/${id}`, data);
    return res.data;
  },

  async delete(id: number) {
    const res = await api.delete(`/pages/${id}`);
    return res.data;
  },
};
