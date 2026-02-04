/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./rfplugin.php",
    "./includes/**/*.php",
    "./templates/**/*.php",
    "./assets/js/**/*.js",
    "./assets/react/**/*.{js,jsx,ts,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: 'hsl(var(--rf-primary-h), var(--rf-primary-s), 98%)',
          100: 'hsl(var(--rf-primary-h), var(--rf-primary-s), 95%)',
          200: 'hsl(var(--rf-primary-h), var(--rf-primary-s), 90%)',
          300: 'hsl(var(--rf-primary-h), var(--rf-primary-s), 80%)',
          400: 'hsl(var(--rf-primary-h), var(--rf-primary-s), 70%)',
          500: 'hsl(var(--rf-primary-h), var(--rf-primary-s), 60%)',
          600: 'hsl(var(--rf-primary-h), var(--rf-primary-s), 50%)',
          700: 'hsl(var(--rf-primary-h), var(--rf-primary-s), 40%)',
          800: 'hsl(var(--rf-primary-h), var(--rf-primary-s), 30%)',
          900: 'hsl(var(--rf-primary-h), var(--rf-primary-s), 20%)',
        },
        accent: {
          50: 'hsl(var(--rf-accent-h), var(--rf-accent-s), 98%)',
          100: 'hsl(var(--rf-accent-h), var(--rf-accent-s), 95%)',
          200: 'hsl(var(--rf-accent-h), var(--rf-accent-s), 90%)',
          300: 'hsl(var(--rf-accent-h), var(--rf-accent-s), 80%)',
          400: 'hsl(var(--rf-accent-h), var(--rf-accent-s), 70%)',
          500: 'hsl(var(--rf-accent-h), var(--rf-accent-s), 60%)',
          600: 'hsl(var(--rf-accent-h), var(--rf-accent-s), 50%)',
          700: 'hsl(var(--rf-accent-h), var(--rf-accent-s), 40%)',
          800: 'hsl(var(--rf-accent-h), var(--rf-accent-s), 30%)',
          900: 'hsl(var(--rf-accent-h), var(--rf-accent-s), 20%)',
        },
      },
      fontFamily: {
        sans: ['"Outfit"', '"Inter"', 'system-ui', '-apple-system', 'sans-serif'],
      },
      backdropBlur: {
        xs: '2px',
      },
      boxShadow: {
        'premium': '0 25px 50px -12px rgba(0, 0, 0, 0.08)',
        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
      },
      borderRadius: {
        'xl': '1rem',
        '2xl': '1.5rem',
        '3xl': '2rem',
      },
    },
  },
  plugins: [],
  // Use a prefix to avoid conflicts with theme styles or other plugins
  prefix: 'rf-',
  corePlugins: {
    preflight: false, // Disable preflight to avoid breaking WP admin
  }
}
