import type { Config } from 'tailwindcss';

const config: Config = {
  darkMode: ['class'],
  content: [
    './src/pages/**/*.{js,ts,jsx,tsx,mdx}',
    './src/components/**/*.{js,ts,jsx,tsx,mdx}',
    './src/app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  theme: {
    extend: {
      colors: {
        gold: {
          DEFAULT: '#b08548',
          dark: '#9a6f36',
          50: '#fdf8f0',
          100: '#f9edd8',
          200: '#f2d9ab',
          300: '#e8c072',
          400: '#dca64a',
          500: '#b08548',
          600: '#9a6f36',
          700: '#7d582c',
          800: '#674929',
          900: '#573d26',
        },
        dark: {
          DEFAULT: '#1a1a1a',
          50: '#f7f7f7',
          100: '#e3e3e3',
          200: '#c8c8c8',
          300: '#a4a4a4',
          400: '#818181',
          500: '#666666',
          600: '#515151',
          700: '#434343',
          800: '#383838',
          900: '#1a1a1a',
        },
        cream: {
          DEFAULT: '#faf5ed',
          50: '#fdfaf5',
          100: '#faf5ed',
          200: '#f4e9d4',
          300: '#ead8b4',
          400: '#dcc090',
          500: '#cba86c',
        },
        brand: {
          primary: '#b08548',
          dark: '#1a1a1a',
          cream: '#faf5ed',
          'primary-hover': '#9a6f36',
          'primary-light': '#f9edd8',
        },
      },
      fontFamily: {
        heading: ['var(--font-playfair)', 'Georgia', 'serif'],
        body: ['var(--font-inter)', 'system-ui', 'sans-serif'],
        sans: ['var(--font-inter)', 'system-ui', 'sans-serif'],
        serif: ['var(--font-playfair)', 'Georgia', 'serif'],
      },
      backgroundImage: {
        'gradient-gold': 'linear-gradient(135deg, #b08548 0%, #dca64a 50%, #b08548 100%)',
        'gradient-dark': 'linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%)',
      },
      boxShadow: {
        gold: '0 4px 15px rgba(176, 133, 72, 0.3)',
        'gold-lg': '0 8px 30px rgba(176, 133, 72, 0.4)',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.5s ease-out',
        'shimmer': 'shimmer 2s infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        shimmer: {
          '0%': { backgroundPosition: '-200% 0' },
          '100%': { backgroundPosition: '200% 0' },
        },
      },
    },
  },
  plugins: [],
};

export default config;
