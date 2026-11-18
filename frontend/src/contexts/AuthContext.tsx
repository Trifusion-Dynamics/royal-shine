'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';
import { User } from '../types';
import { getToken, setToken, removeToken, getCurrentUser, setCurrentUser } from '../lib/auth';
import api from '../lib/api';
import { authService } from '../services/auth.service';

interface AuthContextType {
  user: User | null;
  loading: boolean;
  login: (token: string, user: User) => void;
  logout: () => void;
  changePassword: (data: { currentPassword: string; newPassword: string }) => Promise<void>;
  updateProfile: (data: { name?: string; email?: string }) => Promise<User>;
}

const AuthContext = createContext<AuthContextType>({
  user: null,
  loading: true,
  login: () => {},
  logout: () => {},
  changePassword: async () => {},
  updateProfile: async () => ({} as User),
});

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const token = getToken();
    const storedUser = getCurrentUser();
    if (token && storedUser) {
      setUser(storedUser);
      api.get('/auth/me')
        .then((res) => {
          setUser(res.data);
          setCurrentUser(res.data);
        })
        .catch(() => {
          removeToken();
          setUser(null);
        })
        .finally(() => setLoading(false));
    } else {
      setLoading(false);
    }
  }, []);

  const login = (token: string, userData: User) => {
    setToken(token);
    setCurrentUser(userData);
    setUser(userData);
  };

  const logout = () => {
    removeToken();
    setUser(null);
    if (typeof window !== 'undefined') {
      window.location.href = '/admin/login';
    }
  };

  const changePassword = async (data: { currentPassword: string; newPassword: string }) => {
    await authService.changePassword(data);
  };

  const updateProfile = async (data: { name?: string; email?: string }) => {
    const res = await authService.updateProfile(data);
    const updatedUser: User = res.data || res;
    setUser(updatedUser);
    setCurrentUser(updatedUser);
    return updatedUser;
  };

  return (
    <AuthContext.Provider value={{ user, loading, login, logout, changePassword, updateProfile }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
