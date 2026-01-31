import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#2C1810', // Deep Academic Brown
                'primary-container': '#D4AF37', // Gold for accents
                'on-primary-container': '#1A0D00',
                secondary: '#D4AF37', // Gold Accent
                'secondary-container': '#FDF6E3', // Parchment
                'on-secondary-container': '#1A0D00',
                surface: '#F8F1E9', // Aged Paper
                'surface-variant': '#ffffff', // White (Cards)
                'on-surface': '#1A0D00', // Deep Charcoal
                'on-surface-variant': '#8B4513', // Saddle Brown (Highlights)
                outline: '#8B4513',
                'inverse-surface': '#1A0D00',
                'inverse-on-surface': '#F8F1E9',
            },
            borderRadius: {
                '3xl': '1.5rem',
                '4xl': '2rem',
            },
            boxShadow: {
                'elevation-1': '0px 1px 2px rgba(0, 0, 0, 0.3), 0px 1px 3px 1px rgba(0, 0, 0, 0.15)',
                'elevation-2': '0px 1px 2px rgba(0, 0, 0, 0.3), 0px 2px 6px 2px rgba(0, 0, 0, 0.15)',
                'elevation-3': '0px 4px 8px 3px rgba(0, 0, 0, 0.15), 0px 1px 3px rgba(0, 0, 0, 0.3)',
            }
        },
    },

    plugins: [forms],
    safelist: [
        'w-40', 'h-60', 'md:w-48', 'md:h-72',
        'relative', 'absolute', 'z-20', 'bg-cover', 'bg-center',
        'shadow-xl', 'rounded-r-md', 'shadow-inner', 'inset-0',
        'transform-style-3d', 'perspective-1000',
        'fixed', 'top-0', 'left-0', 'right-0', 'w-full', 'z-50', 'bg-surface/80',
        'backdrop-blur-md', 'border-b', 'border-white/50', 'transition-all', 'duration-300',
        'flex', 'h-16', 'items-center', 'justify-between', 'space-x-8', 'text-gray-600', 'hover:text-primary'
    ],
};
