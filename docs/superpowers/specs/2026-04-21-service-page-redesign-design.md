# Individual service-page redesign (page-service.php)

**Date:** 2026-04-21
**Status:** Brainstormed, awaiting user review
**Scope:** `page-templates/page-service.php` (the template behind every child `/services/<service>/` page — web-design, SEO, branding, AI, Catholic web design, business coaching, etc.). The `/services/` archive (`page-services.php`) shipped on 2026-04-21 and is **out of scope** here.

---

## Problem

User reviewed the individual service pages and said they *"feel very sassy"* (SaaS-y) and *"bland"* — the message isn't landing clearly. After pulling the live copy down from `/services/christian-web-design/`, the **copy itself is not the problem** — it's specific, substance-first, professional, and already carries the faith-context framing organically (*"We understand the difference between a church homepage and a business homepage"*). What fails is the shell: too many sections, uniform visual weight, an underweight hero, a gold accent that's everywhere and therefore nowhere.

## Goals

1. Trim the template from ~11 sections to ~6 without rewriting any existing copy.
2. Give the hero real weight by adopting the `.formation-hero--image` pattern already used on `/home/`, `/formation/`, and `/services/`.
3. Create visual rhythm across the page — one dark anchor section, typographic hierarchy, a single pull-quote breath moment, restrained gold.
4. Drop empty sections (Testimonials, Results/Impact) that currently render placeholder-ish and undermine trust.
5. Retire unused ACF fields so the editor experience in WP admin gets lighter.

## Voice

**Commercial, confident, faith-grounded — not ministry-first.** This page is meant to convert. The moral framing is already threaded through existing copy lines like *"Cultural understanding creates stronger, more authentic work"* — that stays. No new virtue-signaling prose is added.

## Decisions locked during brainstorm

| Question | Decision |
|----------|----------|
| Structure | **Hybrid (C)** — short pastoral intro + spec sheet shape, using *existing* copy blocks |
| Where does moral framing live? | **One dedicated "How we work / Why us" block** near top (1b) — repurposes the existing `.tld-whyus-*` cards |
| Pricing | **Not shown** (2b). User notes tyre-kickers are filtered quickly at call time. |
| Hero style | **`.formation-hero--image`** (3a) — bokeh, dark overlay, consistent with the rest of the site |
| Testimonials + FAQ | **Drop testimonials, keep FAQ** (4a). Testimonials return when real quotes exist. |
| Copy rewrite | **No.** Existing service-page copy is kept wholesale. |

## Sections — kept, moved, dropped

### Kept (6)
1. **Hero** — upgraded to `.formation-hero--image`
2. **Problem statement / Positioning intro** — existing `.tld-problem-*` copy unchanged
3. **Service pillars / What's included** — existing 6 `.tld-pillar-card` blocks unchanged
4. **"Why us / Faith Context"** — existing 3 `.tld-whyus-*` cards, **visually re-cast as the page's dark-navy anchor section**
5. **Process / How we work** — existing 4 `.tld-step-card` blocks unchanged
6. **FAQ accordion** — unchanged, kept

Final CTA stays (Book a Discovery Call via HubSpot modal, already live site-wide).

### Dropped
- **Audience cards** (`audience_items` ACF field + `.tld-audience-*` section). Redundant with the `/services/` archive's own pathway cards.
- **Results / Impact** (`results_items` ACF field + `.tld-results-*` section). Empty, don't ship empty.
- **Testimonials** (`get_template_part('template-parts/testimonials')` call). No quotes yet. Return when real.
- **Related Services** (the `$related_posts` block at the bottom). Footer nav covers it.
- **Inline mid-page CTA** (`get_template_part('template-parts/inline-cta', …)` between process and results). Single closing CTA lands harder.

### Moved
Nothing is structurally moved — only wrapped differently. See "Visual punch" below.

## Visual punch — where "bland" actually lives

### Hero upgrade
Replace the current flat `<section class="tld-hero-service">` with `<header class="formation-hero--image">` — the pattern we already built. Fields remain: eyebrow, title (post title), subtitle, two CTAs. No copy rewrite. Image source = page Featured Image with Unsplash fallback.

### Dark-anchor section for "Why us / Faith Context"
The `.tld-whyus-section` already has a dark-background mode (`has-bg-image` class + optional background image via `whyus_bg` ACF). Confirm this is enabled on all existing service pages (or set a default dark navy `$formation-navy-900` background when no image is provided). Section becomes the visual heart of the page.

### Typographic hierarchy
Three H2 weights introduced via CSS, not ACF:
- `.tld-problem-heading` (existing) — **largest**, e.g. `clamp(1.75rem, 3vw, 2.4rem)`. The emotional hook.
- `.tld-heading-section` inside `.tld-whyus-section` — **medium**, ~1.7rem. The anchor.
- `.tld-heading-section` on pillars / process / FAQ — **standard**, ~1.5rem. Workhorse.

Current template uses the same `.tld-heading-section` everywhere; we override within section scope.

### Pull-quote breath moment
New block inserted **between the pillars grid and the process section** (between sections 3 and 5). Pulls the strongest of the existing whyus lines and renders it centred, large Playfair italic, with a gold opening-quotation-mark glyph. No new content authored — it pulls from `whyus_items[0]['description']` by default (editor-overridable).

New component: `.tld-service-pullquote`. 1 h-level centered blockquote. Cream or off-white background.

### Gold restraint
Currently `.tld-eyebrow` (gold), `.tld-btn-gold`, the text-gold spans inside problem-heading, whyus dark-section gold eyebrow, tick icons on problem cards, and the whyus check icons all use gold. Many uses dilute impact.

**New rule of where gold appears:**
- Hero eyebrow
- Pillar card numerals (new — currently pillar cards have no numeral; adding `01 / 02 / 03…` in serif italic like the homepage formation preview cards)
- Pull-quote opening-mark glyph
- Process step numerals (already gold)
- Closing CTA button

**Removed from:** problem-statement "seconds" highlight (stays but switches to navy bold), whyus check icons (desaturate to white-50%), inline gold dots between eyebrow/heading, and any ACF-toggled gold-strip backgrounds.

### Section backgrounds — deliberate rhythm
| Section | Background |
|---|---|
| Hero | Bokeh image + navy-92% → navy-25% gradient |
| Problem statement | White |
| Pillars grid | Cream (`--tld-off-white`) — matches the library-body change we shipped earlier |
| Pull-quote | White (palate cleanser) |
| Why-us (dark anchor) | `$formation-navy-900` solid or bokeh backdrop |
| Process | White |
| FAQ | Cream |
| Closing CTA | Navy solid |

Alternation is deliberate — no two adjacent sections share a background, and the dark anchor gets real contrast.

## ACF changes

### Retired
- `audience_items` repeater + `audience_heading` + `audience_section_bg` (if present)
- `results_items` repeater + `results_heading`
- `related_services` relationship field
- `show_testimonials` toggle (if present)

### Kept unchanged
- `hero_eyebrow`, `hero_subtitle`, `hero_cta_primary_text`, `hero_cta_primary_url`, `hero_cta_secondary_text`, `hero_cta_secondary_url`
- `intro_statement`, `intro_text`
- `service_pillars` repeater + `pillars_heading`
- `problem_items` repeater + `problems_heading` + `problems_intro` **— kept for per-page "what changes" content if authors use it; drops from template default, opt-in via flag**
- `process_steps` repeater + `process_heading` + `process_bg_image`
- `whyus_items` repeater + `whyus_heading` + `whyus_bg_image`
- `faq_items` repeater

Actually — on re-read, the `problem_items` ("What Changes") section overlaps meaning-wise with the pillars ("What you get"). Dropping `problem_items` from the template is safe; nothing authored there is unrecoverable (it's still in ACF history). Retiring.

Final retired set: `audience_*`, `results_*`, `related_services`, `problem_*`.

### New
- `pullquote_text` (textarea, optional, ~20–40 words). Defaults to first whyus item's description if empty.
- `pullquote_attribution` (text, optional, e.g. *"— Sean, True Light Digital"*).

## Out of scope
- Rewriting service copy.
- Touching `page-services.php` (the archive).
- Service sub-page CPT moves or URL changes.
- Adding testimonials plumbing — that's a future task when real quotes exist.

## Implementation shape

**Files touched:**
- `page-templates/page-service.php` — rewrite to 6 sections, remove audience/results/related/inline-cta/testimonials calls
- `assets/scss/_bootscore-custom.scss` — new `.tld-service-pullquote` styles, tweaks to `.tld-whyus-section` default dark background, pillar-card numeral styling, gold-restraint overrides, H2 size scoping
- `assets/css/main.css` — mirror
- `inc/acf-fields/service-page-fields.php` — remove retired field definitions, add `pullquote_text` and `pullquote_attribution`

**Risk check:**
- Dropping `audience_items` / `results_items` orphans any per-post data. Users have set this via WP admin. Before deleting the ACF group definition, dump current values on prod to `workfolder/service-pages-acf-dump-2026-04-21.json` so authored content is recoverable. (Same pattern as the /services/ archive migration.)
- The `problem_items` field retires — dump those values too. The copy wasn't bad, so if a service page had a good "what changes" list we may want to fold phrases back into the pillar copy editorially. Flag it but don't block implementation.

## What "done" looks like
- Every `/services/<name>/` page renders: bokeh hero → problem statement → pillars → pull-quote → dark why-us anchor → process → FAQ → CTA.
- No empty testimonials / results sections rendering placeholder UI.
- Gold earns its colour — appears ≤5 times per page as a deliberate signal.
- Hero is visible (no flat white top).
- ACF admin view is shorter by ~4 field groups.
