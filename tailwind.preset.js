/**
 * RFPlugin Corporate Tailwind Preset
 *
 * Переиспользуемый preset с корпоративной дизайн-системой.
 * Подходит для использования в React/Vue проектах с Tailwind CSS.
 * Совместим с daisyUI.
 *
 * @package RFPlugin
 * @version 2.0.0
 *
 * Usage:
 * // tailwind.config.js
 * module.exports = {
 *   presets: [require('./tailwind.preset.js')],
 * }
 */

/** @type {import('tailwindcss').Config} */
module.exports = {
  theme: {
    extend: {
      colors: {
        // Primary - Navy Blue
        'corp-primary': {
          50: 'hsl(222, 47%, 97%)',
          100: 'hsl(222, 47%, 93%)',
          200: 'hsl(222, 47%, 85%)',
          300: 'hsl(222, 47%, 70%)',
          400: 'hsl(222, 47%, 50%)',
          500: 'hsl(222, 47%, 35%)',
          600: 'hsl(222, 47%, 25%)',
          700: 'hsl(222, 47%, 20%)',
          800: 'hsl(222, 47%, 15%)',
          900: 'hsl(222, 47%, 10%)',
          DEFAULT: 'hsl(222, 47%, 20%)',
        },
        // Secondary - Slate Gray
        'corp-secondary': {
          50: 'hsl(215, 20%, 97%)',
          100: 'hsl(215, 20%, 90%)',
          200: 'hsl(215, 20%, 80%)',
          300: 'hsl(215, 20%, 65%)',
          400: 'hsl(215, 20%, 50%)',
          500: 'hsl(215, 20%, 40%)',
          600: 'hsl(215, 20%, 30%)',
          700: 'hsl(215, 20%, 25%)',
          800: 'hsl(215, 20%, 18%)',
          900: 'hsl(215, 20%, 12%)',
          DEFAULT: 'hsl(215, 20%, 50%)',
        },
        // Accent - Deep Gold
        'corp-accent': {
          50: 'hsl(40, 60%, 97%)',
          100: 'hsl(40, 60%, 92%)',
          200: 'hsl(40, 60%, 82%)',
          300: 'hsl(40, 60%, 65%)',
          400: 'hsl(40, 60%, 55%)',
          500: 'hsl(40, 60%, 45%)',
          600: 'hsl(40, 60%, 38%)',
          700: 'hsl(40, 60%, 30%)',
          800: 'hsl(40, 60%, 22%)',
          900: 'hsl(40, 60%, 15%)',
          DEFAULT: 'hsl(40, 60%, 45%)',
        },
        // Neutral
        'corp-neutral': {
          50: 'hsl(210, 20%, 98%)',
          100: 'hsl(210, 18%, 96%)',
          200: 'hsl(210, 16%, 90%)',
          300: 'hsl(210, 14%, 80%)',
          400: 'hsl(210, 12%, 65%)',
          500: 'hsl(210, 10%, 50%)',
          600: 'hsl(210, 12%, 40%)',
          700: 'hsl(210, 14%, 30%)',
          800: 'hsl(210, 16%, 20%)',
          900: 'hsl(210, 18%, 12%)',
        },
        // Semantic
        'corp-success': {
          DEFAULT: 'hsl(150, 45%, 35%)',
          light: 'hsl(150, 45%, 95%)',
          dark: 'hsl(150, 45%, 25%)',
        },
        'corp-warning': {
          DEFAULT: 'hsl(30, 65%, 50%)',
          light: 'hsl(30, 65%, 95%)',
          dark: 'hsl(30, 65%, 35%)',
        },
        'corp-danger': {
          DEFAULT: 'hsl(0, 55%, 45%)',
          light: 'hsl(0, 55%, 95%)',
          dark: 'hsl(0, 55%, 30%)',
        },
        'corp-info': {
          DEFAULT: 'hsl(200, 60%, 45%)',
          light: 'hsl(200, 60%, 95%)',
          dark: 'hsl(200, 60%, 30%)',
        },
      },
      fontFamily: {
        'corp-sans': ['Outfit', 'Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
        'corp-mono': ['JetBrains Mono', 'Fira Code', 'Consolas', 'monospace'],
        'corp-display': ['Outfit', 'Inter', 'sans-serif'],
      },
      fontSize: {
        'corp-xs': '0.75rem',
        'corp-sm': '0.875rem',
        'corp-base': '1rem',
        'corp-lg': '1.125rem',
        'corp-xl': '1.25rem',
        'corp-2xl': '1.5rem',
        'corp-3xl': '1.875rem',
        'corp-4xl': '2.25rem',
        'corp-5xl': '3rem',
        'corp-6xl': '3.75rem',
      },
      borderRadius: {
        'corp-sm': '0.25rem',
        'corp-md': '0.5rem',
        'corp-lg': '0.75rem',
        'corp-xl': '1rem',
        'corp-2xl': '1.5rem',
        'corp-3xl': '2rem',
      },
      boxShadow: {
        'corp-xs': '0 1px 2px rgba(0, 0, 0, 0.05)',
        'corp-sm': '0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04)',
        'corp-md': '0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04)',
        'corp-lg': '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04)',
        'corp-xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
        'corp-2xl': '0 25px 50px -12px rgba(0, 0, 0, 0.15)',
        'corp-premium': '0 25px 50px -12px rgba(0, 0, 0, 0.08)',
        'corp-primary': '0 10px 30px -5px hsla(222, 47%, 35%, 0.25)',
        'corp-accent': '0 10px 30px -5px hsla(40, 60%, 45%, 0.25)',
      },
      transitionTimingFunction: {
        'corp-bounce': 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
        'corp-smooth': 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
      },
      spacing: {
        'corp-0': '0',
        'corp-1': '0.25rem',
        'corp-2': '0.5rem',
        'corp-3': '0.75rem',
        'corp-4': '1rem',
        'corp-5': '1.25rem',
        'corp-6': '1.5rem',
        'corp-8': '2rem',
        'corp-10': '2.5rem',
        'corp-12': '3rem',
        'corp-16': '4rem',
        'corp-20': '5rem',
        'corp-24': '6rem',
      },
      maxWidth: {
        'corp-xs': '20rem',
        'corp-sm': '24rem',
        'corp-md': '28rem',
        'corp-lg': '32rem',
        'corp-xl': '36rem',
        'corp-2xl': '42rem',
        'corp-3xl': '48rem',
        'corp-4xl': '56rem',
        'corp-5xl': '64rem',
        'corp-6xl': '72rem',
        'corp-7xl': '80rem',
      },
      zIndex: {
        'corp-dropdown': 1000,
        'corp-sticky': 1100,
        'corp-overlay': 1300,
        'corp-modal': 1400,
        'corp-popover': 1500,
        'corp-tooltip': 1600,
        'corp-toast': 1700,
      },
    },
  },
  plugins: [],
};

/**
 * daisyUI Theme Configuration
 *
 * Если используете daisyUI, добавьте эту тему:
 *
 * daisyui: {
 *   themes: [
 *     {
 *       rfcorp: {
 *         "primary": "hsl(222, 47%, 20%)",
 *         "primary-content": "#ffffff",
 *         "secondary": "hsl(215, 20%, 50%)",
 *         "secondary-content": "#ffffff",
 *         "accent": "hsl(40, 60%, 45%)",
 *         "accent-content": "hsl(222, 47%, 15%)",
 *         "neutral": "hsl(210, 14%, 30%)",
 *         "neutral-content": "#ffffff",
 *         "base-100": "#ffffff",
 *         "base-200": "hsl(210, 18%, 96%)",
 *         "base-300": "hsl(210, 16%, 90%)",
 *         "base-content": "hsl(220, 25%, 15%)",
 *         "info": "hsl(200, 60%, 45%)",
 *         "info-content": "#ffffff",
 *         "success": "hsl(150, 45%, 35%)",
 *         "success-content": "#ffffff",
 *         "warning": "hsl(30, 65%, 50%)",
 *         "warning-content": "#ffffff",
 *         "error": "hsl(0, 55%, 45%)",
 *         "error-content": "#ffffff",
 *         "--rounded-box": "0.75rem",
 *         "--rounded-btn": "0.75rem",
 *         "--rounded-badge": "1.9rem",
 *         "--animation-btn": "0.25s",
 *         "--animation-input": "0.2s",
 *         "--btn-focus-scale": "0.98",
 *         "--border-btn": "1px",
 *         "--tab-border": "1px",
 *         "--tab-radius": "0.5rem",
 *       },
 *     },
 *   ],
 * }
 */
