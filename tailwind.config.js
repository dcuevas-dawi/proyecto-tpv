import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    plugins: [forms],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
            colors: {
                primary: '#038b25',
                primaryLight: '#43a047',
                secondary: '#F0F0F0',
                success: '#7CB342',
                warning: '#FFA726',
                error: '#E53935',
                borderGray: '#BDBDBD',
                surface: '#FFFFFF'
            },
            width: {
                'sidebar': '15%',
                'content': '85%'
            }
        },
    },
    corePlugins: {
        darkMode: false,
    },
};
