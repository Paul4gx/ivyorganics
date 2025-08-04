import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/views/admin/**/*.blade.php',
        './resources/views/admin/*.blade.php',
    ],

    theme: {
    extend: {
      colors: {
        // 'green-700': '#2a5c45',
        // 'green-800': '#1e4532',
        // 'green-900': '#143324',
        'ivory': '#f8f8f0',
      },
      fontFamily: {
        playfair: ['"Playfair Display"', 'serif'],
        poppins: ['Poppins', 'sans-serif'],
      },
    },
  },
    plugins: [forms],
}