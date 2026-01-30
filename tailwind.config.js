/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Livewire/**/*.php",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
      },
      colors: {
        'mayfair-gold': '#E8B923',
        'mayfair-dark': '#0A0A0A',
        'mayfair-gray': '#1A1A1A',
        'mayfair-border': '#333333',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
