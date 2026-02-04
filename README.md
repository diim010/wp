# RFplugin - RoyalFoam WordPress Plugin

Enterprise-grade WordPress plugin for specification-based product construction and invoice management system, built without WooCommerce.

## 🎯 Overview

RFplugin is a production-ready WordPress plugin that provides:

- **Specification-based Product Constructor** - Build custom products with dynamic specifications
- **Invoice System** - JSON-based invoice storage with future PDF & ERP integration
- **REST API** - Secure, fully documented API endpoints
- **React Frontend** - Modern, interactive user interface
- **Custom Post Types** - Products, Services, Cases, Invoices, Tech Docs, FAQ
- **ACF Pro Integration** - Smart field management
- **SEO Optimized** - Schema.org ready, Google AI Search compatible

## 📋 Requirements

- WordPress 6.0+
- PHP 8.4+
- ACF Pro (recommended)
- Node.js 16+ (for React development)

## 🚀 Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through WordPress admin
3. Install and activate ACF Pro (optional but recommended)
4. Navigate to **RoyalFoam** menu in admin panel

## 🏗️ Architecture

### Core plugin Structure

```
rfplugin/
├── rfplugin.php              # Main plugin file
├── includes/
│   ├── Core/                 # Core plugin classes
│   │   ├── Plugin.php        # Main plugin orchestrator
│   │   ├── Activator.php     # Activation handler
│   │   └── Deactivator.php   # Deactivation handler
│   ├── PostTypes/            # Custom post types
│   │   ├── BasePostType.php
│   │   ├── ProductPostType.php
│   │   ├── ServicePostType.php
│   │   ├── CasePostType.php
│   │   ├── InvoicePostType.php
│   │   ├── TechDocPostType.php
│   │   └── FAQPostType.php
│   ├── Taxonomies/           # Custom taxonomies
│   │   ├── BaseTaxonomy.php
│   │   ├── ProductTypeTaxonomy.php
│   │   ├── MaterialTaxonomy.php
│   │   └── CaseIndustryTaxonomy.php
│   ├── Admin/                # Admin interface
│   │   ├── Menu.php          # Admin menu
│   │   └── Branding.php      # WP branding removal
│   ├── REST/                 # REST API
│   │   ├── Router.php
│   │   └── Controllers/
│   │       ├── BaseController.php
│   │       ├── ProductsController.php
│   │       ├── InvoicesController.php
│   │       ├── ServicesController.php
│   │       ├── CasesController.php
│   │       ├── TechDocsController.php
│   │       └── FAQController.php
│   ├── Services/             # Business logic
│   │   ├── ProductConstructor.php
│   │   └── InvoiceManager.php
│   ├── Security/             # Security & permissions
│   │   └── Permissions.php
│   ├── ACF/                  # ACF field groups
│   │   └── FieldGroups.php
│   └── Utils/                # Utilities
│       ├── Logger.php
│       └── Validator.php
├── templates/                # PHP templates
│   └── admin/
│       ├── dashboard.php
│       └── settings.php
├── assets/
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript
│   └── react/                # React application
│       ├── src/
│       │   ├── index.jsx
│       │   └── components/
│       │       ├── ProductConstructor.jsx
│       │       └── InvoiceCreator.jsx
│       ├── package.json
│       └── webpack.config.js
└── README.md
```

## 📦 Custom Post Types

- **rf_product** - Products with specifications
- **rf_service** - Additional services
- **rf_case** - Portfolio case studies
- **rf_invoice** - Customer invoices (admin only)
- **rf_techdoc** - Technical documentation
- **rf_faq** - Frequently asked questions

## 🏷️ Taxonomies

- **rf_product_type** - Product categorization
- **rf_material** - Material types and patterns
- **rf_case_industry** - Industry classification for cases

## 🔌 REST API Endpoints

### Products

```
GET    /wp-json/rfplugin/v1/products
GET    /wp-json/rfplugin/v1/products/{id}
POST   /wp-json/rfplugin/v1/products/{id}/construct
```

### Invoices (Authentication Required)

```
GET    /wp-json/rfplugin/v1/invoices
POST   /wp-json/rfplugin/v1/invoices
GET    /wp-json/rfplugin/v1/invoices/{id}
```

### Services, Cases, Tech Docs, FAQ

```
GET    /wp-json/rfplugin/v1/{endpoint}
GET    /wp-json/rfplugin/v1/{endpoint}/{id}
```

## 🔐 Security

- **Authentication** - Required for invoice creation
- **Authorization** - Role-based access control
- **Nonce Verification** - CSRF protection
- **Data Sanitization** - All inputs sanitized
- **Permissions** - Custom capabilities system

### Custom Capabilities

- `manage_rfplugin` - Full plugin management
- `view_rfplugin_invoices` - View all invoices
- `create_rfplugin_invoices` - Create invoices
- `edit_rfplugin_invoices` - Edit invoices
- `delete_rfplugin_invoices` - Delete invoices

## ⚛️ React Frontend

### Development

```bash
cd assets/react
npm install
npm run dev
```

### Production Build

```bash
npm run build
```

### Components

- **ProductConstructor** - Interactive product builder
- **InvoiceCreator** - Invoice creation form

## 💾 Invoice System

Invoices are stored as:

1. Custom post type entries (WordPress database)
2. JSON files in `/wp-content/uploads/rfplugin-invoices/`

### Future Integration

## 🎨 Admin Interface

### RoyalFoam Menu

- Dashboard - Statistics and quick links
- Products - Product management
- Services - Service management
- Cases - Case study management
- Invoices - Invoice management
- Tech Docs - Documentation management
- FAQ - FAQ management
- Settings - Plugin configuration

### WordPress Branding

All WordPress branding removed from:

- Admin footer
- Login page
- Admin bar
- multisite

## ⚙️ Configuration

### Plugin Settings

- **Invoice Prefix** - Customize invoice number prefix
- **PDF Export** - Enable/disable (coming soon)
- **ERP Integration** - Enable/disable (coming soon)

## 🧩 ACF Field Groups

### Product Specifications

- SKU
- Base Price
- Default Specifications (Height, Width, Length, Density, Color)

- Related Cases
- Technical Files

### Service Details

- Price
- Duration

### Case Details

- Client Name
- Gallery
- Results

### Material Details (Taxonomy)

- Pattern Image
- Properties

## 📊 Product Constructor

The product constructor aggregates:

1. **Product Base Data** - Title, description, SKU
2. **Specifications** - Custom dimensions and properties
3. **Materials** - Available material options with patterns
4. **Calculations** - Volume, surface area
5. **Related Cases** - Portfolio examples
6. **Available Services** - Add-on services
7. **Tech Files** - Documentation and datasheets

### Example API Request

```javascript
POST /wp-json/rfplugin/v1/products/123/construct

{
  "height": 100,
  "width": 50,
  "length": 200,
  "density": "medium",
  "color": "blue",
  "custom_notes": "Custom specifications"
}
```

## 🔧 Development

### Coding Standards

- **PHP Version**: 8.4
- **Indentation**: 4 spaces
- **Naming**: PascalCase (classes), camelCase (methods/variables)
- **Documentation**: PHPDoc/JSDoc required
- **Line Length**: 120 characters max

### Best Practices

- OOP architecture throughout
- Namespaces for organization
- PSR-4 autoloading
- Type hints and return types
- Comprehensive error handling

## 📝 License

GPL v2 or later

## 👥 Author

RoyalFoam Development Team

## 🚧 Roadmap

- [ ] PDF invoice export
- [ ] ERP system integration
- [ ] Multi-language support
- [ ] Advanced analytics dashboard
- [ ] Customer portal
- [ ] Email notifications
- [ ] Quote system
- [ ] Product variants

## 💡 Support

For support and documentation, visit the plugin settings page in your WordPress admin.

---

# Ait-Light theme folder contain base theme boilerplate files

Use next methihod to cteate theme:

1. header.php
2. footer.php
3. front-page.php
4. template-parts/blocks/block-name.php (Acf block, HTML + PHP)
5. template-parts/sections/section-name.php (HTML + PHP)

## Create new block

- functions.php :
  -- register block
  -- allow block for post or page post type.
- Add block (HTML + ACF + CSS) in template-parts/blocks/block.php also add theme/assets/src/sass/gutenberg/_block.scss
