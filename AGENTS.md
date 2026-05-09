# AGENTS.md — Strict Mode

## Editable Files

- Only `massitpro-theme/` may be edited.
- Root `README.md` and `massitpro-theme/README.md` may be updated when documentation needs reflect implementation changes.
- `docs/` is read-only. Do not edit, create, or delete anything inside it.
- `source-reference/` is read-only. Do not edit, create, or delete anything inside it.

## Current Repair Scope

This pass is limited to **Blog and Projects native metafields and rendering**.

Do not touch:
- Unrelated page templates or renderers (homepage, services, industries, locations, about, contact, testimonials, FAQs).
- Unrelated CSS classes, global cards, buttons, footer, header, or brand system.
- Canonical URL files or routing logic.
- Menus, pages, posts, CPT content, or WordPress settings.

## Hard Rules

- Do not create, seed, duplicate, delete, rename, trash, or re-parent pages, posts, projects, CPT items, menus, slugs, taxonomies, or WordPress settings.
- Do not hardcode demo content from `source-reference/` into PHP. No demo titles, demo clients, fake project names, fake blog titles, or placeholder content.
- Do not require ACF, ACF Pro, Composer, npm, or any new plugin.
- Do not add a build step.
- Do not inject placeholder cards, demo content, blueprint arrays, fake testimonials, fake projects, or generic fallback copy.
- All live content must come from WordPress admin data: page title, page excerpt, page content, featured image, native page meta, existing published posts, existing published project CPT items, existing taxonomy terms.
- If a field or section is empty, omit the section. Do not render empty shells.
- If unsure about a change, stop and explain before guessing.

## Content Model Rules

- Use native WordPress fields and native meta boxes only.
- Use `register_post_meta()` for CPT meta fields.
- Reuse existing hero, CTA, process, cards, and spotlight editors and sanitizers.
- Do not use relationship selectors or featured-item dropdowns for visible page-building content unless the existing architecture already uses them for the same purpose.

## Validation Rules

After each pass:
- Run `php -l` on every modified PHP file.
- Confirm no syntax errors.
- Run `git diff -- docs source-reference` and confirm no changes.
- Confirm no demo source-reference titles, clients, or fake names were copied into theme PHP.
- Name the exact files changed.
- Name the exact validation commands run.
- State what still needs manual WordPress content entry.
- State any known limitations.

## Final Report Requirements

Every completion report must list:
1. Changed files.
2. Fixed or added fields.
3. Validation commands run and results.
4. Confirmation that `docs/` and `source-reference/` are untouched.
