# RoyalFoam (RFPlugin) - Enterprise UI & Product Ecosystem

Production-ready WordPress plugin for high-performance enterprise sites. Optimized for **Google AI Search (SGE)**, technical product ecosystems, and premium user experiences.

## 🎯 Strategic Focus

- **Visual Excellence**: Unified design system built with **Tailwind CSS** and premium **GSAP** animations.
- **AI Search Optimization**: Advanced **JSON-LD Schema** engine (FAQPage, TechnicalArticle, Product) for maximum visibility in search LLMs.
- **Enterprise Performance**: 100% SEO Lighthouse scores and optimized Cumulative Layout Shift (CLS).
- **Reusable Blocks**: Built-in library of high-performance ACF blocks with glassmorphism aesthetics.

## 🏗️ Modern Architecture

### Tech Stack

- **Styling**: Tailwind CSS (prefixed with `rf-`)
- **Animations**: GSAP 3.x + ScrollTrigger
- **Structured Data**: JSON-LD Schema Generator
- **Fields**: ACF Pro Integration
- **E-commerce**: Integrated with WooCommerce for core product data

### Plugin Structure

```
rfplugin/
├── assets/
│   ├── css/                  # Compiled Tailwind CSS (main.css)
│   ├── js/                   # GSAP Animation logic (animations.js)
│   └── react/                # React-based Product Constructor
├── includes/
│   ├── ACF/                  # Block definitions & field groups
│   ├── Core/                 # Main orchestrator (Plugin.php)
│   ├── PostTypes/            # Unified Resources & Invoices
│   ├── Services/             # Schema Logic & Data Importers
│   └── REST/                 # Secure API endpoints
├── templates/
│   ├── blocks/               # Tailwind-powered ACF templates
│   └── admin/                # Premium admin dashboard
└── tailwind.config.js        # Design system tokens (HSL-based)
```

## 🚀 Development & Build

### Requirements

- PHP 8.4+
- Node.js 18+
- Tailwind CSS CLI

### Installation

1. Install dependencies: `npm install`
2. Build assets: `npm run build`
3. Dev mode: `npm run dev:css` (watches Tailwind changes)

## 📦 Core Blocks Library

- **Feature Hero** - Atmospheric hero section with GSAP fade-ups.
- **Tech Doc List** - Grid-based technical resource browser.
- **FAQ Accordion** - Interactive Q&A with built-in `FAQPage` schema.
- **CTA Block** - Premium Call-to-Action with glassmorphism.
- **Container** - High-level section wrapper with custom padding.

## 🏷️ SEO & AI Search (SGE)

The plugin automatically injects structured data to ensure Google AI Search understands your content:

- **FAQPage**: Generated for all FAQ resource types.
- **TechnicalArticle**: Detailed schema for technical documentation.
- **Product**: Enhanced enterprise schema for WooCommerce.
- **Breadcrumbs**: Automated logical site hierarchy.

## 🔧 Coding Standards

- **Naming**: `rf-` prefix for all CSS classes to prevent theme conflicts.
- **Colors**: HSL variables in `tailwind.config.js` for easy branding changes.
- **Performance**: GSAP `will-change` optimization and initial opacity hiding to stop layout shifts.

## 👥 Author

RoyalFoam Development Team

---
*GPL v2 or later. Enterprise-ready.*
