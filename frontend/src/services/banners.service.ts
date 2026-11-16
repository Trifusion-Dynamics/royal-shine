import api from '../lib/api';

export const bannersService = {
  async getAll(includeAll = false) {
    const res = await api.get('/banners', { params: { all: includeAll } });
    return res.data;
  },

  async getById(id: number) {
    const res = await api.get(`/banners/${id}`);
    return res.data;
  },

  async create(data: any) {
    const res = await api.post('/banners', data);
    return res.data;
  },

  async update(id: number, data: any) {
    const res = await api.put(`/banners/${id}`, data);
    return res.data;
  },

  async delete(id: number) {
    const res = await api.delete(`/banners/${id}`);
    return res.data;
  },
};
