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
    extend: {},
  },
  plugins: [],
}

