import api from '../lib/api';

export const collectionsService = {
  async getAll(includeInactive = false) {
    const res = await api.get('/collections', { params: { all: includeInactive } });
    return res.data;
  },

  async getBySlug(slug: string) {
    const res = await api.get(`/collections/slug/${slug}`);
    return res.data;
  },

  async getById(id: number) {
    const res = await api.get(`/collections/${id}`);
    return res.data;
  },

  async create(data: any) {
    const res = await api.post('/collections', data);
    return res.data;
  },

  async update(id: number, data: any) {
    const res = await api.put(`/collections/${id}`, data);
    return res.data;
  },

  async delete(id: number) {
    const res = await api.delete(`/collections/${id}`);
    return res.data;
  },
};
