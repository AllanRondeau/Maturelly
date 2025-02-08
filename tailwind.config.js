/** @type {import('tailwindcss').Config} */
import colors from 'tailwindcss/colors'
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    colors: {
      primary: '#FE0039',
      secondary: '#011936',
      ...colors
    },
    fontFamily: {
      'nunito': ['Nunito', 'sans-serif'],
      'anticSlab': ['Antic Slab', 'serif']
    },
    screens: {
      'xs': '500px',
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
      '2xl': '1536px',
    },
    extend: {
      inset: {
        17: '6rem',
      },
    },
  },
  plugins: [],
}

