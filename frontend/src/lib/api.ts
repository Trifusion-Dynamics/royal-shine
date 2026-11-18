import axios from 'axios';
import { getToken, removeToken } from './auth';

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:4000/api/v1',
  timeout: 15000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

export class ApiError extends Error {
  status?: number;
  message: string;

  constructor(message: string, status?: number) {
    super(message);
    this.name = 'ApiError';
    this.status = status;
    this.message = message;
  }
}

// Request interceptor — attach JWT token
api.interceptors.request.use(
  (config) => {
    const token = getToken();
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor — handle errors with clear messages
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (typeof window === 'undefined') return Promise.reject(error);

    // Network error — backend not reachable
    if (!error.response) {
      const msg = error.code === 'ECONNREFUSED'
        ? 'Backend server is not running. Please start the NestJS API on port 4000.'
        : 'Network error. Please check your internet connection and try again.';
      return Promise.reject(new ApiError(msg, 0));
    }

    // 401 Unauthorized — token expired or invalid
    if (error.response.status === 401) {
      removeToken();
      window.location.href = '/admin/login';
      return Promise.reject(new ApiError('Session expired. Please sign in again.', 401));
    }

    // 403 Forbidden
    if (error.response.status === 403) {
      return Promise.reject(new ApiError('Access denied. You do not have permission for this action.', 403));
    }

    // 404 Not found
    if (error.response.status === 404) {
      return Promise.reject(new ApiError('Requested resource not found.', 404));
    }

    // 500 Server error
    if (error.response.status >= 500) {
      return Promise.reject(new ApiError('Server error. Please try again later.', error.response.status));
    }

    // Default — use backend message if available
    const backendMsg = error.response?.data?.message || error.message || 'An unexpected error occurred.';
    return Promise.reject(new ApiError(backendMsg, error.response.status));
  }
);

export default api;
