import api from '../lib/api';

export const categoriesService = {
  async getAll(includeInactive = false) {
    const res = await api.get('/categories', { params: { all: includeInactive } });
    return res.data;
  },

  async getBySlug(slug: string) {
    const res = await api.get(`/categories/slug/${slug}`);
    return res.data;
  },

  async getById(id: number) {
    const res = await api.get(`/categories/${id}`);
    return res.data;
  },

  async create(data: any) {
    const res = await api.post('/categories', data);
    return res.data;
  },

  async update(id: number, data: any) {
    const res = await api.put(`/categories/${id}`, data);
    return res.data;
  },

  async reorder(ids: number[]) {
    const res = await api.put('/categories/reorder', { ids });
    return res.data;
  },

  async delete(id: number) {
    const res = await api.delete(`/categories/${id}`);
    return res.data;
  },
};
