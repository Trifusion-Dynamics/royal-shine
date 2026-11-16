import Cookies from 'js-cookie';

const TOKEN_KEY = 'royal_shine_token';
const USER_KEY = 'royal_shine_user';

export function getToken(): string | null {
  if (typeof window === 'undefined') return null;
  return Cookies.get(TOKEN_KEY) || localStorage.getItem(TOKEN_KEY) || null;
}

export function setToken(token: string): void {
  if (typeof window === 'undefined') return;
  Cookies.set(TOKEN_KEY, token, { expires: 7, sameSite: 'lax' });
  localStorage.setItem(TOKEN_KEY, token);
}

export function removeToken(): void {
  if (typeof window === 'undefined') return;
  Cookies.remove(TOKEN_KEY);
  localStorage.removeItem(TOKEN_KEY);
  localStorage.removeItem(USER_KEY);
}

export function isAuthenticated(): boolean {
  return !!getToken();
}

export function getStoredUser() {
  if (typeof window === 'undefined') return null;
  const raw = localStorage.getItem(USER_KEY);
  if (!raw) return null;
  try {
    return JSON.parse(raw);
  } catch {
    return null;
  }
}

export function setStoredUser(user: object): void {
  if (typeof window === 'undefined') return;
  localStorage.setItem(USER_KEY, JSON.stringify(user));
}

export const getCurrentUser = getStoredUser;
export const setCurrentUser = setStoredUser;
