import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                ivory: '#FAF8F4',
                champagne: '#F2E8DA',
                charcoal: '#25221F',
                'warm-gray': '#77716A',
                gold: '#B68A4A',
                brown: '#563D2B',
                beige: '#E9E1D6',
                white: '#FFFFFF',
            },
            fontFamily: {
                serif: ['Cormorant Garamond', 'Playfair Display', ...defaultTheme.fontFamily.serif],
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            spacing: {
                '18': '4.5rem',
                '22': '5.5rem',
                '26': '6.5rem',
            },
            borderRadius: {
                xs: '2px',
                sm: '4px',
                DEFAULT: '6px',
                md: '8px',
                lg: '12px',
            },
            boxShadow: {
                'luxury': '0 4px 20px -4px rgba(37, 34, 31, 0.06)',
                'luxury-lg': '0 8px 40px -8px rgba(37, 34, 31, 0.08)',
            },
        },
    },
    plugins: [],
};
