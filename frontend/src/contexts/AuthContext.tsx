'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';
import { User } from '../types';
import { getToken, setToken, removeToken, getCurrentUser, setCurrentUser } from '../lib/auth';
import api from '../lib/api';

interface AuthContextType {
  user: User | null;
  loading: boolean;
  login: (token: string, user: User) => void;
  logout: () => void;
}

const AuthContext = createContext<AuthContextType>({
  user: null,
  loading: true,
  login: () => {},
  logout: () => {},
});

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const token = getToken();
    const storedUser = getCurrentUser();
    if (token && storedUser) {
      setUser(storedUser);
      // Verify token with backend
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

  return (
    <AuthContext.Provider value={{ user, loading, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
