import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            zIndex: {
                "-10": "-10",
                "-20": "-20", // jika perlu nilai lain
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],

    safelist: [
        // Safelist dynamic color classes used in dashboard.blade.php
        'border-emerald-500',
        'text-emerald-500',
        'bg-emerald-100',
        'dark:bg-emerald-900',
        'border-pink-500',
        'text-pink-500',
        'bg-pink-100',
        'dark:bg-pink-900',
        'border-violet-500',
        'text-violet-500',
        'bg-violet-100',
        'dark:bg-violet-900',
        'border-red-500',
        'text-red-500',
        'bg-red-100',
        'dark:bg-red-900',
        'border-green-500',
        'text-green-500',
        'bg-green-100',
        'dark:bg-green-900',
        'border-orange-500',
        'text-orange-500',
        'bg-orange-100',
        'dark:bg-orange-900',
        'border-purple-500',
        'text-purple-500',
        'bg-purple-100',
        'dark:bg-purple-900',
        'border-indigo-500',
        'text-indigo-500',
        'bg-indigo-100',
        'dark:bg-indigo-900',
    ],
};
