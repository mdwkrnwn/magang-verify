/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './components/**/*.php',
    './config/**/*.php',
    './controllers/**/*.php',
    './data/**/*.php',
    './function/**/*.php',
    './helpers/**/*.php',
    './middleware/**/*.php',
    './models/**/*.php',
    './pages/**/*.php',
    './routes/**/*.php',
    './assets/js/**/*.js',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
