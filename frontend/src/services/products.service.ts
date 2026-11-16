import api from '../lib/api';

export interface ProductFilters {
  page?: number;
  limit?: number;
  search?: string;
  categoryId?: number;
  categorySlug?: string;
  collectionId?: number;
  collectionSlug?: string;
  metalType?: string;
  goldPurity?: string;
  minPrice?: number;
  maxPrice?: number;
  status?: string;
  isFeatured?: boolean;
  sortBy?: string;
  sortOrder?: 'asc' | 'desc';
}

export const productsService = {
  async getAll(params?: ProductFilters) {
    const res = await api.get('/products', { params });
    return res.data;
  },

  async getFeatured() {
    const res = await api.get('/products/featured');
    return res.data;
  },

  async getNewArrivals() {
    const res = await api.get('/products/new-arrivals');
    return res.data;
  },

  async getBySlug(slug: string) {
    const res = await api.get(`/products/${slug}`);
    return res.data;
  },

  async getById(id: number) {
    const res = await api.get(`/products/id/${id}`);
    return res.data;
  },

  async create(data: any) {
    const res = await api.post('/products', data);
    return res.data;
  },

  async update(id: number, data: any) {
    const res = await api.put(`/products/${id}`, data);
    return res.data;
  },

  async delete(id: number) {
    const res = await api.delete(`/products/${id}`);
    return res.data;
  },
};
