# API Overview

*Note: This application primarily uses Inertia.js for data hydration. Dedicated JSON APIs are minimal.*

## Authentication API (Fortify)
- `POST /login`: Authenticate user.
- `POST /logout`: Destroy session.
- `POST /two-factor-challenge`: Verify 2FA token.

## Internal APIs
- `/api/sitemap.xml`: Generates XML sitemap dynamically.
- `Admin / Backend routes`: Accessible only via Inertia XHR requests by authenticated users.

## Frontend Hydration
Data is passed to Vue components via `$page.props` in Inertia. Models are strictly transformed using Eloquent API Resources (e.g., `ArticleResource`) to prevent sensitive data leakage.
