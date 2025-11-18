/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./resources/**/*.ts",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#fef7ee',
          100: '#fdeed7',
          200: '#fad9ae',
          300: '#f6be7a',
          400: '#f29744',
          500: '#ef7a1f',
          600: '#e05f15',
          700: '#b94713',
          800: '#933917',
          900: '#763115',
        },
      },
    },
  },
  plugins: [],
}

