# Formation landing page redesign

**Date:** 2026-04-20
**Status:** Brainstormed, awaiting review
**Scope:** `/formation/` page only (WP page ID 253, template `page-templates/page-formation.php`, Gutenberg-authored body)

---

## Problem

The current `/formation/` landing reads as underproduced for the brand:

- Hero is an empty navy band with the single word "Formation". The intro paragraph that should lede the page is orphaned in a narrow column 200px below.
- Pillar cards are fine structurally; kept as-is (user decision).
- "Start here by role" section sits in a two-column text/intro layout with a dead empty right column, and the audience entry-cards below are flat grey boxes with no visual affordance.
- No imagery anywhere despite the brand being about the warmth of pastoral life.

## Goals

1. Give the hero real content and a pastoral, photographic register.
2. Frame the two content sections (Pillars, Roles) with short editorial copy so visitors understand what they're choosing between.
3. Turn the audience cards into proper wayfinding — the primary secondary-navigation pattern on the page.
4. Do all of this without touching the other Formation templates (cornerstone, audience archive, pillar archive).

## Decisions taken during brainstorm

| Question | Decision |
|----------|----------|
| Hero treatment | **B — full-bleed bokeh photo with dark left-gradient overlay for copy legibility** |
| Pillar cards | **A — unchanged** (user specifically does not want per-pillar imagery or treatment changes) |
| Audience cards | **Hybrid v2** — 33% flush image left, thin gold hairline seam, italic sans subtitle, navy arrow that slides right + turns gold on hover |
| Image width on audience cards | **33%** (user: works better in 2- and 3-col layouts and on mobile) |
| Section order | **Pillars first, Roles second** (unchanged) with framing copy added to each |

## Out of scope

- Pillar card redesign (explicitly rejected).
- Hero pattern changes on other templates (`/library/`, resource pages, cornerstones).
- New audience taxonomy terms or filter logic (role filter on `/library/` already exists and handles that job — this page just links into it).
- Replacing placeholder Unsplash imagery with curated assets (tracked as a follow-up — see Risks).

---

## Design

### Section 1 — Hero

Full-bleed hero (~440px desktop, ~320px mobile). Replaces the current `formation-landing__header` block.

**Layout**
- Background: bokeh photograph, `background-size: cover`, centre-crop.
- Overlay: `linear-gradient(100deg, rgba(15,32,53,0.92) 0%, rgba(15,32,53,0.70) 45%, rgba(15,32,53,0.25) 100%)` — solid navy on the left fading to ~25% on the right so the image reads through.
- Copy container: `max-width: 560px`, left-aligned within `.container`, vertically centred.

**Content (proposed — copy open to revision)**
- Eyebrow: `THE WORK · FORMATION` (gold `#F5B841`, `0.14em` tracking, uppercase, `0.72rem`)
- H1: `Formation` (Playfair Display, white, `3rem` desktop / `2.2rem` mobile)
- Subtitle paragraph: the existing intro moved up verbatim: *"Formation is our word for the ongoing work of shaping and equipping the people who carry parish communications. Free essays, templates, and frameworks, organised around four pillars. Written for priests, parish secretaries, volunteers, and anyone whose job it is to help a parish speak well, on behalf of something larger than itself."* (white at 85% opacity, `1rem`, `line-height: 1.55`, max-width ~52ch).

**Image**
- Placeholder: Unsplash `photo-1519741497674-611481863552` (bokeh crowd / candle light).
- Replaced pre-launch with a curated asset — see Risks.

### Section 2 — Pillar framing + grid (unchanged grid)

**New framing paragraph** (inserted between hero and pillar grid, inside `.container`):

> The work is organised into four pillars. Each one is a practice as much as a topic — a habit of attention a parish can build over time. Start with whichever name feels closest to what you're already wondering about.

- Typography: Inter `1.05rem`, navy-700, `line-height: 1.65`, `max-width: 62ch`, `margin: 2.5rem auto 2rem`.
- Exact wording is editorial — user approves final copy in WP editor.

**Pillar grid: unchanged.** Four `acf/tld-pillar-card` blocks remain as-is.

### Section 3 — Role framing + audience grid (relaid out)

**Layout fix:** kill the current two-column layout that leaves the right half empty. Heading + intro go full-width in their own `.container`, cards sit in a 3-column grid below (desktop), 2-column (tablet), 1-column (mobile).

**Heading + new framing paragraph**

- H2: `Or start from where you stand` (replaces *"Start here by role"* — clearer intent).
- Paragraph: *"Each pathway below gathers the pieces and downloads across all four pillars most relevant to one role. The [library](/library/) has the same filter if you'd rather browse all resources and narrow by who they're for."*
- Typography matches pillar framing para.
- Exact wording editorial — user approves in WP editor.

**Audience card — hybrid v2 (new reusable component)**

Component name: `.formation-role-card`

```
┌──────────┬───────────────────────────────────────┬─────┐
│          │  I'm the priest                       │     │
│  image   │  Forming a champion, or backing one.  │  →  │
│  (33%)   │                                       │     │
└──────────┴───────────────────────────────────────┴─────┘
```

**Styling**

- Card: `background: #fff; border: 1px solid #dde1ed; border-radius: 8px; overflow: hidden; min-height: 112px; display: flex;`
- Image: `width: 33.333%; background-size: cover; background-position: center;` — flush to top, left, bottom (zero padding). Placeholder Unsplash URLs committed in the PHP template.
- Seam: `2px` solid gold `#F5B841` at 85% opacity, between image and copy.
- Copy column: `padding: 0.9rem 1.1rem; gap: 0.4rem`.
  - H5 title: Playfair, `1.02rem`, navy-900, `font-weight: 700`, `line-height: 1.2`.
  - Subtitle: Inter **italic**, `0.78rem`, `#6e7487`, `line-height: 1.4`. Em dashes dropped from current copy (e.g. `— start from zero` → `Start from zero.`).
- Chevron: `→`, navy `#0F2035` at full opacity (4.5:1+ on white), `1.25rem`. On hover: colour transitions to gold `#B68A2E` and translates `3px` right.
- Card hover: border → `#0F2035`, `transform: translateY(-1px)`, `box-shadow: 0 4px 14px rgba(15,32,53,0.08)`.
- Whole card is a single `<a>` to the audience archive URL (`/formation/for/<slug>/`) — image + text + chevron all in the link region so the click target is the full card.

**Cards (6) with placeholder images**

Mapping matches the existing list in `post_content`:

| Slug | Title | Subtitle | Placeholder image |
|------|-------|----------|-------------------|
| `new-curator` | I'm new to parish communications | Start from zero. | `photo-1434030216411-0b793f4b4173` |
| `parish-secretary` | I'm the parish secretary | Carrying this for years. | `photo-1552960562-daf630e9278b` |
| `priest` | I'm the priest | Forming a champion, or backing one. | `photo-1507679799987-c73779587ccf` |
| `ppc-chair` | I'm on the PPC or I chair it | Supporting the work structurally. | `photo-1543269664-76bc3997d9ea` |
| `diocesan-staff` | I'm at the diocese | Looking at this for a whole diocese. | `photo-1497486751825-1233686d5d80` |
| `agency` | I'm an agency or consultant | Entering parish work. | `photo-1516321318423-f06f85e504b3` |

### Section 4 — Footer / no changes

Footer already renders via `get_footer()`. Out of scope.

---

## Implementation shape

This page is Gutenberg-authored, so the design splits into:

**Template work** (`page-templates/page-formation.php`, minor)
- Replace the `formation-landing__header` inline-styled header with the new full-bleed hero markup that reads its image, eyebrow, and subtitle either from ACF fields on the page or from hardcoded defaults with override hooks. Hardcoded is fine for this one-off landing page per the user's simplicity preference.

**SCSS additions** (`_formation.scss`)
- `.formation-hero--image` — new hero variant.
- `.formation-role-card` + `.formation-role-grid` — new components.
- `.formation-section-lede` — the shared framing-paragraph style.
- Responsive rules: 1/2/3 columns on role grid; hero image height tiers.

**Gutenberg content** (WP page 253 `post_content`)
- Remove the existing hero `<p>` block (pulled into template hero instead).
- Remove the `<!-- wp:list {"className":"formation-audience-links"} -->` block entirely — role grid is rendered by the template (see below), not by Gutenberg.
- Keep the four `acf/tld-pillar-card` blocks and the `h2` "Start here by role" + following `<p>` — those get replaced by the new framing copy authored directly in Gutenberg.

**Role grid: template-rendered, not a Gutenberg block.**
The six role cards are fixed (one per audience taxonomy term), rarely change, and use a custom layout. Rendering them in `page-formation.php` via a new `template-parts/formation/role-grid.php` partial is simpler than a Gutenberg block, keeps the brainstorm's one-off scope intact, and is trivially deletable. The card data (slug, title, subtitle, image URL) lives in a hardcoded PHP array in the partial.

**Image handling**
- Phase 1 (ship): placeholder Unsplash image URLs hardcoded in the role-grid partial and in `page-formation.php` for the hero. No uploads yet.
- Phase 2 (follow-up task, not part of this spec): upload 7 curated assets to the media library, swap URLs for media library IDs. Tracked as a separate task.

## Risks

1. **Placeholder imagery stays in production.** Stock bokeh is a deliberate interim. The design doc explicitly notes the replace-with-curated-assets step as a Phase-2 follow-up — should not ship to real traffic without revisiting.
2. **Hotlinking Unsplash.** The PoC uses direct `images.unsplash.com` URLs; these are CDN-stable but external. For production we'll self-host.
3. **Accessibility on hero image.** The dark gradient overlay must hit WCAG AA for the hero text on any crop of any candidate image. To be verified against a tool in implementation.
4. **Gutenberg vs template-rendered role grid.** Choosing template-rendered keeps the design tight but means content edits require code changes. Given roles are a fixed taxonomy of 6 items that change rarely, this is acceptable.

## What "done" looks like

- `/formation/` renders hero image + copy, pillar frame + grid, role frame + 6 cards, nothing else changed.
- No visual regressions on other Formation templates.
- Role cards link to `/formation/for/<slug>/` archives that already exist.
- Placeholder imagery flagged in a follow-up task.
