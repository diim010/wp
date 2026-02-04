# Changelog

All notable changes to RFplugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-12-30

### Added

#### Core Features
- Initial plugin release
- OOP architecture with PSR-4 autoloading
- Singleton pattern for main plugin class
- WordPress Codex compliant structure

#### Custom Post Types
- Product post type with specifications support
- Service post type for add-on services
- Case post type for portfolio examples
- Invoice post type with restricted access
- Tech Doc post type for technical documentation
- FAQ post type for frequently asked questions

#### Taxonomies
- Product Type taxonomy for categorization
- Material taxonomy for material selection
- Case Industry taxonomy for industry classification

#### REST API
- Complete REST API implementation
- Products endpoint with construct capability
- Invoices endpoint with authentication
- Services, Cases, Tech Docs, and FAQ endpoints
- Proper permission callbacks
- Error handling and validation

#### Admin Interface
- RoyalFoam admin menu with dashboard
- Statistics dashboard
- Settings page
- WordPress branding removal
- Custom login page styling
- Admin footer customization

#### Product Constructor
- Specification-based product building
- Dynamic dimensions calculation
- Material pattern selection
- Related cases integration
- Available services listing
- Technical files attachment
- Volume and surface area calculation

#### Invoice System
- JSON-based invoice storage
- User authentication requirement
- Invoice number generation
- Customer data validation
- Products and services totals calculation
- Tax calculation (20% default)
- Secure file storage in uploads directory

#### Security & Permissions
- Custom capabilities system
- Role-based access control
- Nonce verification
- Data sanitization
- Input validation
- REST API authentication

#### ACF Integration
- Product specifications field group
- Service details field group
- Case details field group
- Tech doc field group
- Material taxonomy field group
- Repeater fields for technical files

#### Frontend Assets
- React-based product constructor
- React-based invoice creator
- Admin CSS styling
- Frontend CSS styling
- Admin JavaScript
- Webpack build configuration

#### Utilities
- Logger utility for debugging
- Validator utility for data validation
- Custom sanitization functions

### Prepared for Future
- PDF export functionality (placeholder)
- ERP integration (placeholder)
- Settings for enabling future features

### Documentation
- Comprehensive README.md
- PHPDoc documentation throughout
- JSDoc documentation for React components
- Inline code comments (only PHPDoc/JSDoc style)

---

## Future Versions

### Planned for [1.1.0]
- PDF invoice export implementation
- Email notifications
- Quote system
- Customer portal

### Planned for [1.2.0]
- ERP system integration
- Advanced analytics
- Multi-language support
- Product variants

### Planned for [2.0.0]
- Mobile app API
- Advanced reporting
- Inventory management
- Payment gateway integration
