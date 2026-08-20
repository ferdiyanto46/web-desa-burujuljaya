# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-08-02
### Added
- **Phase 1-2**: Foundation setup with Laravel 11, SQLite, and Inertia Vue 3.
- **Phase 3**: Authentication via Laravel Fortify (Passkeys, 2FA, Security logs).
- **Phase 4**: User & Role Management via Spatie Permissions.
- **Phase 5**: Content Management System (Articles, Categories, Announcements).
- **Phase 6**: File & Media Management (Galleries, Documents).
- **Phase 7**: Economy & Tourism (UMKM, Products, Tourism spots).
- **Phase 8**: Village Profiling (Dynamic pages, contact information).
- **Phase 9**: Public Interaction (Village services tracking, FAQs, Homepage).
- **Phase 10**: System Administration (Dashboard, Settings, Activity Logs, Audits).
- **Phase 11**: Production Hardening (Security headers, SEO Sitemap, Caching).

### Changed
- Refactored entire codebase to strict Controller -> Service -> Model architecture.
- Enforced `final class` and `private readonly` dependency injection.

### Security
- Added `SecurityHeadersMiddleware`.
- Strict CSP and Permissions-Policy enabled.
