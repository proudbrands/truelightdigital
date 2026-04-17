# True Light Digital — Brand & Identity

Canonical source of truth for who True Light Digital is. Pulled from actual site content (templates + WordPress DB), not speculation.

## The company

- **Trading name:** True Light Digital
- **Legal entity:** Proud Brands Limited (UK-registered)
- **Location:** United Kingdom
- **Market:** Churches, ministries, Christian businesses, and Catholic organisations — UK, Europe, North America, and worldwide
- **Live site:** https://truelight.digital/
- **GitHub:** https://github.com/proudbrands/truelightdigital (master branch)

## Positioning

**"Christian & Catholic Digital Agency"**

Tagline: *Digital growth built on conviction. Measured by results.*

One-liner: Strategy, websites, SEO, branding, and practical AI support for Christian businesses, churches, ministries, and Catholic organisations.

**The wedge:** Faith-aligned is not second-rate. Same technical/creative standards as any leading agency, plus the discernment that comes from shared conviction. "Sharper standards, not softer."

## Origin story

Grew from the observation that organisations doing the most meaningful work often had the weakest digital presence:
- Churches with thriving communities were invisible online
- Christian businesses with real expertise looked indistinguishable from secular competitors
- Catholic organisations with centuries of tradition were presenting themselves with clip-art and outdated sites

Founder **Sean** — 14+ years in digital strategy across enterprise tech and professional services — now focused exclusively on faith-driven organisations. Small team, senior expertise, direct access to the people doing the work.

## Audience (three pathways)

1. **Christian Businesses** — founders & leadership teams wanting growth without the usual tradeoffs. Sharpen message, improve website, turn digital marketing into a business asset.
2. **Churches & Ministries** — improve discoverability, modernize digital presence, use SEO and AI with wisdom.
3. **Catholic Organizations** — parishes, dioceses, schools, apostolates. Reverent, credible, clear. Built with real familiarity.

## Services (four pillars)

1. **Web Design & Development** — Sites that explain who you are quickly, guide the next step, mobile-performant, grow-into-able.
2. **Search Engine Optimisation** — Local visibility for churches; qualified discovery for Christian businesses.
3. **Branding & Messaging** — Voice, message, identity system. Credible and mission-aligned.
4. **AI Strategy & Implementation** — Practical workflows, guardrails, trusted guide. Human oversight preserved.

## Six core values

1. **Stewardship** — Every pound and hour should produce lasting value. Treat client resources as our own.
2. **Truth** — Clear communication. No manipulation, dark patterns, or inflated claims.
3. **Excellence** — Faith-aligned does not mean second-rate. Same standards as any leading agency.
4. **Beauty** — Design is a language, not decoration. Reflect the seriousness and beauty of the mission.
5. **Integrity** — Say what we'll do, then do it. No scope creep without conversation. No vanity metrics.
6. **Service** — Here to serve the client's mission, not showcase ours. Best digital work disappears into the background.

## The "approach" (how we work)

1. **Strategy Before Tactics** — Start with what you need to achieve and who you need to reach, not what to build.
2. **Clarity Over Cleverness** — The best church/ministry sites are the clearest, not the flashiest.
3. **Performance With Principle** — Measure results (traffic, conversions, rankings) but never at the expense of values.
4. **Long-Term Partnership** — Clean code, sustainable systems, trained teams, ongoing support. Projects aren't finished at launch — they're finished when they work.

## Hero metrics (proof points)

- **14+ years** in digital strategy
- **100%** faith-aligned work
- **3×** average organic traffic increase

## Tone of voice

Read across hero, values, pathways, and about copy:
- **Serious and direct.** No hype, no agency fluff. "Language matters. Tone matters. Discernment matters."
- **Confident but humble.** Owns expertise without swagger. "Sharp standards, not soft."
- **Faith-literate without preaching.** References stewardship, discernment, truth/beauty as craft principles — assumes the reader shares the frame.
- **Plain English, short sentences.** British spelling (optimisation, organisations, behaviour).
- **Action-forward.** Primary CTA everywhere: *Book a Discovery Call*. Secondary: *Explore Services* / *About our approach*.

## Site map (current)

**Pages (14):** Home, About, Services, Contact, Resources, Blog + 4 service pages (Christian Web Design, SEO for Churches, Christian Branding, AI for Churches) + 1 extra (Christian Business Coaching, Catholic Website Design) + 3 audience hubs (Christian Business, Churches and Ministries, Catholic Organizations).

**Blog posts (5):** A Catholic Guide to AI; Christian Business Coaching; Church SEO: The Complete Guide; How Churches Are Using AI in 2026; What Is a Faith-Driven Entrepreneur?

**Custom post type:** `tld_resource` (Resources library — downloadable guides/lead magnets)

## Visual identity (design tokens from SCSS)

- **Colour system:** Split-complementary — **Navy** (213°, trust/authority) + **Gold** (44°, warmth/quality). Muted/sophisticated — low-sat dominant with one strong warm accent. Shadows are navy-tinted, not black.
  - Primary navy: `--tld-navy-700: #1C3557`
  - Darkest navy: `--tld-navy-900: #0F2035` (hero backgrounds, footer)
  - Gold accent: featured in CTAs (`.tld-btn-gold`) and heading highlights (`.text-gold`)
- **Typography:**
  - Headings (h1–h3): **Playfair Display** (Didone serif, display weight)
  - Sub-headings (h4–h6) + body: **Inter** (variable, 600 weight for small heads)
  - Body also: **Sen** (variable)
  - Rule: Playfair switches to Inter below 24px because Didone hairlines vanish at small sizes
- **Motion:** Standardised easing — `$ease-out-expo: cubic-bezier(0.16, 1, 0.3, 1)` etc. No default `ease` or `linear`.
- **Design principles referenced in code comments:** *Ch 9 Impressionist — navy-tinted shadows*. Suggests the team follows *Design for Hackers* or similar theory-backed design literature.
