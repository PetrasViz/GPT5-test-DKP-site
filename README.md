# DKP Site

Skeleton project structure with basic authentication flow.

## Added structure

- **Services**: business logic extracted from controllers (`src/services`).
- **Repositories**: simple data access layer for session-stored users (`src/repositories`).

The front controller (`public/index.php`) now uses Composer's PSR-4 autoloader to load
namespaced classes from `src/`.

## Roles

All users register as `guild_member`. Elevated roles such as `guild_advisor`, `guild_leader`, or `admin` must be granted by an administrator after registration. A privileged user can promote members by updating their role through the management tooling or by calling `UserRepository::update` in custom workflows.
