import api from '../lib/api';

export const settingsService = {
  async getAll(): Promise<Record<string, string>> {
    const res = await api.get('/settings');
    return res.data;
  },

  async update(settings: Record<string, any>) {
    const res = await api.put('/settings', { settings });
    return res.data;
  },
};
