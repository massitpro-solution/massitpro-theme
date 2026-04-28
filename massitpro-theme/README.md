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

---

## Homepage Redesign — Things You Must Do After Deployment

### 1. Hero — Tech Background Animation
The particle animation is CSS-only and already live. If you want a more advanced canvas-based animation (moving lines connecting nodes), install a lightweight library like **tsParticles** or add a `<canvas>` element:

1. Upload a particles config JSON to `/assets/js/particles.json`
2. Add `<script src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"></script>` before `</body>` in `footer.php`
3. Add an init script that targets `#page-hero-canvas`

### 2. Trusted Companies — Adding Your Real Logos
1. Create the folder `massitpro-theme/assets/images/partners/`
2. Add PNG logos named exactly (lowercase, hyphenated):
   - `dell.png`, `microsoft.png`, `comcast.png`, `asus.png`, `ring.png`
   - `sophos.png`, `tp-link.png`, `wordpress.png`, `wix.png`, `intel.png`
   - `cisco.png`, `lastpass.png`, `google.png`, `godaddy.png`
3. Recommended size: **240 × 80 px**, transparent PNG background
4. In **WP Admin → Homepage → Trusted Companies**, set the **Heading** and optionally override the **Eyebrow** label. The logos load automatically from the folder; the admin items are used only for headings.
5. If a logo file is missing, the company name is shown as a text badge instead automatically.

**Navy overlay + hover true color** is handled entirely by CSS (`.hp-trust-ticker__logo` `filter` property). No extra work needed.

### 3. Why Trust Us — Admin Fields (Stats section)
In WP Admin → Pages → Home → **Why Trust Us** section:
- Set **Eyebrow**, **Heading**, and **Body** (centered automatically)
- For each of the **4 stats cards**: pick an **Icon**, fill **Value** (e.g. `< 15 min`), **Label** (e.g. `Average Response`), and **Description** (short supporting line).
- No URL/link fields — these are stats cards, not link cards.

### 4. Business Services — Admin Fields
In WP Admin → Pages → Home → **Business Services** section:
- Icons have been removed from the cards as requested.
- Each card: **Title**, **Body**, **Link Label** (e.g. "Learn More"), **Link URL** (must match a service page path so the featured image is pulled from that page automatically).
- The "View Business Services" button links to your `/services/` hub page automatically.

### 5. Other Services — Admin Fields
In WP Admin → Pages → Home → **Other Services** section:
- Set section **Eyebrow**, **Heading**, **Body** (centered automatically)
- **Item 1** = Residential Services card, **Item 2** = Web & Digital card
- For each card fill:
  - **Icon** (pick from dropdown)
  - **Title** (the h4 heading on the card)
  - **Body** (short description)
  - **Description** — enter up to 4 tags separated by commas, e.g.: `Network Setup, Security, WiFi, Hardware`
  - **Image** — upload the card image (shown at top of card)
  - **Link Label** + **Link URL** for the "Learn More" arrow

### 6. Industry Solutions — Admin Fields
In WP Admin → Pages → Home → **Industry Solutions** section:
- Works exactly like the location-detail template flip-card section.
- Each card: **Title**, **Body**, **Link URL** (the flip-card back will pull the industry page's featured image).

### 7. Service Coverage Areas — Admin Fields
In WP Admin → Pages → Home → **Service Coverage Areas** section:
- Set **Eyebrow**, **Heading**, **Body** (all centered)
- Each item: **Title** (location name) + **Link URL** (location page URL)
- These render as white clickable pills with a map-pin icon. No extra styling needed.

### 8. Featured Projects — Setting a Section Heading
The project cards pull automatically from your **Project** CPT (3 most recently ordered).
- Go to WP Admin → Pages → Home — there is **no** admin field for the Projects section heading on the homepage. The fallback heading "Featured Projects" is shown.
- To customise the heading, add a `projects_section` meta key or let me know and I'll add an admin field.

### 9. Testimonials — Slider
The slider shows the **3 most recently published** Testimonial posts automatically. No admin fields needed on the homepage — just make sure you have at least 3 published Testimonials in WP Admin → Testimonials.

### 10. Blog — Admin Fields
In WP Admin → Pages → Home → **Blog** section:
- Set **Eyebrow**, **Heading**, **Body**
- Optionally pin specific posts by checking them in **Featured Posts**
- **Latest Posts Count** controls how many to show (default 3)
- Excerpt truncation at 120 characters with expandable `...` is automatic.

### 11. CTA — Admin Fields
In WP Admin → Pages → Home → **CTA Block**:
- Same fields as all other CTAs. Everything is centered automatically.

### 12. FAQ Section
The FAQ section has been **removed** from the homepage as requested.

### 13. Stats & Why Choose Us Sections
Both sections have been **removed** from the homepage render as requested. Their admin fields still exist in the database (no data loss) but are no longer shown in the homepage meta box.
