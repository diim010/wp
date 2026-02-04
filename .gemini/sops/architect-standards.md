# SOP: Enterprise WordPress Development

## 1. Database & Performance (Query Monitor Focus)

- **No Raw SQL:** All queries must use `$wpdb->prepare()`.
- **N+1 Prevention:** `get_post_meta` or `get_field` must not exist inside loops. Use `update_meta_cache` or local object caching.
- **Transients:** Any external API call (even Gemini's own) must be wrapped in a 1-hour transient minimum.

## 2. WooCommerce Scaling

- **Hook Optimization:** Avoid `init` for logic that only applies to checkout. Use `woocommerce_checkout_process`.
- **Large Orders:** Scripts must handle 500+ line items without hitting PHP memory limits.

## 3. ACF Pro & Data Integrity

- **ACF JSON:** All field groups must be synced via `acf-json`. Manual DB field creation is a "Hard Fail."
- **Validation:** Every `update_field` must be preceded by `current_user_can` and data sanitization.
