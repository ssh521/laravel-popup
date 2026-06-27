# AGENTS.md

## Package Boundary

`ssh521/laravel-popup` owns popup, banner, and notice bar management for Laravel sites and `ssh521/laravel-admin`.

It manages display conditions, close policies, admin CRUD, and public rendering hooks. It does not own admin shell/auth behavior or file manager internals.

## Source Of Truth

- Package behavior: `README.md` and `PRD.md`
- Service provider: `src/LaravelPopupServiceProvider.php`
- Config: `config/laravel-popup.php`
- Admin views: `resources/views/admin/`
- Public rendering views/components: `resources/views/`
- Shared admin UI contract: `../laravel-admin-ui/docs/admin-ui-design-contract.md`
- Component catalog: `../laravel-admin-ui/docs/components.md`

## Change Rules

- Preserve popup status and display-condition semantics.
- Keep public rendering behavior separate from admin CRUD UI changes.
- Do not change close policy storage or request matching behavior for visual-only edits.
- Use shared admin UI components for admin screens before adding package-local UI wrappers.

## Verification

```bash
git diff --check
/Users/ssh521/Projects/Packagist/adminTest/vendor/bin/phpunit --configuration phpunit.xml.dist
```
