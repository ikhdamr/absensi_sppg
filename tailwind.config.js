/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // <--- TAMBAHKAN BARIS INI (Wajib pakai koma)
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
