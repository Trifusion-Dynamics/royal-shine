import api from '../lib/api';

export const galleriesService = {
  async getAll(includeInactive = false) {
    const res = await api.get('/galleries', { params: { all: includeInactive } });
    return res.data;
  },

  async getById(id: number) {
    const res = await api.get(`/galleries/${id}`);
    return res.data;
  },

  async create(data: any) {
    const res = await api.post('/galleries', data);
    return res.data;
  },

  async update(id: number, data: any) {
    const res = await api.put(`/galleries/${id}`, data);
    return res.data;
  },

  async delete(id: number) {
    const res = await api.delete(`/galleries/${id}`);
    return res.data;
  },
};
