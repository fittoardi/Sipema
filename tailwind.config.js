import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'sandy-desk': '#e1d7c2',
                'paper-white': '#ffffff',
                'cream-paper': '#fdfdf8',
                'soft-linen': '#eeefe9',
                'pale-stone': '#e5e7e0',
                'deep-moss': '#23251d',
                'olive-char': '#4d4f46',
                'sage-gray': '#65675e',
                'ash-green': '#9ea096',
                'faded-pewter': '#b3b3af',
                'warm-mist': '#bfc1b7',
                'pure-black': '#000000',
                'signal-blue': '#2f80fa',
                'amber-glow': '#eb9d2a',
                'dark-amber': '#cd8407',
                'burnished-gold': '#b17816',
                'marigold': '#f1a82c',
                'flame-orange': '#f54e00',
                'moss-green': '#6aa84f',
            },
            borderRadius: {
                'card': '4px',
                'btn': '4px',
                'input': '4px',
                'window': '6px',
            },
            fontFamily: {
                sans: ['Inter', 'Open Runde', ...defaultTheme.fontFamily.sans],
                mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'monospace'],
            },
        },
    },

    plugins: [forms],
};
