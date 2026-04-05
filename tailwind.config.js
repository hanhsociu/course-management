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
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                soft: '0 2px 15px -3px rgb(0 0 0 / 0.07), 0 10px 20px -5px rgb(0 0 0 / 0.04)',
                card: '0 1px 2px rgb(15 23 42 / 0.04), 0 12px 40px -12px rgb(99 102 241 / 0.12)',
            },
        },
    },

    plugins: [forms],
};
