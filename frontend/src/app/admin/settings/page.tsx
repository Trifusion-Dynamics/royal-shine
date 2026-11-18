'use client';
import { useState } from 'react';
import Link from 'next/link';
import { useRouter, usePathname } from 'next/navigation';
import { Mail, Lock, User, Save, AlertCircle, CheckCircle, Eye, EyeOff } from 'lucide-react';
import { useAuth } from '@/contexts/AuthContext';

export default function AdminSettings() {
  const { user, changePassword, updateProfile, loading } = useAuth();
  const router = useRouter();
  const pathname = usePathname();

  const [activeTab, setActiveTab] = useState<'profile' | 'password'>('profile');
  const [profileBusy, setProfileBusy] = useState(false);
  const [passwordBusy, setPasswordBusy] = useState(false);
  const [profileMsg, setProfileMsg] = useState<{ text: string; type: 'success' | 'error' } | null>(null);
  const [passwordMsg, setPasswordMsg] = useState<{ text: string; type: 'success' | 'error' } | null>(null);

  // Profile form state
  const [profileName, setProfileName] = useState(user?.name || '');
  const [profileEmail, setProfileEmail] = useState(user?.email || '');

  // Password form state
  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [showCurrent, setShowCurrent] = useState(false);
  const [showNew, setShowNew] = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);

  async function handleProfile(e: React.FormEvent) {
    e.preventDefault();
    setProfileBusy(true);
    setProfileMsg(null);
    try {
      const data: { name?: string; email?: string } = {};
      if (profileName && profileName !== user?.name) data.name = profileName;
      if (profileEmail && profileEmail !== user?.email) data.email = profileEmail;

      if (Object.keys(data).length === 0) {
        setProfileMsg({ text: 'No changes detected.', type: 'error' });
        return;
      }

      await updateProfile(data);
      setProfileMsg({ text: 'Profile updated successfully.', type: 'success' });
    } catch (err: any) {
      setProfileMsg({
        text: err?.response?.data?.message || 'Failed to update profile.',
        type: 'error',
      });
    } finally {
      setProfileBusy(false);
    }
  }

  async function handlePassword(e: React.FormEvent) {
    e.preventDefault();
    setPasswordBusy(true);
    setPasswordMsg(null);
    if (newPassword !== confirmPassword) {
      setPasswordMsg({ text: 'New password and confirmation do not match.', type: 'error' });
      setPasswordBusy(false);
      return;
    }
    if (newPassword.length < 8) {
      setPasswordMsg({ text: 'New password must be at least 8 characters.', type: 'error' });
      setPasswordBusy(false);
      return;
    }
    try {
      await changePassword({ currentPassword, newPassword });
      setPasswordMsg({ text: 'Password changed successfully.', type: 'success' });
      setCurrentPassword('');
      setNewPassword('');
      setConfirmPassword('');
    } catch (err: any) {
      setPasswordMsg({
        text: err?.response?.data?.message || 'Failed to change password.',
        type: 'error',
      });
    } finally {
      setPasswordBusy(false);
    }
  }

  const msgStyle = (type: 'success' | 'error') =>
    type === 'success'
      ? 'rounded-lg bg-green-50 border border-green-100 p-4 text-sm text-green-800 flex items-center gap-3'
      : 'rounded-lg bg-red-50 border border-red-100 p-4 text-sm text-red-800 flex items-center gap-3';

  const msgIcon = (type: 'success' | 'error') =>
    type === 'success' ? <CheckCircle className="w-5 h-5 text-green-600" /> : <AlertCircle className="w-5 h-5 text-red-600" />;

  return (
    <div className="space-y-8">
      <div>
        <p className="text-xs font-semibold text-gold uppercase tracking-wider">Settings</p>
        <h1 className="font-serif text-3xl text-charcoal">Account Settings</h1>
        <p className="text-warm-gray">Manage your admin profile, email, and password.</p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {/* Profile Tab */}
        <div className="lg:col-span-7 space-y-6">
          <div className="rounded-xl border border-beige bg-white p-6">
            <div className="flex items-center gap-3 mb-6">
              <User className="w-6 h-6 text-gold" />
              <h2 className="font-serif text-xl text-charcoal">Profile Information</h2>
            </div>
            {profileMsg && (
              <div className={msgStyle(profileMsg.type)} mb-4>{msgIcon(profileMsg.type)} {profileMsg.text}</div>
            )}
            <form onSubmit={handleProfile} className="space-y-5">
              <div>
                <label className="block text-sm font-medium text-charcoal mb-1">Full Name</label>
                <input
                  type="text"
                  value={profileName}
                  onChange={(e) => setProfileName(e.target.value)}
                  disabled={profileBusy || loading}
                  className="w-full pl-3 py-3 bg-champagne/50 border border-beige rounded-xl text-charcoal focus:ring-2 focus:ring-gold focus:border-gold outline-none transition-all disabled:opacity-50"
                  placeholder="Royal Shine Admin"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-charcoal mb-1">Email Address</label>
                <div className="relative">
                  <Mail className="absolute left-3 top-1/2 -translate-y-1/2 text-warm-gray w-4 h-4" />
                  <input
                    type="email"
                    value={profileEmail}
                    onChange={(e) => setProfileEmail(e.target.value)}
                    disabled={profileBusy || loading}
                    className="w-full pl-10 py-3 bg-champagne/50 border border-beige rounded-xl text-charcoal focus:ring-2 focus:ring-gold focus:border-gold outline-none transition-all disabled:opacity-50"
                    placeholder="admin@royalshine.in"
                  />
                </div>
              </div>
              <button
                type="submit"
                disabled={profileBusy || loading}
                className="w-full flex items-center justify-center gap-2 py-3 bg-gold text-white rounded-xl font-medium hover:bg-gold-dark transition-all disabled:opacity-60 disabled:cursor-not-allowed"
              >
                <Save className="w-4 h-4" /> {profileBusy ? 'Saving…' : 'Save Changes'}
              </button>
            </form>
          </div>

          <div className="rounded-xl border border-beige bg-white p-6">
            <h3 className="font-semibold text-charcoal mb-4">Current Account</h3>
            <div className="space-y-3 text-sm">
              <div className="flex justify-between items-center border-b border-beige pb-2">
                <span className="text-warm-gray">Name</span>
                <span className="text-charcoal font-medium">{user?.name || '—'}</span>
              </div>
              <div className="flex justify-between items-center border-b border-beige pb-2">
                <span className="text-warm-gray">Email</span>
                <span className="text-charcoal font-medium">{user?.email || '—'}</span>
              </div>
              <div className="flex justify-between items-center border-b border-beige pb-2">
                <span className="text-warm-gray">Role</span>
                <span className="text-charcoal font-medium capitalize">{user?.role || '—'}</span>
              </div>
            </div>
          </div>
        </div>

        {/* Password Tab */}
        <div className="lg:col-span-5 space-y-6">
          <div className="rounded-xl border border-beige bg-white p-6">
            <div className="flex items-center gap-3 mb-6">
              <Lock className="w-6 h-6 text-gold" />
              <h2 className="font-serif text-xl text-charcoal">Change Password</h2>
            </div>
            {passwordMsg && (
              <div className={msgStyle(passwordMsg.type)} mb-4>{msgIcon(passwordMsg.type)} {passwordMsg.text}</div>
            )}
            <form onSubmit={handlePassword} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-charcoal mb-1">Current Password</label>
                <div className="relative">
                  <input
                    type={showCurrent ? 'text' : 'password'}
                    value={currentPassword}
                    onChange={(e) => setCurrentPassword(e.target.value)}
                    disabled={passwordBusy || loading}
                    className="w-full pr-10 py-3 bg-champagne/50 border border-beige rounded-xl text-charcoal focus:ring-2 focus:ring-gold focus:border-gold outline-none transition-all disabled:opacity-50"
                    placeholder="••••••••"
                  />
                  <button
                    type="button"
                    onClick={() => setShowCurrent(!showCurrent)}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-warm-gray hover:text-gold disabled:opacity-50"
                    disabled={passwordBusy || loading}
                  >
                    {showCurrent ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                  </button>
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-charcoal mb-1">New Password</label>
                <div className="relative">
                  <input
                    type={showNew ? 'text' : 'password'}
                    value={newPassword}
                    onChange={(e) => setNewPassword(e.target.value)}
                    disabled={passwordBusy || loading}
                    className="w-full pr-10 py-3 bg-champagne/50 border border-beige rounded-xl text-charcoal focus:ring-2 focus:ring-gold focus:border-gold outline-none transition-all disabled:opacity-50"
                    placeholder="••••••••"
                  />
                  <button
                    type="button"
                    onClick={() => setShowNew(!showNew)}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-warm-gray hover:text-gold disabled:opacity-50"
                    disabled={passwordBusy || loading}
                  >
                    {showNew ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                  </button>
                </div>
                <p className="text-[10px] text-warm-gray mt-1">At least 8 characters</p>
              </div>
              <div>
                <label className="block text-sm font-medium text-charcoal mb-1">Confirm New Password</label>
                <div className="relative">
                  <input
                    type={showConfirm ? 'text' : 'password'}
                    value={confirmPassword}
                    onChange={(e) => setConfirmPassword(e.target.value)}
                    disabled={passwordBusy || loading}
                    className="w-full pr-10 py-3 bg-champagne/50 border border-beige rounded-xl text-charcoal focus:ring-2 focus:ring-gold focus:border-gold outline-none transition-all disabled:opacity-50"
                    placeholder="••••••••"
                  />
                  <button
                    type="button"
                    onClick={() => setShowConfirm(!showConfirm)}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-warm-gray hover:text-gold disabled:opacity-50"
                    disabled={passwordBusy || loading}
                  >
                    {showConfirm ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                  </button>
                </div>
              </div>
              <button
                type="submit"
                disabled={passwordBusy || loading}
                className="w-full flex items-center justify-center gap-2 py-3 bg-gold text-white rounded-xl font-medium hover:bg-gold-dark transition-all disabled:opacity-60 disabled:cursor-not-allowed"
              >
                <Save className="w-4 h-4" /> {passwordBusy ? 'Updating…' : 'Change Password'}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
}
