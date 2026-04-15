#### \# AGENTS.md

#### 

#### \## Project scope

#### 

#### \- Only edit files inside `massitpro-theme/`, plus root `README.md` and `massitpro-theme/README.md` if setup or implementation notes need updating.

#### \- `source-reference/source-code.txt` is read-only reference only and must never be edited.

#### \- `docs/canonical-urls.md` defines the exact live URL structure and is the routing source of truth.

#### \- This is a full-theme refinement pass, not a theme replacement.

#### 

#### \## Primary goal

#### 

#### Refine the existing theme so that all supported page types:

#### \- match the intended source-reference visual system and layout structure as closely as possible

#### \- keep the existing routing and shared-template architecture intact

#### \- expose all visible section content for admin editing through native WordPress fields and native meta boxes

#### 

#### \## Required page types in scope

#### 

#### All major supported page types are in scope for this pass, including:

#### \- homepage

#### \- hub and landing pages

#### \- about/contact/general pages

#### \- service detail pages

#### \- industry detail pages

#### \- location detail pages

#### \- testimonials page

#### \- FAQs page

#### \- projects page

#### \- blog-related templates if already supported by the theme

#### 

#### \## Hard rules

#### 

#### \- Do not create, seed, duplicate, insert, trash, rename, or re-parent any pages, menus, posts, CPT items, or settings.

#### \- Do not invent cleaner slugs or cleaner URL hierarchy.

#### \- Do not create one PHP file per page.

#### \- Do not replace the existing theme architecture.

#### \- Preserve shared page-type templates and shared renderers.

#### \- The source-reference file is design and layout reference only.

#### \- Do not copy source-reference wording into production pages.

#### \- Do not hardcode production copy into PHP.

#### \- Do not hardcode production images into PHP.

#### \- Live content and images must come from WordPress admin data structures only.

#### \- If a field is empty, omit that section entirely.

#### \- Do not inject placeholder cards, demo content, blueprint arrays, fake testimonials, fake projects, or generic fallback copy.

#### \- Do not claim something is implemented unless the exact files changed and exact validation commands run are named.

#### 

#### \## Content model rules

#### 

#### \- Do not require ACF Pro.

#### \- Use native WordPress fields and native meta boxes only.

#### \- Unique page content and images must come from:

#### &#x20; - page title

#### &#x20; - page excerpt

#### &#x20; - page content

#### &#x20; - featured image

#### &#x20; - native page meta fields

#### &#x20; - existing CPT content only where explicitly appropriate

#### 

#### \## Admin editing rules

#### 

#### \- Every visible section on every supported page type must support section-level manual editing from WordPress admin without touching code.

#### \- Every visible heading, eyebrow, subheading, paragraph, stat, badge, card title, card body, quote, attribution, button label, button URL, and section image must be editable from admin.

#### \- Do not use relationship selectors, featured-item dropdowns, checkbox pickers, or multi-select content assembly for visible page-building content.

#### \- Visible page-building content must be entered manually in native fields for each page.

#### \- Constrained presentation controls are allowed only where appropriate, such as button style, button size, layout variant, or icon slug, as long as visible content itself remains manually editable.

#### \- Reusable CPT archives may continue to exist where already supported, but visible page sections must not depend on selecting CPT items to build the page layout.

#### 

#### \## Architecture rules

#### 

#### \- Preserve and refactor the existing theme instead of replacing it.

#### \- Use shared page-type templates only:

#### &#x20; - homepage

#### &#x20; - service detail pages

#### &#x20; - industry detail pages

#### &#x20; - location detail pages

#### &#x20; - hub pages

#### &#x20; - about/contact/general pages

#### &#x20; - top-level utility pages such as testimonials, FAQs, and projects

#### \- Business and Residential are landing pages only.

#### \- `/services/remote-it-support-services/` is one shared page and may appear in both business and residential groupings.

#### \- Testimonials, FAQs, and Projects remain top-level pages even if grouped visually under About in navigation.

#### \- If a shared renderer is too generic for required design parity, refactor the shared renderer instead of hardcoding one-off page markup.

#### 

#### \## Design parity rules

#### 

#### \- Use `source-reference/source-code.txt` and the attached screenshots as the visual target.

#### \- Match the intended design system across all supported templates, including:

#### &#x20; - section order

#### &#x20; - layout hierarchy

#### &#x20; - spacing rhythm

#### &#x20; - card density

#### &#x20; - CTA structure

#### &#x20; - stat bands

#### &#x20; - split sections

#### &#x20; - spotlight layouts

#### &#x20; - footer continuity

#### \- Prioritize bringing shared page-type templates into parity rather than hand-tuning only a few individual pages.

#### \- Do not regress working layouts while improving incomplete ones.

#### 

#### \## Existing-code preservation rules

#### 

#### \- Keep working meta keys unless a change is truly required.

#### \- Prefer extending existing native meta structures over replacing them.

#### \- Do not remove working sections just because they are incomplete.

#### \- Do not rewrite unrelated templates.

#### \- Do not refactor unrelated CSS or JS without a direct reason tied to design parity or admin editability.

#### 

#### \## SEO and semantic structure rules

#### 

#### \- Maintain clean heading hierarchy.

#### \- Use semantic sections and accessible markup.

#### \- Keep content fields fully editable.

#### \- Do not hardcode SEO copy into PHP.

#### \- Rank Math Pro handles SEO title, description, and keywords separately.

#### 

#### \## Validation rules

#### 

#### \- After each pass:

#### &#x20; - run PHP lint on every modified PHP file

#### &#x20; - confirm no syntax errors

#### &#x20; - name the exact files changed

#### &#x20; - name the exact commands run

#### &#x20; - state what still needs manual WordPress content entry

#### &#x20; - state any known limitations still not solved

#### 

#### \## Working method

#### 

#### \- Start with a full audit of all supported page types, shared templates, shared renderers, and native meta structures.

#### \- Identify all mismatches between the current implementation and the source-reference system.

#### \- Group work by shared page type, not by isolated single pages.

#### \- Make the minimum necessary architectural changes to support fully editable section content and visual parity.

#### \- Preserve working code wherever possible.

#### \- Update both `README.md` files after each implementation pass with:

#### &#x20; - what changed

#### &#x20; - required software

#### &#x20; - required plugins if any

#### &#x20;

