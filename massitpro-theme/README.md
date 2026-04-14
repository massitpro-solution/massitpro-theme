# Mass IT Pro Theme

Shared-template WordPress theme for Mass IT Pro.

## Current Architecture
- Canonical routing comes from `docs/canonical-urls.md`.
- Shared templates and render functions control layout.
- Live content comes from WordPress admin:
  - page title
  - page excerpt
  - page content
  - featured image
  - native page meta
  - CPT queries where appropriate
- Empty section data is omitted from output.

## Current Native Meta Scope
- Homepage
- Services hub
- Business landing
- Residential landing
- Industries hub
- Locations hub
- About page
- Contact page
- Service detail pages
- Industry detail pages
- Location detail pages

## Notes
- Do not create one PHP file per page.
- Do not seed placeholder content.
- Do not change canonical URLs.
- Shared renderers now use split panels, feature rows, premium card grids, stats bands, and spotlight sections for closer design parity.
- See the root `README.md` for setup, implementation order, editable section details, and validation notes.
