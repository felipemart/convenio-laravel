import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        "./vendor/robsontenorio/mary/src/View/Components/**/*.php"
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [
        forms,
        typography,
        require("daisyui")
    ],
    daisyui: {
        themes: [
            {
                light: {
                    "primary": "#a991f7",
                    "secondary": "#f6d860",
                    "accent": "#37cdbe",
                    "neutral": "#3d4451",
                    "base-100": "#ffffff",
                },
                dark: {

                    "primary": "#115e59",
                    "primary-content": "#cdd0d3",
                    "secondary": "#6b7280",
                    "secondary-content": "#e0e1e4",
                    "accent": "#d1d5db",
                    "accent-content": "#101011",
                    "neutral": "#4b5563",
                    "neutral-content": "#d8dbde",
                    "base-100": "#1f2937",
                    "base-200": "#374151",
                    "base-300": "#111827",
                    "base-content": "#c9cbcf",
                    "info": "#38bdf8",
                    "info-content": "#010d15",
                    "success": "#22c55e",
                    "success-content": "#000e03",
                    "warning": "#eab308",
                    "warning-content": "#130c00",
                    "error": "#ef4444",
                    "error-content": "#140202",

                },
            },
        ],
    },
};


