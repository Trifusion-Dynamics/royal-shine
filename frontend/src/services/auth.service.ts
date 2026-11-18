import api from '../lib/api';

export const authService = {
  async login(credentials: { email: string; password: string }) {
    const res = await api.post('/auth/login', credentials);
    return res.data;
  },

  async getMe() {
    const res = await api.get('/auth/me');
    return res.data;
  },

  async changePassword(data: { currentPassword: string; newPassword: string }) {
    const res = await api.post('/auth/change-password', data);
    return res.data;
  },

  async updateProfile(data: { name?: string; email?: string }) {
    const res = await api.put('/auth/profile', data);
    return res.data;
  },
};
