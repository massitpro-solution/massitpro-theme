# AGENTS.md

## Project scope

* Only edit files inside `massitpro-theme/`, plus `README.md` if setup or instructions need updating.
* The file `source-reference/source-code.txt` is read-only reference source and must never be edited.
* The file `docs/canonical-urls.md` defines the exact live URL structure and is the routing source of truth.

## 

## Hard rules

* Do not create, seed, duplicate, insert, trash, rename, or re-parent any pages, menus, posts, CPT items, or settings.
* Do not invent cleaner slugs or cleaner URL hierarchy.
* Do not create one PHP file per page.
* Shared template and render files control layout.
* The source-reference file is design and layout reference only.
* Do not copy source-reference wording into production pages.
* Do not hardcode production copy into PHP.
* Do not hardcode production images from the source-reference file.
* Live content and images must come from WordPress admin data structures only.
* If a field is empty, omit that section.
* Do not inject placeholder cards, demo content, blueprint arrays, or generic fallback copy.
* Do not claim something is implemented unless you name the exact files changed and the exact commands run to validate them.

## 

## Admin editing rules

Each major page type must support unique section-level editing without touching code.

This means native editable fields must be available for page types such as:

* homepage
* service detail pages
* industry detail pages
* location detail pages
* hub/landing pages where needed
* All pages, and all sections and every content should be editable.No selections or options, no each and every content must be editable. The respective h1, h2, h3, h4, p, links and all should be given respectly. we need to rank number one. Every heading to eyebrow to description. Everything should be editabe on the admin side along with the images like in the source code. 

## 

## Architecture rules

* Preserve and refactor the existing theme instead of replacing it.
* Use shared page-type templates only:

  * homepage
  * service detail pages
  * industry detail pages
  * location detail pages
  * hub pages
  * about/contact/general pages
* Business and Residential are landing pages only.
* The page `/services/remote-it-support-services/` is one shared page and may appear in both business and residential groupings.
* Testimonials, FAQs, and Projects remain top-level pages even if grouped visually under About in navigation.

## 

## SEO and semantic structure rules

\- dont worry about the content just put something in, make sure all the content is editable!! I use Rank Math Pro for seo title, description and keywords fyi. 



## Working method

* Go page type by page type and section by section.
* Validate after each pass.
* Update `README.md` every pass with:

  * what changed
  * required software
  * required plugins if any
  * exact local setup
  * exact manual WordPress steps to preview the full version
  * what fields were added
  * what still needs manual content entry
  * known limitations

## 

