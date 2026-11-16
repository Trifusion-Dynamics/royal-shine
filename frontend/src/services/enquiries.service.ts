import api from '../lib/api';

export const enquiriesService = {
  async submit(data: {
    name: string;
    mobile: string;
    email?: string;
    message: string;
    productId?: number;
    productReference?: string;
    subject?: string;
    enquiryType?: 'product' | 'contact';
    website?: string;
  }) {
    const res = await api.post('/enquiries', data);
    return res.data;
  },

  async getAll(params?: {
    status?: string;
    type?: string;
    search?: string;
    page?: number;
    limit?: number;
  }) {
    const res = await api.get('/enquiries', { params });
    return res.data;
  },

  async getById(id: number) {
    const res = await api.get(`/enquiries/${id}`);
    return res.data;
  },

  async update(id: number, data: any) {
    const res = await api.put(`/enquiries/${id}`, data);
    return res.data;
  },

  async delete(id: number) {
    const res = await api.delete(`/enquiries/${id}`);
    return res.data;
  },

  async getCustomers(search?: string) {
    const res = await api.get('/enquiries/customers', { params: { search } });
    return res.data;
  },

  async getCustomerHistory(mobile: string) {
    const res = await api.get(`/enquiries/customers/${mobile}/history`);
    return res.data;
  },
};
