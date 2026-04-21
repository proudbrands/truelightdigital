# Individual Service-Page Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Trim `page-templates/page-service.php` from 11 sections to 6, upgrade the hero to `.formation-hero--image`, cast the why-us block as a dark-navy anchor, insert a pull-quote breath moment, restrain the gold accent. Keep all existing copy blocks. Spec: [docs/superpowers/specs/2026-04-21-service-page-redesign-design.md](../specs/2026-04-21-service-page-redesign-design.md).

**Architecture:** Rewrite one PHP template (`page-service.php`), add 4 SCSS blocks to `_bootscore-custom.scss`, mirror into `main.css`, retire 4 ACF field sets and add 2 new ones in `service-page-fields.php`. Dump existing ACF values on prod first so authored content for retired fields is recoverable.

**Tech Stack:** PHP 7.4 (WP), ACF Pro, Bootstrap 5, hand-maintained compiled CSS.

---

## File Structure

| File | Action | Purpose |
|------|--------|---------|
| `workfolder/service-pages-acf-dump-2026-04-21.json` | Create (not committed) | Back up every `audience_*` / `results_*` / `problem_*` / `related_services` ACF value across the 6 service pages before field retirement |
| `page-templates/page-service.php` | Rewrite | 6-section template; hero → problem → pillars → pullquote → whyus (dark) → process → FAQ → CTA |
| `assets/scss/_bootscore-custom.scss` | Modify | Add `.tld-service-pullquote`, pillar-numeral styling, scoped H2 sizes, gold-restraint overrides, default dark `.tld-whyus-section` background |
| `assets/css/main.css` | Modify (gitignored) | Mirror SCSS |
| `inc/acf-fields/service-page-fields.php` | Modify | Remove `problem_*` / `audience_*` / `results_*` / `related_services` fields; add `pullquote_text` + `pullquote_attribution` |

**Deploy pattern** (established): commit theme → push → ssh → `git pull` → `scp main.css` → WP Rocket purge.

**Sites affected** (all use `page-service.php` template):
- /services/christian-web-design/
- /services/seo-for-churches/
- /services/christian-branding/
- /services/ai-for-churches/
- /services/catholic-website-design/
- /services/christian-business-coaching/

---

## Task 1: Dump existing ACF values for retired fields

**Files:**
- Create: `workfolder/service-pages-acf-dump-2026-04-21.json`

- [ ] **Step 1: Find the service-page post IDs on prod**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "wp --path=/home/ny5agbo/public_html post list --post_type=page --meta_key=_wp_page_template --meta_value=page-templates/page-service.php --format=csv --fields=ID,post_title,post_name" 2>&1 | grep -v "post-quantum\|store now"
```
Note the IDs returned. Expected: 6 rows (one per service).

- [ ] **Step 2: Dump ACF values to local workfolder**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "wp --path=/home/ny5agbo/public_html eval '
\$ids = []; 
\$rows = get_posts([\"post_type\"=>\"page\",\"meta_key\"=>\"_wp_page_template\",\"meta_value\"=>\"page-templates/page-service.php\",\"posts_per_page\"=>-1,\"fields\"=>\"ids\"]);
\$out = [];
foreach(\$rows as \$id){
  \$out[\$id] = [
    \"title\"=>get_the_title(\$id),
    \"slug\"=>get_post_field(\"post_name\",\$id),
    \"problem_items\"=>get_field(\"problem_items\",\$id),
    \"problems_heading\"=>get_field(\"problems_heading\",\$id),
    \"problems_intro\"=>get_field(\"problems_intro\",\$id),
    \"audience_heading\"=>get_field(\"audience_heading\",\$id),
    \"audience_items\"=>get_field(\"audience_items\",\$id),
    \"results_heading\"=>get_field(\"results_heading\",\$id),
    \"results_items\"=>get_field(\"results_items\",\$id),
    \"related_services\"=>get_field(\"related_services\",\$id),
  ];
}
echo json_encode(\$out, JSON_PRETTY_PRINT);' 2>&1" 2>&1 | grep -v "post-quantum\|store now" > "C:/Users/Sean/Local Sites/church-2/app/public/workfolder/service-pages-acf-dump-2026-04-21.json"
```

- [ ] **Step 3: Verify the dump contains data**

```bash
ls -l "C:/Users/Sean/Local Sites/church-2/app/public/workfolder/service-pages-acf-dump-2026-04-21.json"
head -20 "C:/Users/Sean/Local Sites/church-2/app/public/workfolder/service-pages-acf-dump-2026-04-21.json"
```
Expected: non-empty file with JSON starting `{` — 6 numeric-keyed blocks of ACF values. If the file is empty or only `[]`, STOP and investigate — don't proceed to retire fields.

- [ ] **Step 4: No commit.** Workfolder file stays local, outside the theme repo.

---

## Task 2: Add SCSS — pullquote, pillar numerals, H2 hierarchy, gold restraint, whyus dark default

**Files:**
- Modify: `assets/scss/_bootscore-custom.scss` (append one block near other `.tld-*` service-related styles)
- Modify: `assets/css/main.css` (mirror at end of file, before `sourceMappingURL` comment)

- [ ] **Step 1: Append to `_bootscore-custom.scss`**

Append this block at the end of the file (or find the existing `.tld-whyus-section` block and add near it):

```scss
// ── Service page redesign (2026-04-21) ──────────────────────────

// Pull-quote breath moment between pillars and process.
.tld-service-pullquote {
  padding: 4rem 0;
  background: #fff;
  text-align: center;

  &__mark {
    color: var(--tld-gold);
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 4rem;
    line-height: 0.5;
    margin: 0 0 1rem;
    display: block;
  }
  &__text {
    font-family: 'Playfair Display', Georgia, serif;
    font-style: italic;
    font-size: clamp(1.3rem, 2vw, 1.7rem);
    line-height: 1.4;
    color: var(--tld-dark-navy);
    max-width: 32ch;
    margin: 0 auto 1rem;
    font-weight: 400;
  }
  &__attribution {
    font-family: 'Inter', sans-serif;
    font-size: 0.82rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--tld-muted);
    margin: 0;
  }
}

// Pillar cards get a serif italic numeral in gold.
.tld-pillar-card {
  position: relative;

  &__numeral {
    font-family: 'Playfair Display', Georgia, serif;
    font-style: italic;
    font-weight: 700;
    font-size: 1.2rem;
    color: var(--tld-gold);
    display: block;
    margin-bottom: 0.5rem;
    letter-spacing: 0.02em;
  }
}

// Why-us section defaults to the full dark-navy anchor even without a bg image.
.tld-whyus-section {
  background: var(--tld-dark-navy);
  color: #fff;

  .tld-heading-section { font-size: clamp(1.5rem, 2.4vw, 1.8rem); }
  .tld-whyus-icon-wrap svg { color: rgba(255, 255, 255, 0.55); }
}

// Problem statement H2 is the emotional hook — largest on the page.
.tld-problem-heading {
  font-size: clamp(1.75rem, 3vw, 2.4rem) !important;
  line-height: 1.25;
}

// Gold restraint: demote the inline 'seconds' highlight to confident navy
// (the hero eyebrow, pillar numeral, pullquote mark, process numeral, and
// CTA button are the 5 deliberate gold surfaces). Remove opacity haze.
.tld-problem-heading .text-gold {
  color: var(--tld-dark-navy) !important;
  font-weight: 700;
}

// Process section H2s stay at standard workhorse size.
.tld-process-dark .tld-heading-section,
.tld-faq-modern .tld-heading-section {
  font-size: clamp(1.4rem, 2.1vw, 1.6rem);
}
```

- [ ] **Step 2: Mirror into `assets/css/main.css`**

Append at the end of `main.css` (before the `/*# sourceMappingURL=... */` line). Resolve CSS custom-prop names (`var(--tld-gold)` etc.) are fine as-is — they resolve at browser runtime via the site-wide root block that's already in main.css.

Use the same rules verbatim — they already use CSS custom properties that main.css understands:

```css
/* ── Service page redesign (2026-04-21) ────────────────── */
.tld-service-pullquote {
  padding: 4rem 0;
  background: #fff;
  text-align: center;
}
.tld-service-pullquote__mark {
  color: var(--tld-gold);
  font-family: 'Playfair Display', Georgia, serif;
  font-size: 4rem;
  line-height: 0.5;
  margin: 0 0 1rem;
  display: block;
}
.tld-service-pullquote__text {
  font-family: 'Playfair Display', Georgia, serif;
  font-style: italic;
  font-size: clamp(1.3rem, 2vw, 1.7rem);
  line-height: 1.4;
  color: var(--tld-dark-navy);
  max-width: 32ch;
  margin: 0 auto 1rem;
  font-weight: 400;
}
.tld-service-pullquote__attribution {
  font-family: 'Inter', sans-serif;
  font-size: 0.82rem;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--tld-muted);
  margin: 0;
}

.tld-pillar-card { position: relative; }
.tld-pillar-card__numeral {
  font-family: 'Playfair Display', Georgia, serif;
  font-style: italic;
  font-weight: 700;
  font-size: 1.2rem;
  color: var(--tld-gold);
  display: block;
  margin-bottom: 0.5rem;
  letter-spacing: 0.02em;
}

.tld-whyus-section {
  background: var(--tld-dark-navy);
  color: #fff;
}
.tld-whyus-section .tld-heading-section { font-size: clamp(1.5rem, 2.4vw, 1.8rem); }
.tld-whyus-section .tld-whyus-icon-wrap svg { color: rgba(255, 255, 255, 0.55); }

.tld-problem-heading {
  font-size: clamp(1.75rem, 3vw, 2.4rem) !important;
  line-height: 1.25;
}
.tld-problem-heading .text-gold {
  color: var(--tld-dark-navy) !important;
  font-weight: 700;
}

.tld-process-dark .tld-heading-section,
.tld-faq-modern .tld-heading-section {
  font-size: clamp(1.4rem, 2.1vw, 1.6rem);
}
```

- [ ] **Step 3: Commit (SCSS only — main.css is gitignored)**

```bash
cd "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child"
git add assets/scss/_bootscore-custom.scss
git commit -m "Service page SCSS: pullquote, pillar numerals, dark whyus anchor, H2 hierarchy, gold restraint

Co-Authored-By: Claude Opus 4.7 (1M context) <noreply@anthropic.com>"
```

---

## Task 3: Retire `problem_*` / `audience_*` / `results_*` / `related_services` ACF fields; add pullquote fields

**Files:**
- Modify: `inc/acf-fields/service-page-fields.php`

- [ ] **Step 1: Remove the 4 field groups from the `'fields' => [ ... ]` array**

Open `inc/acf-fields/service-page-fields.php`. Find and delete **the entire array entry** for each of the following fields (keyed on the `'name'` value):

- `problems_heading` (the heading field)
- `problems_intro` (the intro field)
- `problem_items` (the repeater)
- `audience_heading`
- `audience_items` (the repeater)
- `results_heading`
- `results_items` (the repeater)
- `related_services` (relationship field)

Also remove any sibling `tab` entries that were introducing these now-retired sections (e.g. `field_tld_svc_tab_problems`, `field_tld_svc_tab_audience`, `field_tld_svc_tab_results`, `field_tld_svc_tab_related` — if present).

Use the Read tool to load the full file first; use the Edit tool with enough surrounding lines that each delete is unambiguous.

- [ ] **Step 2: Add the two new pullquote fields**

After the existing `whyus_items` repeater definition (find it by its `'name' => 'whyus_items'` line — should be around line 269 pre-edit), insert these two new field arrays immediately after `whyus_items`'s closing bracket (and any trailing comma):

```php
    [
      'key'          => 'field_tld_svc_pullquote_text',
      'label'        => 'Pull-quote text',
      'name'         => 'pullquote_text',
      'type'         => 'textarea',
      'rows'         => 3,
      'instructions' => 'Optional. Centred italic quote shown between the pillars and the why-us section. 20–40 words. If left blank, defaults to the first Differentiator description.',
    ],
    [
      'key'          => 'field_tld_svc_pullquote_attribution',
      'label'        => 'Pull-quote attribution',
      'name'         => 'pullquote_attribution',
      'type'         => 'text',
      'instructions' => 'Optional. Small line under the quote (e.g. "— Sean, True Light Digital"). Leave blank for no attribution.',
    ],
```

- [ ] **Step 3: Syntax check on prod PHP**

```bash
scp -i ~/.ssh/dontleak "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child/inc/acf-fields/service-page-fields.php" ny5agbo@65.181.116.183:/tmp/spf.php 2>&1 | grep -v "post-quantum\|store now"
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "php -l /tmp/spf.php && rm /tmp/spf.php" 2>&1 | grep -v "post-quantum\|store now"
```
Expected: `No syntax errors detected`. If it fails, STOP and report BLOCKED.

- [ ] **Step 4: Commit**

```bash
cd "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child"
git add inc/acf-fields/service-page-fields.php
git commit -m "Service page ACF: retire 4 field groups, add pullquote fields

Removed: problem_* (4 fields), audience_* (2), results_* (2), related_services (1).
Values dumped to workfolder/service-pages-acf-dump-2026-04-21.json before removal.

Added: pullquote_text (textarea), pullquote_attribution (text).

Co-Authored-By: Claude Opus 4.7 (1M context) <noreply@anthropic.com>"
```

---

## Task 4: Rewrite `page-service.php` to 6 sections

**Files:**
- Rewrite: `page-templates/page-service.php` (full replacement)

- [ ] **Step 1: Replace the file contents with:**

```php
<?php
/**
 * Template Name: Service Page
 *
 * Six-section service page. Hero → Problem → Pillars → Pull-quote →
 * Why Us (dark anchor) → Process → FAQ → CTA. All copy kept from the
 * previous template; audience / results / related / inline-CTA /
 * testimonials sections removed. See docs/superpowers/specs/
 * 2026-04-21-service-page-redesign-design.md.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

get_header();

// ── ACF fields ──
$eyebrow         = function_exists('get_field') ? (get_field('hero_eyebrow') ?: 'Our Services') : 'Our Services';
$subtitle        = function_exists('get_field') ? get_field('hero_subtitle') : '';
$cta1_text       = function_exists('get_field') ? get_field('hero_cta_primary_text') : '';
$cta2_text       = function_exists('get_field') ? get_field('hero_cta_secondary_text') : '';
$cta2_url        = function_exists('get_field') ? get_field('hero_cta_secondary_url') : '';
$intro_stmt      = function_exists('get_field') ? get_field('intro_statement') : '';
$intro_text      = function_exists('get_field') ? get_field('intro_text') : '';
$pillars         = function_exists('get_field') ? get_field('service_pillars') : [];
$pillars_heading = function_exists('get_field') ? get_field('pillars_heading') : '';
$whyus           = function_exists('get_field') ? get_field('whyus_items') : [];
$whyus_heading   = function_exists('get_field') ? get_field('whyus_heading') : '';
$whyus_bg        = function_exists('get_field') ? get_field('whyus_bg_image') : '';
$steps           = function_exists('get_field') ? get_field('process_steps') : [];
$process_heading = function_exists('get_field') ? get_field('process_heading') : '';
$process_bg      = function_exists('get_field') ? get_field('process_bg_image') : '';
$faqs            = function_exists('get_field') ? get_field('faq_items') : [];
$pullquote_text  = function_exists('get_field') ? get_field('pullquote_text') : '';
$pullquote_attr  = function_exists('get_field') ? get_field('pullquote_attribution') : '';

// Fallback: if no explicit pullquote, use the first whyus description.
if (!$pullquote_text && !empty($whyus[0]['description'])) {
  $pullquote_text = $whyus[0]['description'];
}

// Hero image — page Featured Image with Unsplash fallback (same pattern as /home/ and /formation/).
$hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full')
  ?: 'https://images.unsplash.com/photo-1519741497674-611481863552?w=2000&q=75';

$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>';
?>
<main id="primary" class="site-main">

  <?php if (have_posts()): while (have_posts()): the_post(); ?>

    <!-- Section 1: Hero -->
    <header class="formation-hero--image" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
      <div class="container">
        <?php if ($eyebrow): ?>
          <span class="formation-hero--image__eyebrow"><?= esc_html($eyebrow); ?></span>
        <?php endif; ?>
        <h1 class="formation-hero--image__title"><?= esc_html(get_the_title()); ?></h1>
        <?php if ($subtitle): ?>
          <p class="formation-hero--image__intro"><?= esc_html($subtitle); ?></p>
        <?php endif; ?>
        <?php if ($cta1_text || $cta2_text): ?>
          <div class="formation-hero--image__ctas">
            <?php if ($cta1_text): ?>
              <a class="btn tld-btn-gold btn-lg tld-btn-arrow" href="#" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
                <?= esc_html($cta1_text); ?><?= $arrow_svg; ?>
              </a>
            <?php endif; ?>
            <?php if ($cta2_text): ?>
              <a class="btn btn-outline-light btn-lg" href="<?= esc_url($cta2_url ?: '#what-we-do'); ?>"><?= esc_html($cta2_text); ?></a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </header>

    <!-- Section 2: Problem statement / positioning intro -->
    <?php if ($intro_stmt): ?>
      <section class="tld-section tld-problem-statement">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-9 text-center">
              <h2 class="tld-problem-heading tld-reveal"><?= wp_kses_post($intro_stmt); ?></h2>
              <?php if ($intro_text): ?>
                <p class="tld-problem-text tld-reveal tld-reveal-d1"><?= esc_html($intro_text); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <!-- Section 3: Pillars / What's included -->
    <?php if ($pillars): ?>
      <section class="tld-section tld-pillars-section bg-off-white" id="what-we-do">
        <div class="container">
          <div class="text-center mb-5">
            <p class="tld-eyebrow tld-reveal">What We Do</p>
            <h2 class="tld-heading-section tld-reveal tld-reveal-d1"><?= esc_html($pillars_heading ?: 'What this service includes'); ?></h2>
          </div>
          <div class="row g-4">
            <?php foreach ($pillars as $i => $pillar): ?>
              <div class="col-md-6 col-lg-4 tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
                <div class="tld-pillar-card">
                  <span class="tld-pillar-card__numeral"><?= esc_html(str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                  <h3 class="tld-pillar-title"><?= esc_html($pillar['title']); ?></h3>
                  <p class="tld-pillar-text"><?= esc_html($pillar['description']); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <!-- Section 4: Pull-quote breath -->
    <?php if ($pullquote_text): ?>
      <section class="tld-service-pullquote tld-reveal">
        <div class="container">
          <span class="tld-service-pullquote__mark" aria-hidden="true">&ldquo;</span>
          <p class="tld-service-pullquote__text"><?= esc_html($pullquote_text); ?></p>
          <?php if ($pullquote_attr): ?>
            <p class="tld-service-pullquote__attribution"><?= esc_html($pullquote_attr); ?></p>
          <?php endif; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Section 5: Why us / Faith context (dark anchor) -->
    <?php if ($whyus): ?>
      <section class="tld-section tld-whyus-section<?= $whyus_bg ? ' has-bg-image' : ''; ?>"<?php if ($whyus_bg): ?> style="background-image: url('<?= esc_url($whyus_bg); ?>');"<?php endif; ?>>
        <div class="container">
          <div class="text-center mb-5">
            <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">The Difference</p>
            <h2 class="tld-heading-section text-white tld-reveal tld-reveal-d1"><?= esc_html($whyus_heading ?: 'Why churches choose us'); ?></h2>
          </div>
          <div class="row g-4">
            <?php foreach ($whyus as $i => $item): ?>
              <div class="col-md-6 col-lg-4 tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
                <div class="tld-whyus-card">
                  <div class="tld-whyus-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                      <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022z"/>
                    </svg>
                  </div>
                  <h3 class="tld-whyus-title"><?= esc_html($item['title']); ?></h3>
                  <p class="tld-whyus-desc"><?= esc_html($item['description']); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <!-- Section 6: Process / How we work -->
    <?php if ($steps): ?>
      <section class="tld-section tld-process-dark<?= $process_bg ? ' has-bg-image' : ''; ?>"<?php if ($process_bg): ?> style="background-image: url('<?= esc_url($process_bg); ?>');"<?php endif; ?> id="how-we-work">
        <div class="container">
          <div class="text-center mb-5">
            <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">The Process</p>
            <h2 class="tld-heading-section text-white tld-reveal tld-reveal-d1"><?= esc_html($process_heading ?: 'How we work'); ?></h2>
          </div>
          <div class="row g-4">
            <?php foreach ($steps as $i => $step): ?>
              <div class="col-md-6 col-lg-3 tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
                <div class="tld-step-card">
                  <span class="tld-step-number"><?= esc_html(str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                  <h3 class="tld-step-title"><?= esc_html($step['title']); ?></h3>
                  <p class="tld-step-text"><?= esc_html($step['description']); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <!-- Section 7: FAQ accordion -->
    <?php if ($faqs): ?>
      <section class="tld-section tld-faq-modern bg-off-white">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <div class="text-center mb-5">
                <p class="tld-eyebrow tld-reveal">FAQ</p>
                <h2 class="tld-heading-section tld-reveal tld-reveal-d1">Frequently asked questions</h2>
              </div>
              <div class="accordion tld-accordion tld-reveal tld-reveal-d2" id="serviceFaq">
                <?php foreach ($faqs as $i => $faq): $i++; ?>
                  <div class="accordion-item">
                    <h3 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-<?= $i; ?>">
                        <?= esc_html($faq['question']); ?>
                      </button>
                    </h3>
                    <div id="faq-<?= $i; ?>" class="accordion-collapse collapse" data-bs-parent="#serviceFaq">
                      <div class="accordion-body"><?= wp_kses_post($faq['answer']); ?></div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <!-- Section 8: Closing CTA -->
    <?php tld_render_cta(); ?>

  <?php endwhile; endif; ?>

</main>

<?php get_footer(); ?>
```

- [ ] **Step 2: Syntax check on prod PHP**

```bash
scp -i ~/.ssh/dontleak "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child/page-templates/page-service.php" ny5agbo@65.181.116.183:/tmp/ps.php 2>&1 | grep -v "post-quantum\|store now"
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "php -l /tmp/ps.php && rm /tmp/ps.php" 2>&1 | grep -v "post-quantum\|store now"
```
Expected: `No syntax errors detected`.

- [ ] **Step 3: Commit**

```bash
cd "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child"
git add page-templates/page-service.php
git commit -m "Rewrite page-service.php: 6 sections, bokeh hero, pullquote, dark anchor

11 sections -> 6. All existing copy kept. Audience, results, related,
inline CTA, and testimonials sections removed. Hero upgraded to the
.formation-hero--image pattern. Pillars grid gets serif italic gold
numerals. Pull-quote block between pillars and why-us pulls its text
from pullquote_text ACF (falls back to whyus_items[0]['description']).
Why-us becomes the page's dark-navy anchor.

Co-Authored-By: Claude Opus 4.7 (1M context) <noreply@anthropic.com>"
```

---

## Task 5: Deploy to production

**Files:** none locally — deploy-only.

- [ ] **Step 1: Push theme commits**

```bash
cd "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child"
git push origin master
```

- [ ] **Step 2: Pull on prod**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "cd /home/ny5agbo/public_html/wp-content/themes/bootscore-child && git pull origin master" 2>&1 | grep -v "post-quantum\|store now"
```
Expected: Fast-forward with 3 files listed — `inc/acf-fields/service-page-fields.php`, `page-templates/page-service.php`, `assets/scss/_bootscore-custom.scss`.

- [ ] **Step 3: SCP `main.css` to prod**

```bash
scp -i ~/.ssh/dontleak "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child/assets/css/main.css" ny5agbo@65.181.116.183:/home/ny5agbo/public_html/wp-content/themes/bootscore-child/assets/css/main.css 2>&1 | grep -v "post-quantum\|store now"
```

- [ ] **Step 4: Purge WP Rocket**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "rm -rf /home/ny5agbo/public_html/wp-content/cache/wp-rocket/* /home/ny5agbo/public_html/wp-content/cache/min/* 2>&1; wp --path=/home/ny5agbo/public_html eval 'if(function_exists(\"rocket_clean_domain\")){rocket_clean_domain();echo \"ok\";}' 2>&1" 2>&1 | grep -v "post-quantum\|store now" | tail -2
```
Expected: `ok`.

- [ ] **Step 5: Verify a service page renders new sections only**

```bash
curl -s "https://truelight.digital/services/christian-web-design/?nocache=$(date +%s)" > /tmp/svc.html
echo "--- new markers (expect >0):"
grep -cE "formation-hero--image|tld-pillar-card__numeral|tld-service-pullquote|tld-whyus-section|id=\"how-we-work\"" /tmp/svc.html
echo "--- retired markers (expect 0):"
grep -cE "tld-hero-service|tld-audience-section|tld-results-section|template-parts/inline-cta|template-parts/testimonials" /tmp/svc.html
```
Expected: first count > 4, second count = 0.

- [ ] **Step 6: Human visual check**

Screenshot `https://truelight.digital/services/christian-web-design/` at 1440×900 (via Claude-in-Chrome or a browser). Walk the page top to bottom and confirm: bokeh hero, problem statement H2 dominant, pillars grid with gold `01 / 02 / 03` numerals, pullquote centered in italic serif with gold opening mark, dark-navy why-us section as the visual anchor, process steps, FAQ, single closing CTA. No audience/results/related sections, no testimonials placeholder, no duplicate inline CTA above the process.

Repeat quickly on one other service (e.g. `/services/seo-for-churches/`) to confirm the template behaves across pages with different content.

If anything looks broken, revert the three commits with `git revert HEAD~2..HEAD` locally and push.

---

## Follow-ups (not in this plan)

- If any service page had a particularly good `problem_items` ("what changes") list, fold the phrasing into that page's `intro_text` or into one of its `service_pillars` descriptions. Not blocking; editorial decision per page.
- When real testimonials exist, add a testimonials section back into `page-service.php` between process and FAQ.
- When case studies exist, add a results block. Don't add it before data exists.
- Consider unifying hero patterns across `/services/<service>/`, `/formation/`, and `/home/` into a single reusable `.tld-hero` component — currently `.formation-hero--image` carries both jobs. Separate spec.

---

## Self-review

**Spec coverage:**
- Keep all copy blocks → Task 4 preserves every field read and render (pillars, whyus, process, faq, intro_statement/text).
- Trim sections 11 → 6 → Task 4 removes audience/results/related/inline-cta/testimonials calls.
- Hero upgrade → Task 4 replaces `<section class="tld-hero-service">` with `<header class="formation-hero--image">`.
- Dark whyus anchor → Task 2 sets `.tld-whyus-section` default background to `--tld-dark-navy`; Task 4 keeps the optional `$whyus_bg` override.
- Pullquote → Task 2 adds `.tld-service-pullquote` styles; Task 4 renders the section with fallback to `whyus_items[0]['description']`; Task 3 adds the `pullquote_text`/`pullquote_attribution` ACF fields.
- Gold restraint → Task 2 demotes the inline "seconds" highlight and desaturates whyus check icons; Task 4 keeps gold only on hero eyebrow, pillar numerals, pullquote mark, process numerals (inherited from existing `.tld-step-number`), and CTA buttons.
- H2 hierarchy → Task 2's scoped size overrides for `.tld-problem-heading`, `.tld-whyus-section .tld-heading-section`, and `.tld-process-dark / .tld-faq-modern .tld-heading-section`.
- ACF retirement + pullquote additions → Task 3.
- ACF dump for safekeeping → Task 1.

**Placeholder scan:** no TBDs, all code blocks complete, all commands have expected output.

**Type consistency:** class names (`.formation-hero--image__*`, `.tld-service-pullquote__*`, `.tld-pillar-card__numeral`, `.tld-whyus-section`, `.tld-problem-heading`) match between SCSS (Task 2) and template (Task 4) and retire-list (Task 3).
