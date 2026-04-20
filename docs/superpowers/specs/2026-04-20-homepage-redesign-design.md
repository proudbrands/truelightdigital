# Homepage redesign — publication-first, mission-led

**Date:** 2026-04-20
**Status:** Brainstormed, awaiting review
**Scope:** Homepage (`/`), `front-page.php`, its 8 template-parts in `template-parts/home/`, plus 3 critical bugs in the site header and blog query.

---

## Problem

The current homepage is built as a traditional digital-agency funnel: hero → pathways → services → CTA. But everything else this session has established says True Light Digital is a **publication-first practice run like a charity** — Formation is the real product, services are how some readers become clients, and the measure is ministry impact, not business metrics.

Three additional visible bugs undermine trust on every visit:
1. Site header nav overlaps the logo.
2. "Book a Call" header button wraps to 3 lines.
3. Blog teaser surfaces two test posts ("i5poke7u7dg7d5", "v84lm1niij1vjx7uui46qr5a4") because the query is `post_type=post` and test drafts are published.

## Goals

1. Reposition the homepage as publication-first — Formation leads, services are acknowledged quietly.
2. Strip all business-metric language (stats grid, "3x traffic", "What better looks like"). No numbers on the page.
3. Use imagery (bokeh hero) to match the pastoral tone already set in `/formation/`.
4. Fix the three trust-breaker bugs.

## Decisions taken during brainstorm

| Question | Decision |
|----------|----------|
| Positioning | **Hybrid, publication-first** — Formation leads, services second, no agency funnel. |
| Outcomes measurement | **Ministry only, expressed qualitatively.** No stat grids. |
| Hero voice | **Direction C** — mission as headline. Eyebrow "A digital practice run like a charity", H1 "If we look after each other, the rest follows." |
| Hero imagery | Full-bleed bokeh with dark left-gradient overlay (matches `/formation/` pattern). |
| Hero stats column | **Removed.** Hero becomes single-column. |
| Section 2 (Formation preview) | **Pillar-led (A)** — 4 compact pillar summaries + "Explore Formation →" link. |
| Section 3 (Services) | **Editorial prose block (E)** — one paragraph acknowledging direct help, soft link into `/services/`. No cards, no numerals. |
| Section 4 (proof/outcomes) | **Latest from Formation** — 3 recent `formation_piece` posts. Replaces the old "Latest insights" blog teaser; test-post bug fixed by filtering post type. |
| Section 5 (closing CTA) | **Subscribe invitation** — email capture with "we'll keep doing this work" voice. Replaces "Ready to build something stronger? Book a Discovery Call." |
| Existing "Proof strip" ("What better looks like") | **Removed.** Ministry outcomes in business language. |
| Existing 3-audience pathways cards | **Removed.** Folded into the services prose block copy. |
| Existing testimonials section | **Removed from homepage.** There are no testimonials yet (no priests on record). Can return when real quotes exist. |

## Out of scope

- `/services/` page changes.
- `/formation/` page changes (already redesigned this session).
- Other templates and archives.
- Mobile-specific redesign beyond the responsive rules inherited from the new hero/section styles (the existing breakpoints are preserved).
- Email list integration plumbing (Mailchimp / ConvertKit / custom). The form markup is specified; the handler is an implementation-plan question.

---

## Design

### Section 1 — Hero (new)

Full-bleed bokeh hero, ~440px desktop / ~320px mobile. Reuses the `.formation-hero--image` component built for `/formation/`. Mission-as-headline voice.

**Content (hardcoded in `template-parts/home/hero.php`)**
- Eyebrow: `A DIGITAL PRACTICE RUN LIKE A CHARITY`
- H1: `If we look after each other, the rest follows.`
- Dek: *"We equip the people who tell the Church's story — priests, parish secretaries, ministry leaders, Christian founders — with writing, templates, and tools. All of it free. Direct help if you want it. No pitch."*
- CTAs: two buttons. Primary `Start in Formation →` → `/formation/`. Secondary `Write to us` → `/contact/` (or whatever the contact page slug is).

**Image source**
- Prefer page's Featured Image on the front page (WordPress "Static front page" setting may target a specific page; if so, featured image applies).
- If no front page is set (blog index), or if featured image is empty, fall back to the Unsplash placeholder `photo-1519741497674-611481863552`.
- Falls into the same Phase-2 follow-up bucket as `/formation/` imagery.

**Removed from old hero:** the right-column `.tld-hero-metrics` 3-stat block (14+ years, 100%, 3x). Gone.

### Section 2 — Formation preview (new)

Cream-backgrounded block (`background: #F8F9FB`) inside `.container`, with:

- Eyebrow: `FORMATION`
- H2: `The free library`
- Dek: *"Four pillars, twenty-plus pieces, all free. The ongoing work of shaping and equipping the people who carry parish and ministry communications."*
- 4 compact pillar cards in a 2×2 grid (md: 2 cols, sm: 1 col). Each card: numeral "01–04", pillar title (Playfair serif), one-line tagline (Inter).
- Footer link: `Explore Formation →` → `/formation/`.

**Component:** new partial `template-parts/home/formation-preview.php`.

**Data source:** pulls from the `pillar` taxonomy terms, ordered by `pillar_sort_order` ACF field (same as `/formation/` template). Renders a **new** compact `.tld-pillar-preview-card` component (not a reuse of the existing full `.tld-pillar-card` ACF block — the full card is too heavy for a 2×2 compact grid). Pillar names and taglines read from term meta (`pillar_tagline` on each term), so no copy is hardcoded in this template.

### Section 3 — Services prose block (new)

Single editorial paragraph, centred max-width ~62ch, inside `.container`. Quiet, no cards.

**Copy**
> **If you need direct help.**
> Sometimes reading isn't enough and a parish, ministry, or Christian business needs someone to do the work with them — a website rebuilt, a search presence that actually finds the people looking, branding that feels like the mission, practical AI that doesn't embarrass the Church. [See how we help →](/services/)

One link, not two. The services page explains engagement. The contact page (reached via the hero's secondary CTA and the subscribe form) handles direct outreach.

Styling: Inter sans-serif, `font-size: 1.05rem`, `line-height: 1.65`, navy body copy with gold accent on the header noun ("If you need direct help"). Matches `.formation-section-lede` pattern.

**Component:** new partial `template-parts/home/direct-help.php`.

### Section 4 — Latest from Formation (replacement)

Reuse the existing blog-teaser layout and card component, but change the query to pull 3 most recent `formation_piece` posts (not `post`). Fixes the test-post bug and aligns the section with the publication-first positioning.

- Eyebrow: `LATEST`
- H2: `From the desk`
- 3 cards in a 3-col grid (md: 2, sm: 1). Each card: featured image, published date, title, short excerpt, "Read →" link.
- "View all" button → `/formation/`.

**Component:** modify `template-parts/home/blog-teaser.php` — change the query's `post_type` and update heading copy.

### Section 5 — Subscribe invitation (new)

Navy-background section (`background: $formation-navy-900`), centered content.

**Copy**
- H2 (white, Playfair): `We'll keep doing this work.`
- Dek (Inter, 85% opacity white): *"If you'd like a short note when something new goes up in Formation, we'll send one. Free, no marketing, unsubscribe any time."*
- Email input + gold submit button (`Subscribe →`).
- Fine-print line below: `You can unsubscribe from any email we send.`

**Component:** new partial `template-parts/home/subscribe.php`.

**Form handler:** open question for the implementation plan. Options: a Gravity Form configured to push to a mailing-list service, a Mailchimp embed, or a simple POST to an `admin-ajax.php` endpoint. The design spec commits to the markup and voice; the plan picks the handler. **Default assumption:** use Gravity Forms (already installed per Formation sidebar pattern), with a form ID read from a theme option so it's configurable without code.

### Bug fixes (inline with the redesign)

**B1 — Nav overlap.** The primary nav is landing on top of the logo at 992–1200px viewports (and probably wider — needs verification). Fix sequence, in order:
1. Inspect `.tld-header` / `.navbar-brand` / `.navbar-nav` computed widths at 1080, 1280, 1440px.
2. First try: widen the logo's flex basis (`flex: 0 0 auto; min-width: 280px`) OR add `margin-right: 1.5rem` on `.navbar-brand`.
3. If that doesn't resolve, reduce nav item horizontal padding OR move overflow items into a "More" dropdown at `< 1280px`.
The spec commits to fix #2 first; the plan escalates to #3 only if #2 doesn't hold.

**B2 — "Book a Call" header button wraps.** The navbar CTA wraps to "Boo / k a / Call" because its container has a constrained width and the button defaults to `white-space: normal`. Fix: `.tld-btn-gold` (header instance) gets `white-space: nowrap`. If the button then overflows horizontally at smaller widths, shorten the label to `Book Call` in the header only (keep `Book a Discovery Call` in the body CTAs).

**B3 — Test posts on homepage.** Two specific posts — `i5poke7u7dg7d5` and `v84lm1niij1vjx7uui46qr5a4` — are `publish`ed on prod. Fix: set both to `draft` (reversible, doesn't delete data). Section 4's query change to `post_type=formation_piece` is the durable defence; the draft flip is the immediate clean-up.

---

## Implementation shape

**Template work**
- `front-page.php` — remove `get_template_part` calls for `pathways`, `proof-strip`, `services`, `values`, `testimonials`, `cta`. Add calls for `formation-preview`, `direct-help`, `subscribe`. Keep `hero` (rewritten), keep `blog-teaser` (renamed or modified).
- `template-parts/home/hero.php` — replace existing markup with `.formation-hero--image` pattern. Drop metrics column. New copy.
- `template-parts/home/formation-preview.php` — NEW.
- `template-parts/home/direct-help.php` — NEW.
- `template-parts/home/blog-teaser.php` — change `post_type` argument to `formation_piece`, update heading.
- `template-parts/home/subscribe.php` — NEW. Markup only; plan picks the form handler.

**SCSS additions**
- `.tld-pillar-preview-card` if a compact pillar variant is needed (or reuse existing with a `--compact` modifier).
- `.tld-home-direct-help` block styles (serif noun prefix, prose body, link).
- `.tld-home-subscribe` section styles (navy background, centred content, inline form).
- Small additions to `.tld-header` for bug B1.

**Bug fixes** live in a separate commit from the redesign so they can be reverted independently.

**Image handling**
- Hero: use WP Featured Image on the front page (if the front page is a WP page in `Settings → Reading`). If the front page is the blog, no featured-image slot exists, so either (a) add an ACF image field on a global Options page, or (b) point Settings → Reading to a `/home/` page that has a Featured Image. **Decision:** use option (b). If no `/home/` page exists, create one, assign the `front-page.php` template, and set Featured Image there.

**Data decisions**
- Latest-from-Formation query: `post_type=formation_piece`, `post_status=publish`, `posts_per_page=3`, `orderby=date`, `order=DESC`.
- Pillar order on the Formation preview: same as `/formation/` — `meta_key=pillar_sort_order`, `orderby=meta_value_num`, `order=ASC`.

## Risks

1. **No numbers at all** is distinctive — some readers will want a quantitative proof point. Mitigation: the writing in Formation IS the proof. If the market rejects this, a lightweight ministry-metrics block can be added later in Section 4 without restructuring.
2. **Subscribe form handler is deferred.** Plan must pick one; not picking one blocks launch. Recommended: Gravity Forms.
3. **Front page featured-image pattern** is brittle if Settings → Reading changes. Alternative: hardcoded image URL in `hero.php` with eventual ACF theme-option. Same trade we made on `/formation/`.
4. **Deleting test posts** is a direct prod DB action. The plan must include a step to confirm the two specific post IDs before deletion — so we don't remove something the user is about to publish.
5. **Copy may not be final.** The hero, services-block, and subscribe copy is drafted in the spec — the user approves or rewrites at implementation time. The plan has explicit "copy review" as an early task.

## What "done" looks like

- `/` renders hero image + mission headline, 4-pillar Formation preview, services prose block, 3 latest Formation pieces, subscribe form.
- The three critical bugs are fixed.
- No stat grids anywhere on the homepage.
- No agency-style CTA. The bottom section is a subscribe form.
- Homepage structure reflects the publication-first positioning.
