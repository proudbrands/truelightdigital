# Services page redesign + site-wide Book-a-Discovery-Call modal swap

**Date:** 2026-04-21
**Status:** Brainstormed, awaiting user review
**Scope:** `page-templates/page-services.php` (WP page at `/services/`), the global discovery modal defined in `functions.php`, and the related ACF services-landing field group.

---

## Problem

`page-services.php` is the services-landing hub, rendered at `/services/`. Current state:
- 7+ section template (Hero → Intro → Services Grid → Who We Serve → Approach → Inline CTA → Testimonials → Final CTA).
- Heavy ACF dependency (~14 fields across 5 groups).
- Stats section already removed in the homepage redesign plan (commit `8129f10`).
- Voice: centred, agency-style hero with "Book a Discovery Call" modal opening a Gravity Form.

Two things are misaligned with the publication-first / charity-voice positioning established on the homepage:
1. The page's rhythm is agency-brochure, not a curated "who we help / what we do" landing.
2. The modal lead form (Gravity Forms) is the wrong booking primitive — the user already owns a HubSpot meetings link at `https://meetings-eu1.hubspot.com/sbrannon` that takes direct bookings without the CRM-lead-routing friction.

The old homepage (pre-redesign) had cleaner DNA for a services function: 3-audience hover-cards, a 4-service vertical stack with big numerals, and a bold final CTA. That DNA was deleted in commit `3e393b4` but lives in git history. We re-use it for the services landing.

## Goals

1. Replace `page-services.php` with a lean, 4-section structure that mirrors the old homepage shape.
2. Re-use the deleted `.tld-hover-card` (pathways) and `.tld-service-stack` / `.tld-service-row` (services) components. Don't re-invent.
3. Swap the site-wide `#tld-discovery-modal` body from the Gravity Form embed to a HubSpot meetings iframe.
4. Keep the "Book a Discovery Call" CTA — services visitors are ready to book, not soft-door.
5. Shed the bespoke services-landing ACF fields (`services_hero_*`, `services_intro_*`, `services_grid_*`, `services_serve_*`, `services_approach_*`) — content is stable enough to hardcode, matching the homepage pattern.

## Decisions taken during brainstorm

| Question | Decision |
|----------|----------|
| Section count | **4 sections:** Hero → Audiences → Services → CTA. Everything else removed. |
| Hero voice | Mission-first, matching the homepage. Headline: **"When reading isn't enough."** (eyebrow: "Direct help for the Church's communications"). Copy editor-reviewable. |
| Hero imagery | Full-bleed bokeh — same pattern as homepage and `/formation/`. Uses page Featured Image, falls back to Unsplash. |
| Audience cards | 3 hardcoded cards (Christian Businesses / Churches & Ministries / Catholic Organisations). Re-use the deleted `.tld-hover-card` component. **No imagery** on cards — keeps visual weight on the hero. |
| Services list | 4 hardcoded services (Web Design, SEO, Branding, AI Strategy). Re-use the deleted `.tld-service-stack` + `.tld-service-row` components with big numerals. **No imagery** on rows. |
| Final CTA | **"Book a Discovery Call"** → opens `#tld-discovery-modal`. Same modal site-wide. No soft "write to us" alternative. |
| Modal contents | **HubSpot meetings iframe** replacing the Gravity Form embed. Embed src: `https://meetings-eu1.hubspot.com/sbrannon?embed=true` via HubSpot's official embed script. |
| ACF fields | All `services_*` ACF fields + their group registration deleted. Content is hardcoded in the template. |
| Helpers | `tld_get_service_pages()` (helper in functions.php or similar) is **unused** by the new services landing (we hardcode), but kept for other pages that may call it. |
| Testimonials | Removed from services page (none exist yet — same reason as homepage). |
| Intro statement section | Removed (absorbed into hero dek). |
| Approach section | Removed — it was agency-speak ("how we start / our process"), doesn't fit the charity voice. |
| "What Christian web design includes" 6-card grid on service sub-pages | Out of scope. This spec is the `/services/` archive only, not the child `page-service.php` template. |

## Out of scope

- Child service pages (`/services/christian-web-design/`, etc.) — those use `page-service.php`. Stats were already removed in Task 11 of the homepage plan; further redesign is a separate spec.
- HubSpot account configuration (meeting availability, pre-meeting questions) — the user owns that in HubSpot admin.
- Global header "Book a Call" button — already links to `#tld-discovery-modal`; inherits the modal body swap automatically.
- The site-wide `tld_render_cta()` (used on resource and formation pages) — ships the same modal too; benefits from the swap without changes.
- Gravity Forms cleanup — we're switching away from `TLD_DISCOVERY_FORM_ID` in the modal, but the form itself can stay in GF admin as a backup/alternative entry. Removing the form is a later tidy-up.
- Newsletter Gravity Form #10 used on the homepage subscribe section — unrelated.

---

## Design

### Section 1 — Hero

Full-bleed bokeh hero, identical pattern to `/formation/` and the `/home/` page. Reuses `.formation-hero--image` SCSS component.

**Layout**
- `<header class="formation-hero--image" style="background-image: url(...)">`
- `min-height: 440px` desktop, `320px` mobile
- Dark left-gradient overlay for copy legibility (already styled on the component)

**Content (hardcoded in template)**
- Eyebrow: `DIRECT HELP FOR THE CHURCH'S COMMUNICATIONS`
- H1: `When reading isn't enough.`
- Dek: *"Sometimes a parish, ministry, or Christian business needs someone to do the work with them — a website rebuilt, a search presence that actually finds the people looking, branding that feels like the mission, practical AI that doesn't embarrass the Church. We do that."*
- CTAs:
  - Primary: **"Book a Discovery Call"** → opens `#tld-discovery-modal` (triggers HubSpot iframe)
  - Secondary: **"See what we do"** → anchor `#what-we-do`

**Image**
- `get_the_post_thumbnail_url()` on the /services/ page if set, else placeholder Unsplash (same fallback style as the homepage hero).

### Section 2 — Audiences

Three hardcoded audience pathway cards. Re-uses the old `.tld-hover-card` component from the deleted `template-parts/home/pathways.php` (recovered from git `3e393b4`).

**Content (hardcoded, identical to pre-redesign homepage):**

| # | Title | Text | URL |
|---|-------|------|-----|
| 01 | Christian Businesses | You want growth without the usual tradeoffs. We help founders and leadership teams sharpen their message, improve their website, and turn digital marketing into a real business asset. | `/christian-business/` |
| 02 | Churches & Ministries | The people you have not met yet are searching online. We help churches improve discoverability, modernize their digital presence, and use tools like SEO and AI with wisdom. | `/churches-ministries/` |
| 03 | Catholic Organisations | Parishes, dioceses, schools, and apostolates need digital work that feels reverent, credible, and clear. We build for Catholic audiences with real familiarity. | `/catholic-organisations/` |

**Layout**
- Section heading (centred): eyebrow `WHO WE SERVE`, h2 `Choose the path that fits your mission`.
- 3-col card grid (1 col mobile, 3 col `lg`).
- `.tld-hover-card` component (already styled in `_bootscore-custom.scss` — verify still present; copy from git if the SCSS was scrubbed).

### Section 3 — Services

Four hardcoded services in a vertical stack with big serif numerals. Re-uses the old `.tld-service-stack` / `.tld-service-row` components from the deleted `template-parts/home/services.php` (recovered from git `3e393b4`).

**Content (hardcoded, identical to pre-redesign homepage):**

| # | Title | Description | URL |
|---|-------|-------------|-----|
| 01 | Web Design & Development | Sites that explain who you are quickly, guide the next step, perform well on mobile, and give your team a platform you can grow into. | `/services/christian-web-design/` |
| 02 | Search Engine Optimisation | Churches need local visibility. Christian businesses need qualified discovery. Pages that rank for what you actually do. | `/services/seo-for-churches/` |
| 03 | Branding & Messaging | Define your voice, sharpen your message, and build an identity system that looks credible and feels aligned with your mission. | `/services/christian-branding/` |
| 04 | AI Strategy & Implementation | Practical workflows, guardrails, and a trusted guide. Save time, improve communication, and keep human oversight where it belongs. | `/services/ai-for-churches/` |

**Layout**
- Anchor id: `#what-we-do` (matches the hero secondary CTA).
- Section heading: eyebrow `WHAT WE DO`, h2 `How we help, in practice.`
- `.tld-service-stack` vertical list, each row a `.tld-service-row` with numeral, title + description, right-side arrow.
- Background: `bg-off-white` as per the old pattern (reads as a palate cleanser after the darker hero and pathways section).

### Section 4 — CTA

A single dark-background CTA section, re-using the old `.tld-cta-modern` component from the deleted `template-parts/home/cta.php` (recovered from git `3e393b4`).

**Content**
- Eyebrow: `GET STARTED`
- H2: `Ready to build something stronger?` (same as the old homepage CTA — still right for services visitors)
- Dek: *"A digital partner who understands faith, takes outcomes seriously, and knows how to make strategy usable. We work best with organisations that care about clarity, move with purpose, and invest in work that lasts."*
- Button: **"Book a Discovery Call"** → opens `#tld-discovery-modal`.

### Global — Modal body swap

The `#tld-discovery-modal` is rendered once globally in `functions.php`'s render helper (around lines 215–232). Currently:

```php
<?php gravity_form(TLD_DISCOVERY_FORM_ID, false, false, false, null, true); ?>
```

Replaced with the HubSpot meetings embed:

```html
<div class="meetings-iframe-container" data-src="https://meetings-eu1.hubspot.com/sbrannon?embed=true"></div>
<script type="text/javascript" src="https://static.hsappstatic.net/MeetingsEmbed/ex/MeetingsEmbedCode.js"></script>
```

The script is loaded lazily on modal open — the embed script is small and HubSpot caches it. Acceptable to ship as inline-in-modal; defer optimisation.

The modal header text ("Book a Discovery Call" + subtitle "Tell us a little about your project…") **changes** to: "Book a Discovery Call" + "Pick a time that works. We'll send a calendar invite."

The modal becomes wider (`modal-xl` Bootstrap class) to accommodate the HubSpot scheduler UI, which needs ~900px to render comfortably.

---

## Implementation shape

**Files touched**

| File | Action |
|------|--------|
| `page-templates/page-services.php` | **Rewrite** (to ~80 lines, 4 sections, hardcoded content) |
| `functions.php` | **Edit** — swap modal body contents (Gravity Form → HubSpot iframe + script), bump to `modal-xl`, update header subtitle text |
| `assets/scss/_bootscore-custom.scss` | **Verify** `.tld-hover-card`, `.tld-service-stack`, `.tld-service-row`, `.tld-cta-modern` components still exist. If any were scrubbed, recover from git and re-add. |
| `assets/css/main.css` | **Mirror** any recovered SCSS (gitignored — ships via scp) |
| ACF field group registrations (`inc/acf-fields/services-page-fields.php` if present) | **Delete** or deactivate the `services_*` field group (field keys won't resolve in the new template; removing field defs keeps wp-admin clean) |

**Recovery from git**
If the `.tld-hover-card` / `.tld-service-stack` / `.tld-service-row` / `.tld-cta-modern` SCSS blocks were removed during an earlier cleanup, recover via:
```
git show 3e393b4:assets/scss/_bootscore-custom.scss  # or wherever they lived
```
Checked before the implementation plan writes code.

**Dependencies on earlier work**
- `.formation-hero--image` component exists (built for /formation/, reused on /home/, reused again here).
- Featured Image pattern works (`get_the_post_thumbnail_url()`) because the /services/ page is a static WP page.

---

## Risks

1. **HubSpot embed script is third-party blocking JS.** The iframe loads a script from `static.hsappstatic.net`. It's small and HubSpot caches aggressively, but it is an external dependency. If HubSpot is down, the modal is broken. Acceptable trade vs. running a lead form + manual follow-up.

2. **HubSpot meetings requires pop-up consent on some browsers** (for calendar sync). If a user's calendar-sync link is blocked, they see a fallback HubSpot-branded form. That's fine.

3. **Dropping `tld_render_cta()` from the services page** — the current services page *doesn't* use that helper (it has its own inline `tld-cta-modern` pattern we're recreating). Verify this during implementation.

4. **Removing the services-landing ACF fields might orphan data** — if the user previously set any `services_*` fields with valuable copy, that copy is lost. The user approved hardcoding, so this is intentional, but implementation should dump the current ACF values to a file for safekeeping before deleting the group.

5. **Modal swap is site-wide** — every existing "Book a Discovery Call" button now routes to HubSpot instead of Gravity Forms. Intentional, but verify no other template expects the GF on modal open (e.g., inline thank-you messages keyed to GF confirmation IDs).

## What "done" looks like

- `/services/` renders a 4-section page: bokeh hero, 3-audience pathway grid, 4-service stack, bold CTA.
- Clicking any "Book a Discovery Call" button anywhere on the site opens the discovery modal with the HubSpot scheduler embedded.
- No `services_*` ACF fields appear in wp-admin on the /services/ page.
- No stat blocks on the /services/ archive (already done in the homepage plan).
- Hero image swappable via WP → Pages → Services → Featured Image.
