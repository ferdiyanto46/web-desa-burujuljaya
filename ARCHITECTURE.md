# Architecture (Enterprise Blueprint v8)

## Overview
Burujul Jaya Web Desa utilizes a strict three-tier architecture:
1. **Controller Layer**: Handles HTTP requests, FormRequest validation, Authorization (Gates/Policies), and returns Inertia Vue Responses. **No business logic.**
2. **Service Layer**: Encapsulates all business logic, database transactions, and cache management.
3. **Model Layer**: Handles database schema, relationships, and scopes.

## Strict Rules
- Controllers must use `Route Model Binding`.
- Services must be injected via `__construct`.
- All custom classes (Controllers, Services, Observers) must be `final class`.
- All properties injected via constructors must be `private readonly`.
- Eloquent Observers are used for side-effects (e.g., Activity Logging) rather than polluting Service classes.

## Frontend
- Vue 3 Composition API with `<script setup>`.
- `shadcn-vue` for UI components.
- `TailwindCSS v4` for utility styling.
- `Inertia.js` for seamless SPA routing.
