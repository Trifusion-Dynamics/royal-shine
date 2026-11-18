'use client';
import { FormEvent, useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { Mail, Lock, Eye, EyeOff, ArrowRight } from 'lucide-react';
import api from '../../../lib/api';
import { setToken, setStoredUser } from '../../../lib/auth';

export default function AdminLogin() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [busy, setBusy] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const router = useRouter();

  async function submit(e: FormEvent) {
    e.preventDefault();
    setBusy(true);
    setError('');
    try {
      const { data } = await api.post('/auth/login', { email, password });
      const token = data.access_token || data.accessToken;
      if (!token) throw new Error('Authentication response was incomplete');
      setToken(token);
      setStoredUser(data.user);
      router.push('/admin');
    } catch (err: any) {
      let msg = err?.response?.data?.message || 'Unable to sign in. Check your credentials and try again.';
      if (err?.status === 0 || err?.message?.includes('Backend server') || err?.message?.includes('Network error')) {
        msg = err.message || 'Unable to connect to the server. Please make sure the backend API is running.';
      }
      setError(msg);
    } finally {
      setBusy(false);
    }
  }

  return (
    <main className="min-h-screen w-full bg-cream">
      <div className="flex flex-col lg:flex-row h-[calc(100vh-rem(4rem))]">
        {/* Left: Branding */}
        <section className="flex-1 flex flex-col justify-center px-6 py-12 lg:px-20 lg:py-24 bg-champagne border-r border-beige">
          <Link href="/" className="flex items-center gap-2 mb-8">
            <span className="font-serif text-3xl text-gold tracking-tight">ROYAL</span>
            <span className="font-serif text-3xl text-charcoal tracking-tight">SHINE</span>
          </Link>
          <p className="text-xs font-semibold text-gold uppercase tracking-widest mb-6">ADMINISTRATION</p>
          <h1 className="font-serif text-5xl lg:text-6xl text-charcoal leading-tight mb-6">
            Welcome<br />
            <em className="text-gold">back.</em>
          </h1>
          <p className="text-warm-gray text-lg leading-relaxed max-w-md mb-10">
            Sign in to manage your jewellery catalogue, customer enquiries, and website content.
          </p>
          <div className="flex gap-4 text-sm text-warm-gray">
            <div className="flex items-center gap-2">
              <span className="w-2 h-2 rounded-full bg-gold" />
              Secure portal
            </div>
            <div className="flex items-center gap-2">
              <span className="w-2 h-2 rounded-full bg-gold" />
              Jewellery catalogue
            </div>
          </div>
        </section>

        {/* Right: Login Form */}
        <section className="flex-1 flex items-center justify-center bg-white p-6 lg:p-0">
          <form onSubmit={submit} className="w-full max-w-md space-y-6">
            <div className="text-center">
              <p className="text-xs font-semibold text-gold uppercase tracking-widest">Secure Sign In</p>
              <h2 className="font-serif text-3xl text-charcoal">Admin portal</h2>
            </div>

            {error && (
              <div className="rounded-lg bg-red-50 border border-red-100 p-4 text-sm text-red-800 flex items-center gap-3">
                <span className="w-5 h-5 text-red-600">!</span>
                {error}
              </div>
            )}

            <div className="space-y-4">
              <label className="block text-sm font-medium text-charcoal">Email address</label>
              <div className="relative">
                <Mail className="absolute left-3 top-1/2 -translate-y-1/2 text-warm-gray w-4 h-4" />
                <input
                  required
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  autoComplete="username"
                  className="w-full pl-10 pr-4 py-4 bg-champagne/50 border border-beige rounded-xl text-charcoal focus:ring-2 focus:ring-gold focus:border-gold outline-none transition-all"
                  placeholder="admin@royalshine.in"
                />
              </div>

              <label className="block text-sm font-medium text-charcoal">Password</label>
              <div className="relative">
                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 text-warm-gray w-4 h-4" />
                <input
                  required
                  type={showPassword ? 'text' : 'password'}
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  autoComplete="current-password"
                  className="w-full pl-10 pr-10 py-4 bg-champagne/50 border border-beige rounded-xl text-charcoal focus:ring-2 focus:ring-gold focus:border-gold outline-none transition-all"
                  placeholder="••••••••"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-3 top-1/2 -translate-y-1/2 text-warm-gray hover:text-gold"
                >
                  {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                </button>
              </div>
            </div>

            <button
              type="submit"
              disabled={busy}
              className="w-full flex items-center justify-center gap-2 py-4 bg-gold text-white rounded-xl font-medium hover:bg-gold-dark transition-all disabled:opacity-60 disabled:cursor-not-allowed"
            >
              {busy ? 'Signing in…' : 'Sign in'}
              <ArrowRight className="w-4 h-4" />
            </button>

            <p className="text-xs text-center text-warm-gray">
              Default credentials: <br />
              <span className="text-gold font-medium">admin@royalshine.in</span> / <span className="text-gold font-medium">RoyalShine@2024</span>
            </p>
          </form>
        </section>
      </div>
    </main>
  );
}
