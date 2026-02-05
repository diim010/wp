/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  // Use the corporate preset
  presets: [require('./tailwind.preset.js')],

  content: [
    "./rfplugin.php",
    "./includes/**/*.php",
    "./templates/**/*.php",
    "./assets/js/**/*.js",
    "./assets/react/**/*.{js,jsx,ts,tsx}",
  ],

  theme: {
    extend: {
      // Legacy colors kept for backward compatibility
      // New code should use corp-* prefixed colors from preset
      colors: {
        primary: {
          50: 'hsl(var(--rf-primary-h, 222), var(--rf-primary-s, 47%), 98%)',
          100: 'hsl(var(--rf-primary-h, 222), var(--rf-primary-s, 47%), 95%)',
          200: 'hsl(var(--rf-primary-h, 222), var(--rf-primary-s, 47%), 90%)',
          300: 'hsl(var(--rf-primary-h, 222), var(--rf-primary-s, 47%), 80%)',
          400: 'hsl(var(--rf-primary-h, 222), var(--rf-primary-s, 47%), 70%)',
          500: 'hsl(var(--rf-primary-h, 222), var(--rf-primary-s, 47%), 60%)',
          600: 'hsl(var(--rf-primary-h, 222), var(--rf-primary-s, 47%), 50%)',
          700: 'hsl(var(--rf-primary-h, 222), var(--rf-primary-s, 47%), 40%)',
          800: 'hsl(var(--rf-primary-h, 222), var(--rf-primary-s, 47%), 30%)',
          900: 'hsl(var(--rf-primary-h, 222), var(--rf-primary-s, 47%), 20%)',
        },
        accent: {
          50: 'hsl(var(--rf-accent-h, 40), var(--rf-accent-s, 60%), 98%)',
          100: 'hsl(var(--rf-accent-h, 40), var(--rf-accent-s, 60%), 95%)',
          200: 'hsl(var(--rf-accent-h, 40), var(--rf-accent-s, 60%), 90%)',
          300: 'hsl(var(--rf-accent-h, 40), var(--rf-accent-s, 60%), 80%)',
          400: 'hsl(var(--rf-accent-h, 40), var(--rf-accent-s, 60%), 70%)',
          500: 'hsl(var(--rf-accent-h, 40), var(--rf-accent-s, 60%), 60%)',
          600: 'hsl(var(--rf-accent-h, 40), var(--rf-accent-s, 60%), 50%)',
          700: 'hsl(var(--rf-accent-h, 40), var(--rf-accent-s, 60%), 40%)',
          800: 'hsl(var(--rf-accent-h, 40), var(--rf-accent-s, 60%), 30%)',
          900: 'hsl(var(--rf-accent-h, 40), var(--rf-accent-s, 60%), 20%)',
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

  // No prefix to support standard Tailwind classes
  // prefix: 'rf-',

  corePlugins: {
    preflight: false, // Disable preflight to avoid breaking WP admin
  }
}
