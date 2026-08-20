# Security Information

## Security Headers
The application enforces strict security headers via `SecurityHeadersMiddleware`:
- `X-Frame-Options: SAMEORIGIN`
- `X-XSS-Protection: 1; mode=block`
- `X-Content-Type-Options: nosniff`
- `Strict-Transport-Security: max-age=31536000; includeSubDomains`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Content-Security-Policy`

## Authentication & Authorization
- **Fortify**: Handles login, registration (if enabled), and two-factor authentication.
- **Spatie Permissions**: Controls access via Roles and Permissions.
- **Activity Log**: Tracks all model creations, updates, and deletions, providing a clear audit trail.

## Vulnerability Reporting
If you discover a security vulnerability within this application, please send an email to the village administrator immediately. Do not disclose vulnerabilities publicly until they have been patched.
