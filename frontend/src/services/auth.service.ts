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
};
