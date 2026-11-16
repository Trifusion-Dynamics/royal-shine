import api from '../lib/api';

export const testimonialsService = {
  async getAll(includeUnapproved = false) {
    const res = await api.get('/testimonials', { params: { all: includeUnapproved } });
    return res.data;
  },

  async getById(id: number) {
    const res = await api.get(`/testimonials/${id}`);
    return res.data;
  },

  async create(data: any) {
    const res = await api.post('/testimonials', data);
    return res.data;
  },

  async update(id: number, data: any) {
    const res = await api.put(`/testimonials/${id}`, data);
    return res.data;
  },

  async delete(id: number) {
    const res = await api.delete(`/testimonials/${id}`);
    return res.data;
  },
};
