/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.{html,twig}",
    "./templates/components/**/*.{html,twig}",
    "./templates/front/**/*.{html,twig}",
    "./templates/back/**/*.{html,twig}",
    "./src/**/*.{html,js}",
    "./templates/**/*.{html,twig}",
  ],
  theme: {
    colors: {
      'black': '#222222',
      'white': '#FAFCFE',
      'lightgrey': '#EBEBEB',
      'grey': '#7E7E7E',
      'primary-cm': '#fb4f14',
      'secondary': '#55afb9',
      'success': '#3ABA6B',
      'red': '#EB334B',
      'warning': '#FFA630',
    },
    fontFamily: {
      'sans': ['Roboto', 'sans-serif'],
      'montserrat': ['Montserrat', 'sans-serif']
    },
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('daisyui'),
  ],
  daisyui: {
    themes: [
      // light theme
      {
        light: {
          ...require("daisyui/src/theming/themes")["[data-theme=light]"],
          "--primary-cm": "#fb4f14"
        },
      },
      // cupcake theme
      {
        cupcake: {
          ...require("daisyui/src/theming/themes")["[data-theme=cupcake]"],
          "--primary-cm": "#fb4f14"
        },
      },
      // dark theme
      {
        dark: {
          ...require("daisyui/src/theming/themes")["[data-theme=dark]"],
          "--primary-cm": "#fb4f14"
        },
      },
    ],
    darkTheme: "light", // name of one of the included themes for dark mode
    base: true, // applies background color and foreground color for root element by default
    styled: true, // include daisyUI colors and design decisions for all components
    utils: true, // adds responsive and modifier utility classes
    prefix: "", // prefix for daisyUI classnames (components, modifiers and responsive class names. Not colors)
    logs: true, // Shows info about daisyUI version and used config in the console when building your CSS
    themeRoot: ":root", // The element that receives theme color CSS variables
  },
}
