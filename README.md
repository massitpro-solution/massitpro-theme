# Mass IT Pro Theme Refactor

## Current Pass Summary
- This pass continues the native WordPress refactor with a design-parity focus on the shared frontend renderers.
- Shared sections now use more purpose-built layouts instead of defaulting to the same generic white card/grid pattern.
- Live output reads from page title, page excerpt, page content, featured image, native page meta, and CPT queries.
- Native meta boxes are now active for:
  - homepage
  - services hub
  - business landing
  - residential landing
  - industries hub
  - locations hub
  - about page
  - contact page
  - service detail pages
  - industry detail pages
  - location detail pages
- Canonical routing remains driven by `docs/canonical-urls.md`.
- Shared templates remain shared. No one-PHP-file-per-page approach was added.

## Required Software
- WordPress 6.x
- PHP 7.4 or newer
- MySQL or MariaDB
- A local WordPress environment such as Local, Laragon, XAMPP, MAMP, or Docker

## Required Plugins
- None

## No Build Step
- No Node, npm, Composer, or asset build step is required for this theme in its current state.
- No CSS or JS lint command is configured in this repo.
- PHP lint must be run on every modified PHP file after each implementation pass.

## Native Editing Model
- Shared layout stays in the theme's existing shared templates and render functions.
- Per-page content comes from:
  - page title
  - page excerpt
  - page content
  - featured image
  - native page meta stored with `register_post_meta()`
  - CPT queries for projects, testimonials, FAQs, and blog posts where appropriate
- Native section data is stored one section at a time on the `page` post type using JSON meta keys in this format:
  - `massitpro_<section_key>`
- The first-pass native section storage and save flow live in:
  - `massitpro-theme/inc/meta-helpers.php`
  - `massitpro-theme/inc/meta-boxes.php`
- If a section payload is empty, the renderer omits that section completely.
- No placeholder copy, placeholder cards, or blueprint arrays should drive live page sections.

## Current Native Meta Boxes
- The native meta box appears only when the page context matches one of these supported page types:
  - `front-page`
  - `services-hub`
  - `services-business`
  - `services-residential`
  - `industries-hub`
  - `locations-hub`
  - `about`
  - `contact`
  - `service-detail`
  - `industry-detail`
  - `location-detail`
- The page context still comes from canonical routing helpers in `massitpro-theme/inc/canonical.php` and `massitpro-theme/page.php`.

## Section Keys Added This Pass

### Homepage Meta Box
- Context key: `front-page`
- Section keys:
  - `hero`
  - `trust_strip`
  - `stats_section`
  - `core_services_section`
  - `services_carousel_section`
  - `why_choose_section`
  - `industries_section`
  - `locations_section`
  - `projects_section`
  - `testimonials_section`
  - `secondary_services_section`
  - `blog_section`
  - `faq_section`
  - `cta_block`

### Service Detail Meta Box
- Context key: `service-detail`
- Section keys:
  - `hero`
  - `intro_section`
  - `deliverables_section`
  - `capabilities_section`
  - `process_section`
  - `ideal_for_section`
  - `related_services_section`
  - `related_projects_section`
  - `related_testimonials_section`
  - `faq_section`
  - `cta_block`

### Industry Detail Meta Box
- Context key: `industry-detail`
- Section keys:
  - `hero`
  - `overview_section`
  - `pain_points_section`
  - `recommended_services_section`
  - `sub_clusters_section`
  - `compliance_section`
  - `featured_project_section`
  - `faq_section`
  - `related_links_section`
  - `cta_block`

### Location Detail Meta Box
- Context key: `location-detail`
- Section keys:
  - `hero`
  - `overview_section`
  - `why_local_section`
  - `available_services_section`
  - `served_industries_section`
  - `trust_cards_section`
  - `faq_section`
  - `related_links_section`
  - `cta_block`

### Services Hub Meta Box
- Context key: `services-hub`
- Section keys:
  - `hero`
  - `business_services_section`
  - `why_choose_section`
  - `residential_services_section`
  - `web_design_spotlight`
  - `process_section`
  - `cta_block`

### Business And Residential Landing Meta Boxes
- Context keys:
  - `services-business`
  - `services-residential`
- Section keys:
  - `hero`
  - `intro_section`
  - `services_section`
  - `benefits_section`
  - `process_section`
  - `faq_section`
  - `cta_block`

### Industries Hub Meta Box
- Context key: `industries-hub`
- Section keys:
  - `hero`
  - `intro_section`
  - `featured_industries_section`
  - `value_cards_section`
  - `compliance_cards_section`
  - `featured_project_section`
  - `cta_block`

### Locations Hub Meta Box
- Context key: `locations-hub`
- Section keys:
  - `hero`
  - `intro_section`
  - `featured_locations_section`
  - `service_highlights_section`
  - `local_advantage_section`
  - `cta_block`

### About Page Meta Box
- Context key: `about`
- Section keys:
  - `hero`
  - `intro_section`
  - `stats_section`
  - `value_cards_section`
  - `process_section`
  - `focus_section`
  - `cta_block`

### Contact Page Meta Box
- Context key: `contact`
- Section keys:
  - `hero`
  - `trust_cards_section`
  - `process_section`
  - `coverage_section`
  - `cta_block`

## Field Structure In This Pass

### Hero Sections
- Fields:
  - hero eyebrow
  - hero title override
  - hero subtitle
  - hero image
  - button 1 label
  - button 1 URL
  - button 1 style
  - button 1 size
  - button 2 label
  - button 2 URL
  - button 2 style
  - button 2 size

### Intro And Overview Sections
- Fields:
  - section heading
  - section body
  - section image

### Simple List Sections
- Fields:
  - section heading
  - section body
  - newline-separated item list

### Card Sections
- Fields vary by section and may include:
  - eyebrow
  - heading
  - body
  - icon
  - title
  - description/body
  - image
  - link label
  - link URL

### Process Sections
- Fields:
  - section heading
  - section body
  - repeated steps
  - each step supports step label, title, and body

### Relationship Sections
- Fields:
  - section heading
  - section body
  - selected related items from:
    - canonical service pages
    - canonical industry pages
    - canonical location pages
    - `project` CPT
    - `testimonial` CPT
    - `faq_item` CPT
    - blog posts

### Featured Project Sections
- Fields:
  - section heading
  - section body
  - one selected `project` post

### Spotlight Sections
- Fields:
  - eyebrow
  - section heading
  - section body
  - section image
  - link label
  - link URL

### Related Links Sections
- Fields:
  - section heading
  - section body
  - repeated link label
  - repeated link URL
  - repeated description

### CTA Sections
- Fields:
  - CTA eyebrow
  - CTA heading
  - CTA body
  - CTA image
  - button 1 label
  - button 1 URL
  - button 1 style
  - button 1 size
  - button 2 label
  - button 2 URL
  - button 2 style
  - button 2 size

## Shared Renderer Behavior In This Pass
- Hero title falls back to the page title if the hero title override is empty.
- Hero subtitle falls back to the page excerpt if the hero subtitle is empty.
- Intro and overview body content can use page content when the explicit section body is empty.
- Explicit section images can fall back to the current page featured image where the shared renderer supports it.
- Empty sections are omitted.
- Shared templates now read native meta via `massitpro_get_section_meta()` instead of ACF helpers.
- Shared renderer layouts now include:
  - split image/content panels
  - alternating feature rows
  - premium image card grids
  - stats bands
  - pill-based location groupings
  - dark featured-project and CTA shells
  - stronger FAQ split layout

## ACF Refactor Map
- Removed:
  - `massitpro-theme/inc/acf-fields.php`
  - `massitpro-theme/inc/acf-helpers.php`
- Replaced/refactored:
  - `massitpro-theme/functions.php`
    - now loads `inc/meta-helpers.php` and `inc/meta-boxes.php`
    - no longer loads ACF files
  - `massitpro-theme/inc/render.php`
    - replaces `massitpro_get_acf_field()` calls with `massitpro_get_section_meta()`
    - keeps shared rendering logic in the same shared template flow
  - `massitpro-theme/inc/core.php`
    - removes ACF-style option assumptions for header/footer helpers
  - `README.md`
    - now documents the native WordPress model instead of ACF Pro

## Exact Implementation Order
1. Canonical routing pass
   - keep exact URLs from `docs/canonical-urls.md`
   - keep shared context detection in `page.php`
2. Native meta foundation
   - add shared meta helpers
   - register native page meta
   - add native page meta boxes for the first-pass contexts
3. Homepage structure pass
   - keep shared homepage renderer
   - load homepage sections from native meta and real CPT queries only
4. Service detail pass
   - keep one shared service detail renderer
   - allow unique per-page hero, intro, lists, cards, FAQs, related content, and CTA from native meta
5. Industry detail pass
   - keep one shared industry detail renderer
   - allow unique per-page overview, pain points, related services, project, FAQs, related links, and CTA from native meta
6. Location detail pass
   - keep one shared location detail renderer
   - allow unique per-page overview, why-local, related services, related industries, FAQs, related links, and CTA from native meta
7. Design-parity pass
   - redesign shared components, split layouts, and card hierarchy to match the source-reference structure more closely
8. Next pass: CPT native meta refinements
   - add native meta boxes to `project`, `testimonial`, and `faq_item` if those archive/detail views need more structured admin data

## What Is Editable After This Pass

### Homepage
- Hero
- Trust strip
- Stats
- Core services section
- Services carousel section
- Why choose cards
- Industries section
- Locations section
- Projects section
- Testimonials section
- Secondary services cards
- Blog section
- FAQ section
- CTA block

### Services Hub
- Hero
- Business service rows
- Why choose cards
- Residential services
- Web design spotlight
- Process steps
- CTA block

### Business And Residential Landing Pages
- Hero
- Intro
- Service relationships
- Benefits cards
- Process steps
- FAQ section
- CTA block

### Industries Hub
- Hero
- Intro
- Featured industries
- Value cards
- Compliance cards
- Featured project
- CTA block

### Locations Hub
- Hero
- Intro
- Featured locations
- Service highlights
- Local advantage cards
- CTA block

### About Page
- Hero
- Mission intro
- Stats
- Value cards
- Process steps
- Massachusetts focus spotlight
- CTA block

### Contact Page
- Hero
- Contact lead content via page content and featured image
- Trust cards
- Process steps
- Coverage spotlight
- CTA block

### Service Detail Pages
- Hero
- Intro
- Deliverables list
- Capabilities cards
- Process steps
- Ideal-for list
- Related services
- Related projects
- Related testimonials
- FAQ section
- CTA block

### Industry Detail Pages
- Hero
- Overview
- Pain points cards
- Recommended services
- Sub-clusters
- Compliance cards
- Featured project
- FAQ section
- Related links
- CTA block

### Location Detail Pages
- Hero
- Overview
- Why-local cards
- Available services
- Served industries
- Trust cards
- FAQ section
- Related links
- CTA block

## What Still Needs Manual Content Entry
- All real production copy
- All real production images
- Homepage section content and relationships
- Services hub, landing, About, and Contact section content and relationships
- Service detail section content and relationships
- Industry detail section content and relationships
- Location detail section content and relationships
- Real `project`, `testimonial`, and `faq_item` content
- Blog posts

## What Is Not Yet Native-Meta-Driven In This Pass
- CPT-specific native meta boxes for `project`, `testimonial`, and `faq_item`

## Manual WordPress Steps To Preview This Pass
1. Activate the `Mass IT Pro Theme`.
2. Confirm the local site already contains the real canonical pages from `docs/canonical-urls.md`.
3. Do not create substitute pages or change slugs.
4. In `Settings > Reading`, set:
   - front page = the existing Home page
   - posts page = the existing Blog page
5. Edit the homepage and fill:
   - title
   - excerpt
   - featured image
   - page content where needed
   - the native homepage meta box sections added in this pass
6. Edit canonical service detail pages under `/services/...` and fill:
   - title
   - excerpt
   - featured image
   - page content where needed
   - the native service detail meta box sections added in this pass
7. Edit canonical industry detail pages under `/industries/...` and fill:
   - title
   - excerpt
   - featured image
   - page content where needed
   - the native industry detail meta box sections added in this pass
8. Edit canonical location detail pages under `/it-support-across-massachusetts-service-areas/...` and fill:
   - title
   - excerpt
   - featured image
   - page content where needed
   - the native location detail meta box sections added in this pass
9. Edit these additional shared page types and fill their native meta box sections as needed:
   - `/services/`
   - `/services/business/`
   - `/services/residential/`
   - `/industries/`
   - `/it-support-across-massachusetts-service-areas/`
   - `/about-it-company-in-massachusetts/`
   - `/contactus/`
10. Create or import the real `project`, `testimonial`, `faq_item`, and `post` entries you want to select in relationship sections.
11. Assign a Primary menu only if you want to override the canonical fallback navigation.
12. Preview the site. Any empty native section stays hidden on purpose.

## Files Changed In This Pass
- `README.md`
- `massitpro-theme/README.md`
- `massitpro-theme/assets/css/app.css`
- `massitpro-theme/inc/render.php`
- `massitpro-theme/inc/meta-boxes.php`

## Validation Commands Run
- `Get-Content AGENTS.md`
- `Get-Content docs/canonical-urls.md`
- `git status --short`
- `php -l massitpro-theme/functions.php`
- `php -l massitpro-theme/inc/core.php`
- `php -l massitpro-theme/inc/render.php`
- `php -l massitpro-theme/inc/meta-boxes.php`
- `php -l massitpro-theme/inc/meta-helpers.php`
- final `git status --short`

## Known Limits And Unverified Items
- This repo does not include the live WordPress database, so existing page content, CPT content, and menu assignments cannot be verified here.
- The canonical routing/helpers are implemented in code, but the actual matching page tree still depends on your local WordPress content.
- Browser-level visual QA against the live/local WordPress site was not possible from this terminal-only environment.
