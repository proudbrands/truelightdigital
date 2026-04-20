# Homepage Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rebuild the homepage as publication-first, mission-led (see [spec](../specs/2026-04-20-homepage-redesign-design.md)). Remove all numeric "stats" from the homepage and from service sub-pages. Fix three trust-breaker bugs (header nav overlap, Book-a-Call wrap, test posts). Diagnose and fix markdown rendering on `/library/communications-champion-role-description/`.

**Architecture:** Tear down 5 homepage template-parts, add 4 new ones (hero rewrite, Formation preview, direct-help prose, subscribe). Latest-insights query changes from `post` to `formation_piece`. Hero reuses `.formation-hero--image` from `/formation/`. SCSS additions in `_formation.scss` (or split `_home.scss`); `main.css` hand-maintained. Service-page stats removal is a search-and-remove sweep. Markdown bug diagnosed before fix.

**Tech Stack:** PHP 7.4 (WP), Sass (hand-compiled), Gutenberg, ACF, Bootstrap 5, Gravity Forms.

---

## File Structure

| File | Action | Purpose |
|------|--------|---------|
| `front-page.php` | Modify | Trim to 5 sections: hero, formation-preview, direct-help, blog-teaser (renamed), subscribe |
| `template-parts/home/hero.php` | Rewrite | Full-bleed bokeh + mission headline, no metrics column |
| `template-parts/home/formation-preview.php` | Create | 4 pillar preview cards |
| `template-parts/home/direct-help.php` | Create | Editorial prose block for services |
| `template-parts/home/blog-teaser.php` | Modify | Query `formation_piece`, update heading |
| `template-parts/home/subscribe.php` | Create | Email capture section |
| `template-parts/home/pathways.php` | Delete | Superseded by Formation preview |
| `template-parts/home/proof-strip.php` | Delete | Killed (no numbers) |
| `template-parts/home/services.php` | Delete | Superseded by direct-help block |
| `template-parts/home/values.php` | Delete | Folded into hero/mission voice |
| `template-parts/home/cta.php` | Delete | Superseded by subscribe |
| `template-parts/testimonials.php` | Leave on disk, remove from front-page.php | No testimonials yet |
| `assets/scss/_formation.scss` | Modify | `.tld-pillar-preview-card`, `.tld-home-direct-help`, `.tld-home-subscribe` |
| `assets/scss/_bootscore-custom.scss` | Modify | `.tld-header` bug fixes (B1 nav overlap, B2 button wrap) |
| `assets/css/main.css` | Modify (gitignored) | Mirror SCSS changes |
| `page-templates/page-service.php` | Modify | Remove stats section (B-stats, see Task 13) |
| `template-parts/service-stats*.php` (if present) | Delete | If standalone component |
| WP DB: posts `i5poke7u7dg7d5`, `v84lm1niij1vjx7uui46qr5a4` | Update | Set `post_status='draft'` |

Deploy pattern (established): commit theme → push → ssh → `git pull` → `scp main.css` → WP Rocket purge.

---

## Task 1: Delete superseded template-parts

**Files:**
- Delete: `template-parts/home/pathways.php`, `proof-strip.php`, `services.php`, `values.php`, `cta.php`

- [ ] **Step 1: Verify nothing else references these parts**

Run Grep for `get_template_part.*home/(pathways|proof-strip|services|values|cta)` across the theme. Only `front-page.php` should reference them.

- [ ] **Step 2: Delete the 5 files**

```bash
cd "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child"
rm template-parts/home/pathways.php
rm template-parts/home/proof-strip.php
rm template-parts/home/services.php
rm template-parts/home/values.php
rm template-parts/home/cta.php
```

- [ ] **Step 3: Commit**

```bash
git add -u template-parts/home/
git commit -m "Remove superseded homepage template-parts

Pathways, proof-strip, services, values, and cta are all being
replaced in the publication-first redesign. front-page.php no longer
loads them after Task 4."
```

---

## Task 2: Rewrite the hero template-part

**Files:**
- Modify: `template-parts/home/hero.php` (full rewrite, small file)

- [ ] **Step 1: Replace the file contents**

```php
<?php
/**
 * Homepage: Hero — full-bleed bokeh + mission headline.
 *
 * Reuses the .formation-hero--image pattern from /formation/.
 * Image source: front page's Featured Image; falls back to Unsplash.
 * Copy is hardcoded — this is a mission statement, not editable content.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full')
  ?: 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=2000&q=75';
?>
<header class="formation-hero--image" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
  <div class="container">
    <span class="formation-hero--image__eyebrow">A digital practice run like a charity</span>
    <h1 class="formation-hero--image__title">If we look after each other, the rest follows.</h1>
    <p class="formation-hero--image__intro">We equip the people who tell the Church's story &mdash; priests, parish secretaries, ministry leaders, Christian founders &mdash; with writing, templates, and tools. All of it free. Direct help if you want it. No pitch.</p>
    <div class="formation-hero--image__ctas">
      <a class="btn tld-btn-gold btn-lg tld-btn-arrow" href="<?= esc_url(home_url('/formation/')); ?>">Start in Formation &rarr;</a>
      <a class="btn btn-outline-light btn-lg" href="<?= esc_url(home_url('/contact/')); ?>">Write to us</a>
    </div>
  </div>
</header>
```

- [ ] **Step 2: Add CTA wrapper styles to `_formation.scss`**

Insert after the existing `.formation-hero--image__intro { ... }` block:

```scss
  &__ctas {
    margin-top: 1.3rem;
    display: flex;
    gap: 0.6rem;
    flex-wrap: wrap;
  }
```

Mirror into `main.css`:

```css
.formation-hero--image__ctas {
  margin-top: 1.3rem;
  display: flex;
  gap: 0.6rem;
  flex-wrap: wrap;
}
```

- [ ] **Step 3: Syntax check**

```bash
scp -i ~/.ssh/dontleak "wp-content/themes/bootscore-child/template-parts/home/hero.php" ny5agbo@65.181.116.183:/tmp/h.php
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "php -l /tmp/h.php && rm /tmp/h.php" 2>&1 | grep -v post-quantum
```
Expected: `No syntax errors detected`

- [ ] **Step 4: Commit**

```bash
git add template-parts/home/hero.php assets/scss/_formation.scss
git commit -m "Rewrite homepage hero: mission headline + bokeh full-bleed"
```

---

## Task 3: Create formation-preview partial + SCSS

**Files:**
- Create: `template-parts/home/formation-preview.php`
- Modify: `assets/scss/_formation.scss`
- Modify: `assets/css/main.css`

- [ ] **Step 1: Write the partial**

```php
<?php
/**
 * Homepage: Formation preview — 4 compact pillar cards.
 * Pulls from the `pillar` taxonomy, ordered by pillar_sort_order meta.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$pillars = get_terms([
  'taxonomy'   => 'pillar',
  'hide_empty' => false,
  'meta_key'   => 'pillar_sort_order',
  'orderby'    => 'meta_value_num',
  'order'      => 'ASC',
  'number'     => 4,
]);
if (is_wp_error($pillars) || empty($pillars)) return;
?>
<section class="tld-home-formation-preview">
  <div class="container">
    <div class="tld-home-formation-preview__header">
      <span class="tld-home-formation-preview__eyebrow">Formation</span>
      <h2 class="tld-home-formation-preview__title">The free library</h2>
      <p class="tld-home-formation-preview__lede">Four pillars, twenty-plus pieces, all free. The ongoing work of shaping and equipping the people who carry parish and ministry communications.</p>
    </div>
    <div class="tld-home-formation-preview__grid">
      <?php foreach ($pillars as $i => $term):
        $tagline = function_exists('get_field') ? get_field('pillar_tagline', $term) : '';
        $num = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
      ?>
        <a class="tld-pillar-preview-card" href="<?= esc_url(get_term_link($term)); ?>">
          <span class="tld-pillar-preview-card__num"><?= esc_html($num); ?></span>
          <span class="tld-pillar-preview-card__body">
            <span class="tld-pillar-preview-card__title"><?= esc_html($term->name); ?></span>
            <?php if ($tagline): ?>
              <span class="tld-pillar-preview-card__tagline"><?= esc_html($tagline); ?></span>
            <?php endif; ?>
          </span>
        </a>
      <?php endforeach; ?>
    </div>
    <p class="tld-home-formation-preview__link">
      <a href="<?= esc_url(home_url('/formation/')); ?>">Explore Formation &rarr;</a>
    </p>
  </div>
</section>
```

- [ ] **Step 2: Append SCSS** to `_formation.scss`:

```scss
// --- Homepage: Formation preview ---
.tld-home-formation-preview {
  background: #F8F9FB;
  padding: 4rem 0;

  &__header { text-align: left; max-width: 680px; margin-bottom: 2rem; }
  &__eyebrow {
    font-size: 0.72rem; letter-spacing: 0.14em; text-transform: uppercase;
    color: #B68A2E; font-weight: 700; display: block; margin-bottom: 0.6rem;
  }
  &__title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; color: $formation-navy-900;
    margin: 0 0 0.7rem; font-weight: 700; line-height: 1.15;
  }
  &__lede {
    font-family: 'Inter', sans-serif;
    color: #4a5168; font-size: 1rem; line-height: 1.6; margin: 0;
    max-width: 58ch;
  }
  &__grid {
    display: grid; grid-template-columns: 1fr; gap: 0.9rem; margin: 2rem 0;
    @media (min-width: 768px) { grid-template-columns: 1fr 1fr; }
  }
  &__link a {
    color: $formation-navy-900; font-weight: 600;
    border-bottom: 2px solid $formation-gold; padding-bottom: 2px;
    text-decoration: none;
  }
}

.tld-pillar-preview-card {
  display: flex; gap: 1rem; align-items: flex-start;
  background: #fff; border: 1px solid #e4e7ef; border-radius: 8px;
  padding: 1.1rem 1.3rem;
  text-decoration: none; color: inherit;
  transition: border-color 0.18s ease, transform 0.18s ease;

  &__num {
    font-family: 'Playfair Display', serif; font-style: italic;
    color: $formation-gold; font-size: 1.4rem; font-weight: 700;
    flex-shrink: 0; min-width: 2.5rem;
  }
  &__body { display: flex; flex-direction: column; gap: 0.3rem; }
  &__title {
    font-family: 'Playfair Display', serif; color: $formation-navy-900;
    font-size: 1.05rem; font-weight: 700; line-height: 1.2;
  }
  &__tagline {
    font-family: 'Inter', sans-serif; color: #6e7487;
    font-size: 0.82rem; line-height: 1.45;
  }

  &:hover {
    border-color: $formation-navy-900;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(15,32,53,0.06);
  }
}
```

- [ ] **Step 3: Mirror into `main.css`** — append at end-of-file (before the `sourceMappingURL` comment), resolving `$formation-*` to hex: `$formation-gold` → `#E6B849`, `$formation-navy-900` → `#0F2035`. Copy the structure from Step 2 but as plain CSS (no nesting, each media query as its own block).

- [ ] **Step 4: Syntax check**

```bash
scp -i ~/.ssh/dontleak "wp-content/themes/bootscore-child/template-parts/home/formation-preview.php" ny5agbo@65.181.116.183:/tmp/fp.php
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "php -l /tmp/fp.php && rm /tmp/fp.php" 2>&1 | grep -v post-quantum
```

- [ ] **Step 5: Commit**

```bash
git add template-parts/home/formation-preview.php assets/scss/_formation.scss
git commit -m "Add homepage Formation preview (4 compact pillar cards)"
```

---

## Task 4: Update front-page.php to the new section order

**Files:**
- Modify: `front-page.php`

- [ ] **Step 1: Replace file contents**

```php
<?php
/**
 * Homepage Template (publication-first redesign).
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main">

  <?php if (have_posts()): while (have_posts()): the_post(); ?>

    <?php get_template_part('template-parts/home/hero'); ?>
    <?php get_template_part('template-parts/home/formation-preview'); ?>
    <?php get_template_part('template-parts/home/direct-help'); ?>
    <?php get_template_part('template-parts/home/blog-teaser'); ?>
    <?php get_template_part('template-parts/home/subscribe'); ?>

  <?php endwhile; endif; ?>

</main>

<?php get_footer(); ?>
```

- [ ] **Step 2: Commit**

```bash
git add front-page.php
git commit -m "Trim front-page to 5 sections in new order"
```

---

## Task 5: Create direct-help prose partial + SCSS

**Files:**
- Create: `template-parts/home/direct-help.php`
- Modify: `assets/scss/_formation.scss`
- Modify: `assets/css/main.css`

- [ ] **Step 1: Write the partial**

```php
<?php
/**
 * Homepage: "If you need direct help" — quiet editorial prose
 * acknowledging the services offering without pitching.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;
?>
<section class="tld-home-direct-help">
  <div class="container">
    <p class="tld-home-direct-help__body">
      <strong class="tld-home-direct-help__lead">If you need direct help.</strong>
      Sometimes reading isn't enough and a parish, ministry, or Christian business needs someone to do the work with them &mdash; a website rebuilt, a search presence that actually finds the people looking, branding that feels like the mission, practical AI that doesn't embarrass the Church.
      <a class="tld-home-direct-help__link" href="<?= esc_url(home_url('/services/')); ?>">See how we help &rarr;</a>
    </p>
  </div>
</section>
```

- [ ] **Step 2: Append SCSS**

```scss
// --- Homepage: direct-help prose block ---
.tld-home-direct-help {
  padding: 3.5rem 0;
  background: #fff;

  &__body {
    font-family: 'Inter', sans-serif;
    font-size: 1.08rem;
    line-height: 1.7;
    color: #2c3240;
    max-width: 62ch;
    margin: 0 auto;
    text-align: left;
  }
  &__lead {
    font-family: 'Playfair Display', serif;
    color: $formation-navy-900;
    font-size: 1.15rem;
    font-weight: 700;
    display: block;
    margin-bottom: 0.5rem;
  }
  &__link {
    color: $formation-navy-900;
    font-weight: 600;
    text-decoration: none;
    border-bottom: 2px solid $formation-gold;
    padding-bottom: 2px;
    white-space: nowrap;
  }
}
```

- [ ] **Step 3: Mirror into `main.css`** (same hex-resolution pattern).

- [ ] **Step 4: Syntax + commit**

```bash
scp + php -l (same pattern as Task 2)
git add template-parts/home/direct-help.php assets/scss/_formation.scss
git commit -m "Add homepage direct-help prose block (no cards, quiet link to services)"
```

---

## Task 6: Convert blog-teaser → latest-from-Formation

**Files:**
- Modify: `template-parts/home/blog-teaser.php`

- [ ] **Step 1: Change the query and heading**

Find:
```php
$recent_posts = get_posts([
  'posts_per_page' => 3,
  'post_status'    => 'publish',
]);
```

Replace with:
```php
$recent_posts = get_posts([
  'post_type'      => 'formation_piece',
  'posts_per_page' => 3,
  'post_status'    => 'publish',
  'orderby'        => 'date',
  'order'          => 'DESC',
]);
```

Find the heading `<h2 class="tld-heading-section mb-0 tld-reveal tld-reveal-d1">Latest insights</h2>` and replace with `Latest from Formation`.

Find the eyebrow `<p class="tld-eyebrow tld-reveal">Insights</p>` and replace with `Latest`.

Find the "View all" button `href="<?= esc_url(home_url('/blog/')); ?>"` and replace with `href="<?= esc_url(home_url('/formation/')); ?>"`.

- [ ] **Step 2: Commit**

```bash
git add template-parts/home/blog-teaser.php
git commit -m "Blog teaser now pulls formation_piece, not post; fixes junk-title bug"
```

---

## Task 7: Create subscribe partial + SCSS

**Files:**
- Create: `template-parts/home/subscribe.php`
- Modify: `assets/scss/_formation.scss`
- Modify: `assets/css/main.css`

- [ ] **Step 1: Identify the Gravity Form ID**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "wp --path=/home/ny5agbo/public_html eval 'if(class_exists(\"GFAPI\")){\$forms=GFAPI::get_forms();foreach(\$forms as \$f){echo \$f[\"id\"].\" - \".\$f[\"title\"].\"\n\";}}'" 2>&1 | grep -v post-quantum
```

Look for an existing newsletter/subscribe form. If one exists, note its ID for Step 2. If none exists, **STOP** — create one via WP Admin → Forms → New Form with a single Email field, then come back.

- [ ] **Step 2: Write the partial**

```php
<?php
/**
 * Homepage: Subscribe invitation — email capture, no hard sell.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$form_id = 0; // REPLACE with the ID identified in plan Task 7 Step 1
?>
<section class="tld-home-subscribe">
  <div class="container">
    <h2 class="tld-home-subscribe__title">We&rsquo;ll keep doing this work.</h2>
    <p class="tld-home-subscribe__dek">If you&rsquo;d like a short note when something new goes up in Formation, we&rsquo;ll send one. Free, no marketing, unsubscribe any time.</p>
    <?php if ($form_id && function_exists('gravity_form')): ?>
      <div class="tld-home-subscribe__form">
        <?php gravity_form($form_id, false, false, false, null, true); ?>
      </div>
    <?php else: ?>
      <p class="tld-home-subscribe__fallback"><a href="<?= esc_url(home_url('/contact/')); ?>">Write to us</a> to be added to the list.</p>
    <?php endif; ?>
  </div>
</section>
```

- [ ] **Step 3: Append SCSS**

```scss
// --- Homepage: subscribe section ---
.tld-home-subscribe {
  background: $formation-navy-900;
  color: #fff;
  padding: 4rem 0;
  text-align: center;

  .container { max-width: 680px; }

  &__title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; line-height: 1.15;
    color: #fff; margin: 0 0 0.8rem; font-weight: 700;
  }
  &__dek {
    font-family: 'Inter', sans-serif;
    font-size: 1rem; line-height: 1.6;
    color: rgba(255,255,255,0.85);
    max-width: 52ch; margin: 0 auto 1.8rem;
  }
  &__form { max-width: 460px; margin: 0 auto; }
  &__fallback a {
    color: $formation-gold; font-weight: 600;
    border-bottom: 2px solid $formation-gold; padding-bottom: 2px;
    text-decoration: none;
  }
}
```

- [ ] **Step 4: Mirror into `main.css`** + syntax-check + commit.

```bash
git add template-parts/home/subscribe.php assets/scss/_formation.scss
git commit -m "Add homepage subscribe section (Gravity Forms-backed)"
```

---

## Task 8: Fix B1 — header nav overlapping logo

**Files:**
- Modify: `assets/scss/_bootscore-custom.scss`
- Modify: `assets/css/main.css`

- [ ] **Step 1: Diagnose**

Open the live site at 1080, 1280, 1440, 1920 viewports. Use devtools to confirm whether `.navbar-brand` and `.navbar-nav` overlap at specific widths. Note at which breakpoint the overlap appears/disappears.

- [ ] **Step 2: Patch**

In `_bootscore-custom.scss`, find `.tld-header` / `.navbar-brand` rules. Add (or update if present):

```scss
.tld-header {
  .navbar-brand {
    margin-right: 1.5rem;
    min-width: 260px;   // reserves space for the wordmark + dot
    flex-shrink: 0;
  }
  @media (max-width: 1199.98px) {
    .navbar-brand { min-width: 220px; }
  }
}
```

Mirror into `main.css`.

- [ ] **Step 3: Verify on local, then deploy** (see Task 14).

- [ ] **Step 4: Commit**

```bash
git add assets/scss/_bootscore-custom.scss
git commit -m "Header B1: reserve min-width on navbar-brand so nav stops overlapping logo"
```

---

## Task 9: Fix B2 — "Book a Call" button wrapping

**Files:**
- Modify: `assets/scss/_bootscore-custom.scss`
- Modify: `assets/css/main.css`
- Possibly: `header.php` (label change if nowrap still overflows)

- [ ] **Step 1: Patch**

In `_bootscore-custom.scss`, find `.tld-btn-gold` or the header CTA rule. Add:

```scss
.tld-header .tld-btn-gold,
.tld-header .btn {
  white-space: nowrap;
}
```

Mirror into `main.css`.

- [ ] **Step 2: Verify**

Reload homepage at 1080–1920px viewports. Button should no longer wrap. If `nowrap` causes horizontal overflow at narrow widths (< 1200), shorten the label:

In `header.php`, find the header CTA button and change label from "Book a Call" to "Book Call" (header only; don't touch body CTAs).

- [ ] **Step 3: Commit**

```bash
git add assets/scss/_bootscore-custom.scss  # and header.php if changed
git commit -m "Header B2: nowrap on header CTA button so Book a Call stops breaking"
```

---

## Task 10: Fix B3 — junk blog posts

**Files:** WP DB only (prod).

- [ ] **Step 1: Confirm the two post IDs**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "wp --path=/home/ny5agbo/public_html post list --s='i5poke7u7dg7d5' --format=csv --fields=ID,post_title,post_status" 2>&1 | grep -v post-quantum
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "wp --path=/home/ny5agbo/public_html post list --s='6tomxswyb25f9vgi' --format=csv --fields=ID,post_title,post_status" 2>&1 | grep -v post-quantum
```

Save the two IDs for Step 2.

- [ ] **Step 2: Set both to draft**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "wp --path=/home/ny5agbo/public_html post update <ID1> <ID2> --post_status=draft" 2>&1 | grep -v post-quantum
```

Expected: `Success: Updated post <ID1>.` twice.

- [ ] **Step 3: Verify**

```bash
curl -s "https://truelight.digital/?nocache=$(date +%s)" | grep -c "i5poke7u7dg7d5\|6tomxswyb25f9vgi"
```
Expected: 0. (After Task 6 also landed, the homepage won't query the blog at all, but belt-and-braces.)

---

## Task 11: Service-pages stat sweep

**Files:**
- Scan: `page-templates/page-service.php`, `page-templates/page-services.php`, any child partials that include `.tld-metric*` or big-number patterns.
- Potentially: `template-parts/service-stats*.php` or similar.

- [ ] **Step 1: Locate all stat blocks**

```bash
Grep tool: pattern `tld-metric|stat-number|94%|53%|88%|THE REALITY` in theme files.
Grep tool: pattern `data-count=` in theme files (the animated stat counter).
```

List every file touched. Expected hits: the hero metrics in `hero.php` (already killed in Task 2), plus at least one stat block on `page-service.php` visible in the user's screenshot ("94% / 53% / 88% / 5s").

- [ ] **Step 2: Remove the sections**

For each file identified:
- If it's a whole `<section>` wrapping the stats, delete the section.
- If stats are inline in a larger layout, delete just the stat block + its heading.
- Preserve any non-stat content above/below.

Eyeball the result — the "THE REALITY / Why great web design delivers results" section in the user's screenshot is the one to kill. Copy around it ("What Christian web design includes" card grid) stays.

- [ ] **Step 3: Verify nothing broke**

Check `/services/christian-web-design/` (and any sibling service pages) renders without the stats row.

- [ ] **Step 4: Commit**

```bash
git add page-templates/page-service.php  # plus any other files touched
git commit -m "Remove stat blocks from service pages (no numbers policy)"
```

---

## Task 12: Diagnose + fix markdown rendering on library page

**Files:** investigation-first; fix location depends on diagnosis.

- [ ] **Step 1: Capture the broken render**

```bash
curl -s "https://truelight.digital/library/communications-champion-role-description/?nocache=$(date +%s)" > /tmp/cc.html
# Grep for visible markdown syntax leaking into HTML output:
grep -nE '(^|>)#{1,6} |\*\*[^<]*\*\*|```|\[.*\]\(.*\)' /tmp/cc.html | head -20
```

Expected: either raw markdown characters (`**`, `##`, backticks) visible in rendered output (means MD → HTML converter didn't run), or broken HTML structure.

- [ ] **Step 2: Identify the converter**

```bash
Grep tool: pattern `parsedown|league/commonmark|markdown_to_html|jetpack_markdown` across the theme + mu-plugins + wp-content/plugins (only theme and mu-plugins — don't scan node_modules).
```

The commit history shows "Markdown converter: skip --- separators" — find that converter.

- [ ] **Step 3: Reproduce locally**

On local WP, visit the same URL. Check if the bug reproduces. If yes, debugger or `error_log()` inside the converter to see what's failing.

- [ ] **Step 4: Fix**

The fix depends on diagnosis. Two most likely root causes:
- (a) The converter isn't being called on `single-tld_resource.php` output.
- (b) The converter is called but has a regression from a recent change.

Patch the single location that fails. Do NOT refactor — targeted fix only.

- [ ] **Step 5: Verify + commit**

```bash
curl -s "https://truelight.digital/library/communications-champion-role-description/?nocache=$(date +%s)" > /tmp/cc2.html
# No raw markdown characters in rendered output:
grep -cE '(^|>)#{1,6} |\*\*[^<]*\*\*' /tmp/cc2.html
```
Expected: 0.

```bash
git add <file(s) touched>
git commit -m "Fix markdown rendering on library detail pages"
```

---

## Task 13: Hook up /home/ page for Featured Image

**Files:** none (WP admin / DB only).

Context: `front-page.php` uses `get_the_post_thumbnail_url(get_the_ID(), 'full')` for the hero image. For that to resolve, the front page must be a static Page with a Featured Image assigned.

- [ ] **Step 1: Check WP Reading settings**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "wp --path=/home/ny5agbo/public_html option get show_on_front; wp --path=/home/ny5agbo/public_html option get page_on_front" 2>&1 | grep -v post-quantum
```

- [ ] **Step 2: If needed, create a `/home/` page and point Settings → Reading at it**

If `show_on_front` is `page` and `page_on_front` is set, you're done; proceed to Step 3.

If not, create a page via WP Admin → Pages → Add New, title "Home", slug `home`, no content. Then Settings → Reading → "A static page" → select "Home". This routes `/` to `front-page.php` with the Home page as queried.

- [ ] **Step 3: Set a Featured Image on the /home/ page**

Via WP Admin on the Home page, upload or pick a hero image from Media Library. Publish.

- [ ] **Step 4: Verify the hero picks it up**

Curl the homepage and confirm `background-image` URL points at the uploaded attachment, not the Unsplash fallback.

---

## Task 14: Deploy everything

**Files:** none.

- [ ] **Step 1: Push**

```bash
cd "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child"
git push origin master
```

- [ ] **Step 2: Pull on prod**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "cd /home/ny5agbo/public_html/wp-content/themes/bootscore-child && git pull origin master" 2>&1 | grep -v post-quantum
```

- [ ] **Step 3: SCP main.css**

```bash
scp -i ~/.ssh/dontleak "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child/assets/css/main.css" ny5agbo@65.181.116.183:/home/ny5agbo/public_html/wp-content/themes/bootscore-child/assets/css/main.css
```

- [ ] **Step 4: Purge WP Rocket**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "rm -rf /home/ny5agbo/public_html/wp-content/cache/wp-rocket/*; wp --path=/home/ny5agbo/public_html eval 'if(function_exists(\"rocket_clean_domain\")){rocket_clean_domain();echo \"ok\";}'" 2>&1 | grep -v post-quantum
```

- [ ] **Step 5: Final visual check**

Screenshot `https://truelight.digital/` at 1440×900. Verify: new hero image + mission headline, 4 pillar cards, direct-help prose block, 3 latest-from-Formation cards (no junk), navy subscribe section. No test-posts visible. Header nav no longer overlapping logo. "Book a Call" button doesn't wrap.

---

## Follow-ups (not in this plan)

- Replace placeholder Unsplash hero image with a curated asset (Phase 2, same as `/formation/`).
- Wire the newsletter Gravity Form to a mailing-list provider (Mailchimp / ConvertKit / native GF notifications).
- Audit other pages for stat blocks if any remain (e.g. `/seo-for-churches/`, `/branding-identity/` service sub-pages).
- Testimonials section returns to the homepage once real quotes from priests / parish secretaries exist.

---

## Self-review notes

**Spec coverage:** every section in the spec maps to a task (Hero=2, Formation preview=3, direct-help=5, blog-teaser rewrite=6, subscribe=7). Bugs B1/B2/B3 are tasks 8/9/10. Service-page stats sweep = task 11. Markdown bug = task 12.

**Placeholder scan:** `$form_id = 0; // REPLACE` is marked as a deliberate plan-gated lookup, not a TBD. Task 7 Step 1 explicitly blocks until the real ID is known.

**Type consistency:** class names (`.formation-hero--image__ctas`, `.tld-home-formation-preview__*`, `.tld-pillar-preview-card__*`, `.tld-home-direct-help__*`, `.tld-home-subscribe__*`) are consistent between SCSS, partials, and references.
