/**
 * RFPlugin Corporate Design Tokens - JavaScript Export
 *
 * Экспорт дизайн-токенов для использования в React/Vue компонентах.
 * Синхронизирован с rf-design-tokens.css
 *
 * @package RFPlugin
 * @version 2.0.0
 */

// Corporate Color Palette
export const colors = {
  primary: {
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
    hover: 'hsl(222, 47%, 25%)',
  },
  secondary: {
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
  accent: {
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
  neutral: {
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
  success: {
    DEFAULT: 'hsl(150, 45%, 35%)',
    light: 'hsl(150, 45%, 95%)',
    dark: 'hsl(150, 45%, 25%)',
  },
  warning: {
    DEFAULT: 'hsl(30, 65%, 50%)',
    light: 'hsl(30, 65%, 95%)',
    dark: 'hsl(30, 65%, 35%)',
  },
  danger: {
    DEFAULT: 'hsl(0, 55%, 45%)',
    light: 'hsl(0, 55%, 95%)',
    dark: 'hsl(0, 55%, 30%)',
  },
  info: {
    DEFAULT: 'hsl(200, 60%, 45%)',
    light: 'hsl(200, 60%, 95%)',
    dark: 'hsl(200, 60%, 30%)',
  },
};

// Typography
export const typography = {
  fontFamily: {
    sans: ['Outfit', 'Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
    mono: ['JetBrains Mono', 'Fira Code', 'Consolas', 'monospace'],
    display: ['Outfit', 'Inter', 'sans-serif'],
  },
  fontSize: {
    xs: '0.75rem',
    sm: '0.875rem',
    base: '1rem',
    lg: '1.125rem',
    xl: '1.25rem',
    '2xl': '1.5rem',
    '3xl': '1.875rem',
    '4xl': '2.25rem',
    '5xl': '3rem',
    '6xl': '3.75rem',
  },
  fontWeight: {
    light: 300,
    normal: 400,
    medium: 500,
    semibold: 600,
    bold: 700,
    extrabold: 800,
  },
  lineHeight: {
    none: 1,
    tight: 1.25,
    snug: 1.375,
    normal: 1.5,
    relaxed: 1.625,
    loose: 2,
  },
  letterSpacing: {
    tighter: '-0.05em',
    tight: '-0.025em',
    normal: '0',
    wide: '0.025em',
    wider: '0.05em',
    widest: '0.1em',
  },
};

// Spacing (8px base grid)
export const spacing = {
  0: '0',
  px: '1px',
  0.5: '0.125rem',
  1: '0.25rem',
  1.5: '0.375rem',
  2: '0.5rem',
  2.5: '0.625rem',
  3: '0.75rem',
  3.5: '0.875rem',
  4: '1rem',
  5: '1.25rem',
  6: '1.5rem',
  7: '1.75rem',
  8: '2rem',
  9: '2.25rem',
  10: '2.5rem',
  12: '3rem',
  14: '3.5rem',
  16: '4rem',
  20: '5rem',
  24: '6rem',
  28: '7rem',
  32: '8rem',
};

// Border Radius
export const borderRadius = {
  none: '0',
  sm: '0.25rem',
  md: '0.5rem',
  lg: '0.75rem',
  xl: '1rem',
  '2xl': '1.5rem',
  '3xl': '2rem',
  full: '9999px',
};

// Shadows
export const boxShadow = {
  xs: '0 1px 2px rgba(0, 0, 0, 0.05)',
  sm: '0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04)',
  md: '0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04)',
  lg: '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04)',
  xl: '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
  '2xl': '0 25px 50px -12px rgba(0, 0, 0, 0.15)',
  inner: 'inset 0 2px 4px rgba(0, 0, 0, 0.05)',
  premium: '0 25px 50px -12px rgba(0, 0, 0, 0.08)',
};

// Transitions
export const transitions = {
  duration: {
    75: '75ms',
    100: '100ms',
    150: '150ms',
    200: '200ms',
    300: '300ms',
    500: '500ms',
    700: '700ms',
    1000: '1000ms',
  },
  timing: {
    easeIn: 'cubic-bezier(0.4, 0, 1, 1)',
    easeOut: 'cubic-bezier(0, 0, 0.2, 1)',
    easeInOut: 'cubic-bezier(0.4, 0, 0.2, 1)',
    bounce: 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
    smooth: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
  },
};

// Z-Index
export const zIndex = {
  hide: -1,
  auto: 'auto',
  base: 0,
  docked: 10,
  dropdown: 1000,
  sticky: 1100,
  banner: 1200,
  overlay: 1300,
  modal: 1400,
  popover: 1500,
  tooltip: 1600,
  toast: 1700,
  max: 9999,
};

// Container Sizes
export const containers = {
  xs: '20rem',
  sm: '24rem',
  md: '28rem',
  lg: '32rem',
  xl: '36rem',
  '2xl': '42rem',
  '3xl': '48rem',
  '4xl': '56rem',
  '5xl': '64rem',
  '6xl': '72rem',
  '7xl': '80rem',
  full: '100%',
};

// Complete Design Tokens Export
export const designTokens = {
  colors,
  typography,
  spacing,
  borderRadius,
  boxShadow,
  transitions,
  zIndex,
  containers,
};

// CSS Variable Generator for React/Vue inline styles
export function cssVar(name) {
  return `var(--rf-corp-${name})`;
}

// Theme helper
export const theme = {
  bg: cssVar('bg'),
  surface: cssVar('surface'),
  text: cssVar('text'),
  textSecondary: cssVar('text-secondary'),
  textMuted: cssVar('text-muted'),
  border: cssVar('border'),
  primary: cssVar('primary'),
  secondary: cssVar('secondary'),
  accent: cssVar('accent'),
};

export default designTokens;
