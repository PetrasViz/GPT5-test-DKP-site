# DKP Site

Skeleton project structure with basic authentication flow.

## Added structure

- **Services**: business logic extracted from controllers (`src/services`).
- **Repositories**: simple data access layer for session-stored users (`src/repositories`).

The front controller (`public/index.php`) now uses Composer's PSR-4 autoloader to load
namespaced classes from `src/`.
