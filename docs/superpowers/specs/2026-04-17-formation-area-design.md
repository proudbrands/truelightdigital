# Formation Area — Design Spec

**Date:** 2026-04-17
**Theme:** `bootscore-child` (True Light Digital)
**Brief source:** Sean's "Brief: The Formation Area" (draft v2) + brainstorm with Claude Code on 2026-04-17
**Target site:** `truelight.digital` (local: `church-2.local`)

---

## Goal

Ship a structured learning zone called **Formation** on `truelight.digital`. Four pillars. At launch: **4 seeded cornerstone essays** (~3,500–4,500 words each, one per pillar, written by Sean + Claude Project) **plus 3 migrated blog posts** (one promoted to cornerstone in the Communications Champion pillar; two short reads in the Guardrails & Discernment pillar). Total: **7 pieces across 4 pillars at launch** — 5 cornerstones, 2 short reads. This replaces the existing Blog as the agency's primary editorial surface. Formation is the agency's public thinking — it is what defensibility looks like for True Light Digital.

## Decisions log

| # | Decision | Choice |
|---|---|---|
| Q1 | Formation vs existing Blog | **Absorb.** Blog retires; 3 of 5 posts migrate to Formation, 2 stay as `post` type with no nav entry (min-surface). |
| Q2 | Nav placement | **Promoted.** Order: Formation \| Services \| Our Customers \| About \| Contact \| Book a Call CTA. |
| Q3 | Downloads CPT | **Extend `tld_resource`.** Add `pillar` taxonomy to the existing Resources library; no new `download` CPT; no `/formation/downloads/` in Phase 1. |
| Q4 | ESP / capture | **Gravity Forms only for MVP.** HubSpot (already paid for) integration deferred until volume warrants it. |
| Q5 | Analytics | **Matomo** (already self-hosted and tracking; plugin active). |
| Q6 | Content pipeline | **One-off seed** from `documents/*.md`. No WP-CLI, no admin importer, no ongoing sync. Gutenberg is source of truth post-seed. |
| Q7 | Clergy-only visibility field | **Not built.** YAGNI; add when gating is real. |
| Approach | Scope framing | **Approach 1 — Skinny MVP.** Trims brief from 4 taxonomies/9 blocks/4 phases to 2 taxonomies/3 blocks/1 phase. |

## Pillars (final)

Numbered by `pillar_sort_order` (stored on the term):

1. **The Communications Champion** — slug `communications-champion`
2. **Rhythm & Restraint** — slug `rhythm-and-restraint` *(renamed from brief's "Hierarchy & Harmony")*
3. **Invitation & Patience** — slug `invitation-and-patience` *(renamed from brief's "Buy-in Without Burden")*
4. **Guardrails & Discernment** — slug `guardrails-and-discernment`

Hero taglines and meta descriptions are authored and provided; stored as ACF term fields (§6).

---

## §1 — Information architecture & URL structure

```
/formation/                                      Landing (WP page + page template)
/formation/<pillar-slug>/                        Pillar archive (taxonomy-pillar.php)
/formation/<pillar-slug>/<piece-slug>/           Single piece (single-formation_piece.php)

/resources/                                      Existing Resources library (unchanged)
/resources/category/<slug>/                      Existing (unchanged)

/blog/                                           Remaining 2 posts (post type = post)
/blog/what-is-a-faith-driven-entrepreneur/       Remains
/blog/christian-business-coaching/               Remains

/blog/how-churches-are-using-ai        → 301 → /formation/guardrails-and-discernment/how-churches-are-using-ai/
/blog/church-seo-guide                 → 301 → /formation/communications-champion/church-seo-guide/
/blog/catholic-ai-guide                → 301 → /formation/guardrails-and-discernment/catholic-ai-guide/
```

No blanket `/blog/*` fallback redirect — the 2 retained posts keep `/blog/` valid.

### Rewrite mechanics

- CPT `formation_piece` registered with `rewrite => ['slug' => 'formation/%pillar%', 'with_front' => false]`
- `add_rewrite_tag('%pillar%', '([^/]+)', 'pillar=')` to expose the tag
- Filter `post_type_link` to substitute `%pillar%` with the piece's assigned pillar term slug. Fallback (never expected to fire): `uncategorised-pillar` if a piece has no pillar assigned
- Taxonomy `pillar` registered with `rewrite => ['slug' => 'formation', 'with_front' => false, 'hierarchical' => false]`
- `page-formation.php` template assigned to a regular WP page with slug `formation` at `/formation/`

### Sitemap

The SEO Framework auto-includes the CPT and taxonomy archive once both are `public => true, show_in_rest => true`. Verify post-deploy.

---

## §2 — Data model

### 2.1 Custom post type: `formation_piece`

```php
register_post_type('formation_piece', [
  'labels'        => [
    'name'               => 'Formation',
    'singular_name'      => 'Piece',
    'add_new_item'       => 'Add New Piece',
    'edit_item'          => 'Edit Piece',
    'view_item'          => 'View Piece',
    'search_items'       => 'Search Pieces',
    'menu_name'          => 'Formation',
  ],
  'public'        => true,
  'show_in_rest'  => true,
  'has_archive'   => false,
  'menu_icon'     => 'dashicons-book-alt',
  'menu_position' => 23,
  'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
  'rewrite'       => ['slug' => 'formation/%pillar%', 'with_front' => false],
  'taxonomies'    => ['pillar', 'piece_type'],
]);
```

### 2.2 Taxonomies

| Name | Hierarchical | Rewrite | UI enforcement | Seeded terms |
|---|---|---|---|---|
| `pillar` | no | `/formation/<slug>/` | ACF radio + hide native meta box | `communications-champion`, `rhythm-and-restraint`, `invitation-and-patience`, `guardrails-and-discernment` |
| `piece_type` | no | not publicly rewritten | ACF radio + hide native meta box | `cornerstone`, `short-read`, `field-note` |

Single-select enforced via ACF Taxonomy field type (`field_type = radio`, `save_terms = true`) combined with `remove_meta_box` on the native taxonomy UI. `Template` and `Course` terms deferred until those piece types exist.

### 2.3 ACF field groups

Registered via ACF admin UI, persisted to `acf-json/` (existing theme convention — `functions.php` already sets `acf/settings/save_json` and `acf/settings/load_json`).

**Field group: "Formation Piece"** (location: `post_type == formation_piece`)

| Field name | Type | Required | Notes |
|---|---|---|---|
| `subtitle` | Text | no | Tagline under the title (the italic line from the markdown source) |
| `summary` | Textarea | **yes** | 2–3 sentences. Used in listings and as meta description on single piece pages |
| `pillar` | Taxonomy (radio, save_terms) | **yes** | Drives URL and archive membership |
| `piece_type` | Taxonomy (radio, save_terms) | **yes** | Drives template variant |
| `reading_time_minutes` | Number | no | Auto-calculated on save if empty (word_count / 225, rounded up) |
| `toc_enabled` | True/False | no | Conditional: show only when `piece_type == cornerstone`. Default: on. |

**Field group: "Pillar Term Fields"** (location: `taxonomy == pillar`)

| Field name | Type | Required | Notes |
|---|---|---|---|
| `pillar_tagline` | Textarea (~300 char soft limit) | yes | Rendered on pillar archive hero under term name |
| `pillar_meta_description` | Text (160 char soft limit) | yes | Served as meta description for the pillar archive via SEO Framework filter |
| `pillar_sort_order` | Number (1–99) | yes | Drives 01–04 numbering in Pillar Card block and archive hero |

### 2.4 Auto reading-time hook

```php
add_action('save_post_formation_piece', function ($post_id, $post, $update) {
  if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) return;
  if (get_field('reading_time_minutes', $post_id)) return;
  $wc = str_word_count(wp_strip_all_tags(get_post_field('post_content', $post_id)));
  update_field('reading_time_minutes', max(1, (int) ceil($wc / 225)), $post_id);
}, 20, 3);
```

### 2.5 Deferred fields (brief list, not built for Phase 1)

- `topic` taxonomy
- `audience` taxonomy
- `related_pieces` relationship (auto-populate at render time instead)
- `associated_downloads` relationship (deferred with the downloads phase)
- `pull_quotes` repeater (use inline core pullquote blocks, styled)
- `companion_email_sequence_id` (no ESP integration yet)
- `visibility` select (Q7)
- `table_of_contents_enabled` → promoted to `toc_enabled` above

Each is ~20 minutes to add back when a piece demands it.

---

## §3 — Templates

Three PHP templates. Each is a thin controller delegating to `get_template_part()` for composable sections.

### 3.1 `single-formation_piece.php`

Picks layout by `piece_type`:

**Cornerstone layout:**
- Full-bleed navy hero: pillar tag, reading time, published date, title (Playfair Display), subtitle, featured image below hero
- Two-column body: main column (max-width 680px) with `the_content()`, sticky ToC sidebar (`position: sticky; top: 1rem`) on `lg+` breakpoint
- ToC: auto-built in PHP by parsing `the_content()` rendered output for `<h2>` and `<h3>` tags via `preg_replace_callback`, injecting `id` attributes, emitting a nested list wrapped in `<nav aria-label="On this page">`. Active-section highlight via client-side IntersectionObserver (~2KB JS).
- ToC visibility: sticky sidebar on `lg+`, collapsed `<details>` at top of body on mobile
- Post-body: email capture (inline GF form with hidden `pillar_interest` field), related pieces row (3 auto-selected from same pillar, excluding current), breadcrumb link back to pillar

**Short Read / Field Note layout:**
- Compact hero: pillar tag, title, reading time (no featured image, no subtitle if empty)
- Single-column body (max-width 680px), no ToC
- Same post-body: email capture, related row, breadcrumb

### 3.2 `taxonomy-pillar.php`

- Pillar hero: navy background, gold accent numeral from `pillar_sort_order` (01–04), pillar name (Playfair), `pillar_tagline` (term ACF field), piece count (live query)
- Featured section: most recent `cornerstone` in this pillar, rendered as a full-width Piece Card variant
- Grid: remaining pieces, 2-col on `md`, 3-col on `lg`, Piece Card compact variant
- Client-side filter pills: `All`, `Cornerstone`, `Short Read`, `Field Note` — pure CSS via `data-*` attributes + a small JS toggle. Hidden if pillar has <3 pieces total
- Post-grid: pillar-scoped email capture

### 3.3 `page-formation.php`

- Assigned to a WP page with slug `formation`
- Template provides: `<main>` container, standard header/footer, and a content band that renders `the_content()`
- Landing page composition is in the block editor — author places intro paragraph (the approved landing copy), then 4 Pillar Card blocks, then optionally a featured Piece Card

### 3.4 Brand tokens (reused from existing theme)

- Playfair Display: h1, h2, h3 (switches to Inter below 24px per existing rule)
- Inter / Sen: body text, small UI
- Navy backgrounds: `--tld-navy-900: #0F2035` (hero), `--tld-navy-700: #1C3557` (body on dark)
- Gold accent: `#E6B849` — decorative only (left borders, accent numerals, CTA button bg). Never primary text on white.
- Navy-tinted shadows (existing convention)

---

## §4 — Blocks

Three ACF blocks + CSS-only styling of the core pullquote block.

### 4.1 Pillar Card — `acf/tld-pillar-card`

**Purpose:** author-placed on Formation landing page. Four cards, one per pillar.

**Fields:** `pillar` (taxonomy dropdown, required).

**Render:** card with navy-to-gold accent numeral (`pillar_sort_order`), pillar name, `pillar_tagline`, live piece count, arrow link to `/formation/<slug>/`.

**Render template:** `template-parts/blocks/tld-pillar-card.php`

### 4.2 What to do this week — `acf/tld-callout-action`

**Purpose:** inline inside cornerstone essays. Author-placed.

**Fields:**
- `label_override` (text, optional, default "What to do this week")
- `heading` (text, required)
- `intro` (textarea, optional)
- `steps` (repeater, min 1 max 5): each has `step_lead` (text, bold) + `step_detail` (textarea)

**Render:** gold-bordered cream box matching the cornerstone mockup.

**Render template:** `template-parts/blocks/tld-callout-action.php`

### 4.3 Piece Card — `acf/tld-piece-card`

**Purpose:** author-placed on agency homepage, service pages, etc., to hand-feature a specific piece. Also used as the `get_template_part()` rendering in pillar grids and related-pieces rows (shared render template).

**Fields:**
- `piece` (post object, filtered to `formation_piece`, required)
- `variant` (select: `default` / `cornerstone-featured` / `compact`, default `default`)
- `show_summary` (true/false, default true)

**Render:** pulls title, piece_type badge, pillar tag, reading time, summary, featured image, permalink.

**Render template:** `template-parts/blocks/tld-piece-card.php` (reused from pillar grid)

### 4.4 Pull quote — CSS-only

Style the core `wp-block-pullquote` and `wp-block-quote` when rendered inside `.formation-piece-body`: gold left border (`border-left: 3px solid #E6B849`), Playfair italic, `font-size: 1.25rem`, no new block.

### 4.5 Template-rendered (not blocks)

- Cornerstone hero — emitted by `single-formation_piece.php`
- Pillar grid piece cards — emitted by `taxonomy-pillar.php`
- Related pieces row — emitted at end of `single-formation_piece.php`
- Email capture (inline GF form with hidden `pillar_interest`) — emitted at end of single + pillar templates

Each is ~20 minutes to promote to a block later if authors want manual placement.

### 4.6 Block registration

`inc/acf-blocks.php` (existing file) gets three new `acf_register_block_type` entries. Each declares `'category' => 'formation'`.

New Gutenberg category registered via `block_categories_all` filter in the same file:

```php
add_filter('block_categories_all', function ($categories) {
  array_splice($categories, 1, 0, [[
    'slug'  => 'formation',
    'title' => 'Formation',
    'icon'  => 'book-alt',
  ]]);
  return $categories;
}, 10);
```

Result: the three Formation blocks appear grouped under a "Formation" category in the Gutenberg inserter.

---

## §5 — Integration with the existing site

### 5.1 Nav

Menu is WP-admin-managed (`main-menu` theme_location; see `header.php:70`). Single admin step during rollout:

```
Appearance → Menus → Main Menu
  - Drag order: Formation | Services | Our Customers | About | Contact
  - Remove: Blog
  - Save
```

Same treatment for `mobile-menu` location.

### 5.2 Blog absorption

**Migrate these 3 posts via Post Type Switcher plugin** (install → switch → uninstall):

| ID | Title | New pillar | New piece_type | Slug treatment |
|---|---|---|---|---|
| 118 | How Churches Are Using AI in 2026 | Guardrails & Discernment | Short Read | Keep existing `post_name` |
| 119 | Church SEO: The Complete Guide | Communications Champion | Cornerstone | Keep existing `post_name` |
| 120 | A Catholic Guide to AI | Guardrails & Discernment | Short Read | Keep existing `post_name` |

For each: switch post type, assign pillar + piece_type radios, write mandatory `summary`, confirm the `post_name` (slug) is unchanged — the redirect mu-plugin in §1 assumes slugs are preserved. Publish.

**Keep these 2 posts as `post` type** (min-surface, no nav item):

| ID | Title | Behaviour |
|---|---|---|
| 117 | What Is a Faith-Driven Entrepreneur? | Stays at `/blog/<slug>/`; reachable via services-page internal links + footer if desired |
| 121 | Christian Business Coaching: What It Is and How It Works | Same |

**Redirect mu-plugin:** `wp-content/mu-plugins/tld-formation-redirects.php` — explicit map for the 3 migrated slugs → Formation URLs. No blanket fallback. Written as a mu-plugin so it survives theme switches.

### 5.3 Extend `tld_resource`

Single change in `inc/custom-post-types.php`:

```diff
-  'taxonomies' => ['resource_category'],
+  'taxonomies' => ['resource_category', 'pillar'],
```

Plus the `pillar` taxonomy's `object_type` list includes `tld_resource`. No new ACF fields on `tld_resource`; no `/formation/downloads/` page; no email-gating.

---

## §6 — Seed strategy

One-off script. Deletable after use.

### 6.1 Script

**Location:** `wp-content/mu-plugins/tld-formation-seed.php` (mu-plugin, deleted after successful run)

**Behaviour:**

1. On `admin_init`, check option `tld_formation_seeded_v1`. If truthy, exit.
2. Loop `C:\Users\Sean\Local Sites\church-2\app\public\documents\pillar-*.md` (path resolved via `ABSPATH . 'documents/'`)
3. For each file: parse YAML frontmatter (minimal regex, no Composer dep)
4. Map frontmatter:
   - `title` → `post_title`
   - `pillar: 01-communications-champion` → strip `NN-` prefix → assign `pillar` term by slug
   - `type: cornerstone` → assign `piece_type` term
   - `status: draft` → `post_status = draft`
   - `last_updated` → `post_modified`
5. Body processing:
   - Skip first `# H1` heading (duplicates title)
   - Extract next `*italic*` line as `subtitle` ACF field
   - Skip `---` horizontal rule
   - Convert remaining markdown → Gutenberg block markup
6. `wp_insert_post` (upsert by slug derived from `sanitize_title($title)`)
7. Seed the four `pillar` terms (if not already present) with names, taglines, meta descriptions, and sort order from a config array baked into the script (copy supplied by Sean)
8. Set option `tld_formation_seeded_v1 = true`
9. Emit admin notice listing created pieces + "Open each in Gutenberg for summary + polish. Delete this mu-plugin when done."

### 6.2 Pillar term config (baked into seed script)

```php
$pillars = [
  'communications-champion' => [
    'name'             => 'The Communications Champion',
    'sort_order'       => 1,
    'tagline'          => 'Every parish communication system that lasts has a named human at its centre, supported and replaceable. Most parishes have none of those things. This is how to fix it.',
    'meta_description' => 'How Catholic parishes find, form, and protect the people who carry their communications. Essays, templates, and frameworks for priests and parish secretaries.',
  ],
  'rhythm-and-restraint' => [
    'name'             => 'Rhythm & Restraint',
    'sort_order'       => 2,
    'tagline'          => 'A parish\'s communications are a form of breath, rising and falling with the liturgical year. Here is how to breathe with the Church instead of gasping against her.',
    'meta_description' => 'How Catholic parishes plan communications around the liturgical year. Editorial rhythm, seasonal restraint, and the discipline of when not to publish at all.',
  ],
  'invitation-and-patience' => [
    'name'             => 'Invitation & Patience',
    'sort_order'       => 3,
    'tagline'          => 'Parishes change slowly, through relationship, by invitation. Here is how to help a parish change well, without breaking the people you are trying to serve.',
    'meta_description' => 'Change management for Catholic parishes, without the corporate playbook. How priests, volunteers, and agencies can help parishes change at the pace parishes actually work.',
  ],
  'guardrails-and-discernment' => [
    'name'             => 'Guardrails & Discernment',
    'sort_order'       => 4,
    'tagline'          => 'Parish communications are speech on behalf of the Body of Christ, and the most important skill is the pause before speech. Here is how to hold that discipline.',
    'meta_description' => 'Safeguarding, data protection, AI, and editorial discernment in Catholic parish communications. The discipline of speaking carefully on behalf of the Body of Christ.',
  ],
];
```

### 6.3 Markdown → Gutenberg converter

Hand-rolled, ~100 lines of PHP. No Composer dependency.

**Supported:**
- Paragraphs → `<!-- wp:paragraph --><p>...</p><!-- /wp:paragraph -->`
- `## h2` / `### h3` → `<!-- wp:heading {"level":N} --><hN>...</hN><!-- /wp:heading -->`
- Ordered and unordered lists → `<!-- wp:list -->` with correct `ordered` attribute
- Blockquotes → core quote block
- `**bold**`, `*italic*`, `[links](url)`
- `---` separators → `<!-- wp:separator -->`

**Not supported** (manual post-seed polish — ~5 min per piece):
- Images (featured + inline) — author sets in Gutenberg
- "What to do this week" sections — author promotes heading+list unit to Callout block
- Pull-worthy quotes — author promotes blockquote to core pullquote
- Tables

### 6.4 Post-seed checklist per piece (~5 min)

1. Open in Gutenberg, verify block structure
2. Write the 2–3 sentence `summary` ACF field (mandatory before publish)
3. Set featured image
4. Promote any "What to do this week" sections to Callout blocks
5. Promote pull-worthy quotes to core pullquote blocks
6. Change `post_status` to `publish` when ready

### 6.5 Cleanup

- Delete `wp-content/mu-plugins/tld-formation-seed.php`
- Keep `documents/` as archived markdown source

---

## §7 — Non-functional requirements

### 7.1 Stack already active (confirmed via `wp_options.active_plugins`)

- Gravity Forms, ACF Pro, Matomo, The SEO Framework (`autodescription`), Imagify, Post SMTP, Leadin (HubSpot tracking already present — out of scope for Formation), migration tooling.

No new plugins required for Phase 1. Post Type Switcher is installed temporarily during blog migration and removed after.

### 7.2 Performance (target: LCP < 1.5s on mid-range mobile, 4G)

- Hero images via `wp_get_attachment_image()` with `sizes="(min-width:1200px) 1200px, 100vw"`; existing `tld-hero` size (1920×800) used
- Cornerstone hero: `loading="eager"` + `fetchpriority="high"`; all other images `loading="lazy"`
- Imagify handles WebP/AVIF conversion automatically
- Fonts self-hosted in `assets/css/fonts.css`; preload directives for Playfair 700 (h1) and Inter 400 (body)
- One shared JS file for ToC scrollspy + filter pills (≤5KB minified). No other JS added
- No caching plugin in Phase 1 — Rocket.net edge caching handles it. Revisit only if measurement shows LCP >1.5s

### 7.3 Accessibility (WCAG 2.2 AA)

- Existing skip-to-content link in `functions.php:110` — verify it lands on `#primary` inside Formation templates
- Heading hierarchy: h1 = piece title (hero), h2/h3 inside body. Markdown converter enforces no h1 in `post_content`
- ToC sidebar: `<nav aria-label="On this page">`, each link targets matching heading `id`, active section uses `aria-current="location"`
- Mobile ToC uses native `<details>` / `<summary>` — accessible by default
- Piece-type badge uses text ("Cornerstone" / "Short Read" / "Field Note"), never colour alone
- Filter pills on pillar archive: `aria-pressed` state, keyboard-navigable
- **Contrast audit** (against brand tokens):
  - Navy `#1C3557` on white → 11.26:1 ✓ AAA (body)
  - Gold `#E6B849` on navy `#0F2035` → 7.8:1 ✓ AAA (accent on dark)
  - Gold `#E6B849` on white → 2.09:1 ✗ FAIL — gold is decorative accent only (left borders, numerals, CTA button background). Never primary text on light
- Email capture form (inline GF) inherits existing `.gform_wrapper` SCSS from Discovery Call modal — already production-tested

### 7.4 SEO (delegated to The SEO Framework, except three custom filters)

- CPT + taxonomy both `public => true, show_in_rest => true` → automatic sitemap inclusion (verify post-deploy)
- The SEO Framework auto-emits: canonical, OG tags, Twitter cards, `Article` schema, breadcrumb schema

**Custom filters (theme-side, roughly three small functions):**

1. **Single piece meta description** — prefer ACF `summary` over auto-generated excerpt. Hook The SEO Framework's description filter, check `is_singular('formation_piece')`, return `get_field('summary')`.
2. **Pillar archive meta description** — return `get_field('pillar_meta_description', 'pillar_' . $term_id)` when `is_tax('pillar')`.
3. **Breadcrumb structure** — ensure breadcrumb shows `Home › Formation › <Pillar> › <Piece>` on single pages and `Home › Formation › <Pillar>` on pillar archives. The SEO Framework uses hierarchy by default; CPT + custom taxonomy may need a small adjustment.

### 7.5 Redirects

Per §5.2 — explicit map for 3 migrated slugs. No blanket fallback. Verify 301 status with `curl -I` after deploy.

### 7.6 Analytics

Matomo plugin already active. Formation pageviews tracked automatically. Optional custom goal: GF form submission on Formation pages — deferred; revisit if capture volume warrants attribution detail.

---

## Open items for implementation (not blocking design)

- Configure The SEO Framework to surface Formation CPT in its admin UI (one toggle in plugin settings)
- Preload font-face declarations for Playfair 700 and Inter 400 — verify current `fonts.css` has them
- Decide featured images per cornerstone (author picks during Gutenberg polish step)
- Confirm Rocket.net edge caching doesn't interfere with draft-preview rendering during seed polish

## Out of scope (from brief, deferred)

- `topic` and `audience` taxonomies
- `related_pieces` and `associated_downloads` ACF relationship fields
- `pull_quotes` repeater field
- `companion_email_sequence_id` field
- `visibility` select field (`public` / `clergy` / `diocesan-staff`)
- Scripture / Magisterium Reference block
- Download Card block + email-gated downloads + `/formation/downloads/` index page
- Search UI (default WP search stub is fine)
- Admin markdown importer
- Ongoing markdown→WP sync pipeline
- WP-CLI import command
- Course piece_type + `course_modules` field
- Pillar-specific email capture block (template-rendered instead)
- Dedicated Cornerstone Hero block (template-rendered instead)
- Comments
- Social share buttons beyond Copy Link / Email
- HubSpot forms + tracking on Formation pages (Leadin tracking that's already on the site is unchanged)

Each is explicitly re-addable later when a piece or workflow demands it. Rough effort: ~20 min per field, ~1 hr per block.

## File inventory (what gets created or modified)

### New files

```
bootscore-child/single-formation_piece.php
bootscore-child/taxonomy-pillar.php
bootscore-child/page-templates/page-formation.php
bootscore-child/template-parts/blocks/tld-pillar-card.php
bootscore-child/template-parts/blocks/tld-callout-action.php
bootscore-child/template-parts/blocks/tld-piece-card.php
bootscore-child/template-parts/formation/cornerstone-hero.php
bootscore-child/template-parts/formation/compact-hero.php
bootscore-child/template-parts/formation/pillar-hero.php
bootscore-child/template-parts/formation/toc.php
bootscore-child/template-parts/formation/email-capture.php
bootscore-child/template-parts/formation/related-pieces.php
bootscore-child/inc/formation/cpt-formation-piece.php
bootscore-child/inc/formation/taxonomies.php
bootscore-child/inc/formation/reading-time.php
bootscore-child/inc/formation/toc-builder.php
bootscore-child/inc/formation/seo-filters.php
bootscore-child/assets/scss/_formation.scss
bootscore-child/assets/js/formation.js
bootscore-child/acf-json/group_formation_piece.json
bootscore-child/acf-json/group_pillar_term.json
bootscore-child/acf-json/group_block_pillar_card.json
bootscore-child/acf-json/group_block_callout_action.json
bootscore-child/acf-json/group_block_piece_card.json
wp-content/mu-plugins/tld-formation-seed.php         (deleted after seed run)
wp-content/mu-plugins/tld-formation-redirects.php    (permanent)
```

### Modified files

```
bootscore-child/functions.php                  (load new inc/formation/*.php files)
bootscore-child/inc/custom-post-types.php      (add 'pillar' to tld_resource taxonomies)
bootscore-child/inc/acf-blocks.php             (register 3 new blocks + 'formation' category)
bootscore-child/assets/scss/_bootscore_custom.scss  (import _formation.scss)
```

### Admin-only changes (no code)

- Main menu reorder (remove Blog, add Formation, promote to first)
- Mobile menu match
- Posts 118/119/120 converted via Post Type Switcher, pillars + piece types assigned, summary written
- Per-pillar term edits if Sean wants to tweak taglines post-launch
- Sitemap ping to Google Search Console after go-live

## Success criteria

1. `/formation/` loads and renders the four Pillar Cards
2. `/formation/<each-pillar>/` loads, shows hero with tagline, grid of pieces
3. `/formation/communications-champion/<cornerstone-slug>/` loads with:
   - Correct hero (pillar tag, reading time, title, subtitle, featured image)
   - Working sticky ToC on desktop, `<details>` collapse on mobile
   - All four seeded cornerstones published
4. `/blog/<migrated-slug>/` returns HTTP 301 to the correct Formation URL (verified via `curl -I`)
5. `/blog/` still resolves with 2 remaining posts
6. Main nav shows: Formation \| Services \| Our Customers \| About \| Contact
7. Matomo records pageviews for Formation pages
8. Lighthouse score ≥90 on the seeded cornerstone (Performance, Accessibility, SEO)
9. Meta description in Google's search preview on a seeded cornerstone shows the ACF `summary` text (verified via `site:truelight.digital` search after indexing)
10. A pillar meta description edit in WP admin takes effect on next page render with no deploy

---

*End of design spec. Next step: invoke `superpowers:writing-plans` to produce the implementation plan.*
