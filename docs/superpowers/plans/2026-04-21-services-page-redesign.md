# Services Page Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace `page-templates/page-services.php` with a lean 4-section page (Hero → Audiences → Services → CTA) and swap the site-wide `#tld-discovery-modal` body from a Gravity Form embed to a HubSpot meetings iframe. Spec: [docs/superpowers/specs/2026-04-21-services-page-redesign-design.md](../specs/2026-04-21-services-page-redesign-design.md).

**Architecture:** Rewrite `page-services.php` with hardcoded content in the shape of the pre-redesign homepage DNA — reusing the `.tld-hover-card`, `.tld-service-stack`/`.tld-service-row`, and `.tld-cta-modern` components that are already present in `_bootscore-custom.scss`. Swap one function body in `functions.php` to replace the Gravity Form with HubSpot's embed script. Delete the `services_*` ACF field group (values dumped to a file first for safekeeping).

**Tech Stack:** PHP 7.4 (WP), Bootstrap 5, existing SCSS in `_bootscore-custom.scss` (no new component styles needed), HubSpot Meetings embed script.

---

## File Structure

| File | Action | Purpose |
|------|--------|---------|
| `page-templates/page-services.php` | **Rewrite** | New 4-section template, hardcoded content |
| `functions.php` | **Edit** | Swap `#tld-discovery-modal` body from GF embed to HubSpot iframe; widen to `modal-xl`; update subtitle |
| `inc/acf-fields/services-page-fields.php` | **Delete** | ACF field group no longer used |
| `inc/acf-fields.php` (or similar includes file) | **Edit** | Remove the `require_once` for the deleted file |
| `workfolder/services-acf-dump-2026-04-21.json` | **Create** (not committed) | Back up the current ACF values on prod before we delete the field group |

All SCSS components (`.formation-hero--image`, `.tld-hover-card`, `.tld-service-stack`, `.tld-service-row`, `.tld-cta-modern`) are already present in `_bootscore-custom.scss` / `_formation.scss` — no SCSS changes needed for this plan.

**Deploy pattern** (established): commit theme → push → ssh → `git pull` → scp main.css if touched → WP Rocket purge.

---

## Task 1: Dump existing services_* ACF values from prod for safekeeping

**Files:**
- Create: `workfolder/services-acf-dump-2026-04-21.json` (local, gitignored dir)

- [ ] **Step 1: Find the services page ID on prod**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "wp --path=/home/ny5agbo/public_html post list --post_type=page --name=services --format=csv --fields=ID,post_title" 2>&1 | grep -v "post-quantum\|store now"
```
Note the ID returned (expected: single row with the services page).

- [ ] **Step 2: Dump all services_* post meta for that page ID**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "wp --path=/home/ny5agbo/public_html post meta list <ID> --format=json" 2>&1 | grep -v "post-quantum\|store now" > "C:/Users/Sean/Local Sites/church-2/app/public/workfolder/services-acf-dump-2026-04-21.json"
```

- [ ] **Step 3: Verify the dump has content**

```bash
ls -l "C:/Users/Sean/Local Sites/church-2/app/public/workfolder/services-acf-dump-2026-04-21.json"
```
Expected: non-empty file (usually several KB). If empty, STOP and investigate — don't proceed to delete ACF fields.

- [ ] **Step 4: No commit.** The dump lives in `workfolder/` which is outside the theme repo. Deployment artefact only.

---

## Task 2: Rewrite `page-templates/page-services.php`

**Files:**
- Rewrite: `page-templates/page-services.php`

- [ ] **Step 1: Replace the file contents**

```php
<?php
/**
 * Template Name: Services Landing
 *
 * Four-section services hub: Hero → Audiences → Services → CTA.
 * Content is hardcoded (stable) matching the post-redesign homepage pattern.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();

// Hero image — page Featured Image with Unsplash fallback (same pattern as homepage).
$hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full')
  ?: 'https://images.unsplash.com/photo-1519741497674-611481863552?w=2000&q=75';

$audiences = [
  [
    'num'   => '01',
    'title' => 'Christian Businesses',
    'text'  => 'You want growth without the usual tradeoffs. We help founders and leadership teams sharpen their message, improve their website, and turn digital marketing into a real business asset.',
    'url'   => '/christian-business/',
  ],
  [
    'num'   => '02',
    'title' => 'Churches & Ministries',
    'text'  => 'The people you have not met yet are searching online. We help churches improve discoverability, modernize their digital presence, and use tools like SEO and AI with wisdom.',
    'url'   => '/churches-ministries/',
  ],
  [
    'num'   => '03',
    'title' => 'Catholic Organisations',
    'text'  => 'Parishes, dioceses, schools, and apostolates need digital work that feels reverent, credible, and clear. We build for Catholic audiences with real familiarity.',
    'url'   => '/catholic-organisations/',
  ],
];

$services = [
  [
    'num'   => '01',
    'title' => 'Web Design &amp; Development',
    'desc'  => 'Sites that explain who you are quickly, guide the next step, perform well on mobile, and give your team a platform you can grow into.',
    'url'   => '/services/christian-web-design/',
  ],
  [
    'num'   => '02',
    'title' => 'Search Engine Optimisation',
    'desc'  => 'Churches need local visibility. Christian businesses need qualified discovery. Pages that rank for what you actually do.',
    'url'   => '/services/seo-for-churches/',
  ],
  [
    'num'   => '03',
    'title' => 'Branding &amp; Messaging',
    'desc'  => 'Define your voice, sharpen your message, and build an identity system that looks credible and feels aligned with your mission.',
    'url'   => '/services/christian-branding/',
  ],
  [
    'num'   => '04',
    'title' => 'AI Strategy &amp; Implementation',
    'desc'  => 'Practical workflows, guardrails, and a trusted guide. Save time, improve communication, and keep human oversight where it belongs.',
    'url'   => '/services/ai-for-churches/',
  ],
];

$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>';
?>
<main id="primary" class="site-main">

  <?php if (have_posts()): while (have_posts()): the_post(); ?>

    <!-- Section 1: Hero -->
    <header class="formation-hero--image" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
      <div class="container">
        <span class="formation-hero--image__eyebrow">Direct help for the Church&rsquo;s communications</span>
        <h1 class="formation-hero--image__title">When reading isn&rsquo;t enough.</h1>
        <p class="formation-hero--image__intro">Sometimes a parish, ministry, or Christian business needs someone to do the work with them &mdash; a website rebuilt, a search presence that actually finds the people looking, branding that feels like the mission, practical AI that doesn&rsquo;t embarrass the Church. We do that.</p>
        <div class="formation-hero--image__ctas">
          <a class="btn tld-btn-gold btn-lg tld-btn-arrow" href="#" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">Book a Discovery Call <?= $arrow_svg; ?></a>
          <a class="btn btn-outline-light btn-lg" href="#what-we-do">See what we do</a>
        </div>
      </div>
    </header>

    <!-- Section 2: Audiences -->
    <section class="tld-section">
      <div class="container">
        <div class="text-center mb-5">
          <p class="tld-eyebrow tld-reveal">Who We Serve</p>
          <h2 class="tld-heading-section tld-reveal tld-reveal-d1">Choose the path that fits your mission</h2>
        </div>
        <div class="row g-4">
          <?php foreach ($audiences as $i => $a): ?>
            <div class="col-lg-4 tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
              <a href="<?= esc_url(home_url($a['url'])); ?>" class="tld-hover-card">
                <span class="tld-hover-card-number"><?= esc_html($a['num']); ?></span>
                <h3 class="tld-hover-card-title"><?= esc_html($a['title']); ?></h3>
                <p class="tld-hover-card-text"><?= esc_html($a['text']); ?></p>
                <span class="tld-hover-card-link">
                  Learn more
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
                </span>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Section 3: Services -->
    <section class="tld-section bg-off-white" id="what-we-do">
      <div class="container">
        <div class="text-center mb-5">
          <p class="tld-eyebrow tld-reveal">What We Do</p>
          <h2 class="tld-heading-section tld-reveal tld-reveal-d1">How we help, in practice.</h2>
        </div>
        <div class="tld-service-stack">
          <?php foreach ($services as $i => $s): ?>
            <a href="<?= esc_url(home_url($s['url'])); ?>" class="tld-service-row tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
              <span class="tld-service-row-num"><?= esc_html($s['num']); ?></span>
              <div class="tld-service-row-content">
                <h3 class="tld-service-row-title"><?= $s['title']; ?></h3>
                <p class="tld-service-row-desc"><?= $s['desc']; ?></p>
              </div>
              <span class="tld-service-row-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
              </span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Section 4: CTA -->
    <section class="tld-cta-modern">
      <div class="container">
        <div class="tld-cta-modern-inner">
          <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">Get Started</p>
          <h2 class="tld-cta-modern-heading tld-reveal tld-reveal-d1">Ready to build something stronger?</h2>
          <p class="tld-cta-modern-text tld-reveal tld-reveal-d2">A digital partner who understands faith, takes outcomes seriously, and knows how to make strategy usable. We work best with organisations that care about clarity, move with purpose, and invest in work that lasts.</p>
          <div class="tld-reveal tld-reveal-d3">
            <a href="#" class="btn tld-btn-gold btn-lg tld-btn-arrow" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
              Book a Discovery Call
              <?= $arrow_svg; ?>
            </a>
          </div>
        </div>
      </div>
    </section>

  <?php endwhile; endif; ?>

</main>
<?php get_footer(); ?>
```

- [ ] **Step 2: Syntax check via SSH**

```bash
scp -i ~/.ssh/dontleak "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child/page-templates/page-services.php" ny5agbo@65.181.116.183:/tmp/ps.php 2>&1 | grep -v "post-quantum\|store now"
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "php -l /tmp/ps.php && rm /tmp/ps.php" 2>&1 | grep -v "post-quantum\|store now"
```
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
cd "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child"
git add page-templates/page-services.php
git commit -m "Rewrite /services/ page: 4 sections, hardcoded, publication-first voice

Co-Authored-By: Claude Opus 4.7 (1M context) <noreply@anthropic.com>"
```

---

## Task 3: Swap the `#tld-discovery-modal` body to HubSpot

**Files:**
- Modify: `functions.php` lines ~215–232 (the modal render helper).

- [ ] **Step 1: Locate the modal block**

```bash
cd "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child"
grep -n 'id="tld-discovery-modal"' functions.php
```
Expected: a single hit near line 216. The block spans the modal `<div class="modal fade" id="tld-discovery-modal"...>` opening and runs ~17 lines to its closing `</div>` (it's one `<div class="modal-dialog">` wrapping a `<div class="modal-content">`).

- [ ] **Step 2: Replace the modal block**

Using the Edit tool, find and replace this exact block in `functions.php`:

**Find:**
```php
  <div class="modal fade" id="tld-discovery-modal" tabindex="-1" aria-labelledby="tld-discovery-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content tld-modal-content">
        <div class="modal-header tld-modal-header">
          <div>
            <h5 class="modal-title" id="tld-discovery-modal-label">Book a Discovery Call</h5>
            <p class="tld-modal-subtitle">Tell us a little about your project and we'll be in touch within one working day.</p>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body tld-modal-body">
          <?php gravity_form(TLD_DISCOVERY_FORM_ID, false, false, false, null, true); ?>
        </div>
      </div>
    </div>
  </div>
```

**Replace with:**
```php
  <div class="modal fade" id="tld-discovery-modal" tabindex="-1" aria-labelledby="tld-discovery-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content tld-modal-content">
        <div class="modal-header tld-modal-header">
          <div>
            <h5 class="modal-title" id="tld-discovery-modal-label">Book a Discovery Call</h5>
            <p class="tld-modal-subtitle">Pick a time that works. We&rsquo;ll send a calendar invite.</p>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body tld-modal-body">
          <!-- Start of Meetings Embed Script -->
          <div class="meetings-iframe-container" data-src="https://meetings-eu1.hubspot.com/sbrannon?embed=true"></div>
          <script type="text/javascript" src="https://static.hsappstatic.net/MeetingsEmbed/ex/MeetingsEmbedCode.js"></script>
          <!-- End of Meetings Embed Script -->
        </div>
      </div>
    </div>
  </div>
```

Three changes:
1. `modal-lg` → `modal-xl` (HubSpot scheduler needs more width).
2. Subtitle text updated.
3. `gravity_form(...)` replaced with HubSpot embed div + script.

- [ ] **Step 3: Syntax check**

```bash
scp -i ~/.ssh/dontleak "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child/functions.php" ny5agbo@65.181.116.183:/tmp/fn.php 2>&1 | grep -v "post-quantum\|store now"
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "php -l /tmp/fn.php && rm /tmp/fn.php" 2>&1 | grep -v "post-quantum\|store now"
```
Expected: `No syntax errors detected`

- [ ] **Step 4: Commit**

```bash
git add functions.php
git commit -m "Swap discovery modal body from Gravity Forms to HubSpot meetings embed

Modal is now modal-xl to fit HubSpot's scheduler UI. Subtitle updated.
Every Book-a-Discovery-Call trigger site-wide now routes to HubSpot.

Co-Authored-By: Claude Opus 4.7 (1M context) <noreply@anthropic.com>"
```

---

## Task 4: Remove the services-landing ACF field group

**Files:**
- Delete: `inc/acf-fields/services-page-fields.php`
- Modify: the file that `require_once`s it (likely `inc/acf-fields.php` or `functions.php`)

- [ ] **Step 1: Confirm the dump from Task 1 exists**

```bash
ls -l "C:/Users/Sean/Local Sites/church-2/app/public/workfolder/services-acf-dump-2026-04-21.json"
```
If absent, **STOP** — don't delete ACF field registrations without backing up the data.

- [ ] **Step 2: Find the include that loads the file**

```bash
cd "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child"
grep -rn "services-page-fields" --include="*.php" .
```
Expected: one or more `require_once` / `include_once` calls referencing the file.

- [ ] **Step 3: Remove the require line(s)**

For each file that includes `services-page-fields.php`, remove the single include line. Example:

```php
// BEFORE:
require_once get_stylesheet_directory() . '/inc/acf-fields/services-page-fields.php';

// AFTER: (line removed)
```

Use the Edit tool with exact context (a few lines before and after) to make the removal unambiguous.

- [ ] **Step 4: Delete the field-group file**

```bash
rm "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child/inc/acf-fields/services-page-fields.php"
```

- [ ] **Step 5: Syntax check the file(s) you modified**

For each modified includes file:
```bash
scp -i ~/.ssh/dontleak "<path>" ny5agbo@65.181.116.183:/tmp/af.php 2>&1 | grep -v "post-quantum\|store now"
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "php -l /tmp/af.php && rm /tmp/af.php" 2>&1 | grep -v "post-quantum\|store now"
```

- [ ] **Step 6: Commit**

```bash
git add -u
git commit -m "Remove services-landing ACF field group (values hardcoded in template)

Backed up current prod values to workfolder/services-acf-dump-2026-04-21.json
before removing. Field group was bespoke to page-services.php which no
longer reads any of these fields.

Co-Authored-By: Claude Opus 4.7 (1M context) <noreply@anthropic.com>"
```

---

## Task 5: Deploy to production

**Files:** none (deploy-only).

- [ ] **Step 1: Push theme commits**

```bash
cd "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child"
git push origin master
```

- [ ] **Step 2: Pull on prod**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "cd /home/ny5agbo/public_html/wp-content/themes/bootscore-child && git pull origin master" 2>&1 | grep -v "post-quantum\|store now"
```
Expected: Fast-forward with `functions.php`, `page-templates/page-services.php`, and one includes file listed; `services-page-fields.php` deleted.

- [ ] **Step 3: No main.css scp needed**

No SCSS was touched in this plan. Skip the scp step.

- [ ] **Step 4: Purge WP Rocket**

```bash
ssh -i ~/.ssh/dontleak ny5agbo@65.181.116.183 "rm -rf /home/ny5agbo/public_html/wp-content/cache/wp-rocket/* /home/ny5agbo/public_html/wp-content/cache/min/* 2>&1; wp --path=/home/ny5agbo/public_html eval 'if(function_exists(\"rocket_clean_domain\")){rocket_clean_domain();echo \"ok\";}' 2>&1" 2>&1 | grep -v "post-quantum\|store now" | tail -2
```
Expected: `ok`

- [ ] **Step 5: Smoke check — /services/ renders new sections**

```bash
curl -s "https://truelight.digital/services/?nocache=$(date +%s)" 2>&1 > /tmp/svc.html
echo ---new-hero-classes:; grep -cE "formation-hero--image|tld-hover-card|tld-service-stack|tld-cta-modern|When reading" /tmp/svc.html
echo ---old-removed-classes:; grep -cE "tld-hero-service|tld-problem-statement|tld-services-grid-section|tld-approach-section|tld-audience-hub-card" /tmp/svc.html
```
Expected: first number > 10. Second number = 0.

- [ ] **Step 6: Smoke check — modal contains HubSpot embed**

```bash
curl -s "https://truelight.digital/services/?nocache=$(date +%s)" 2>&1 | grep -c "meetings-iframe-container\|MeetingsEmbedCode.js"
```
Expected: >= 2 (the div and the script).

Also verify the Gravity Form embed is GONE from the modal on at least two unrelated pages:
```bash
for url in "https://truelight.digital/" "https://truelight.digital/formation/" "https://truelight.digital/library/"; do
  echo "---$url"
  curl -s "$url?nocache=$(date +%s)" 2>&1 | grep -c "gform_wrapper.*gform_wrapper_[0-9]\+" | head -1
done
```
The newsletter form (#10) on the homepage subscribe section still renders as a Gravity Form — that's expected. The discovery modal should no longer contain one.

- [ ] **Step 7: Human visual check**

Screenshot `https://truelight.digital/services/` at 1440×900 (via Claude-in-Chrome or browser). Verify: new bokeh hero with mission headline, 3 audience pathway cards, 4 services vertical rows, bold navy CTA at bottom. Click "Book a Discovery Call" on any button and confirm the modal opens with the HubSpot scheduler.

If anything looks broken, revert the three commits: `git revert HEAD~2..HEAD` and push.

---

## Follow-ups (not in this plan)

- Consider retiring `TLD_DISCOVERY_FORM_ID` Gravity Form (no longer used in the modal). Keep for now as a backup until HubSpot is battle-tested.
- Service sub-pages (`/services/christian-web-design/`, etc.) still use `page-service.php` — their hero pattern differs from the new `/services/` archive. Unification is a separate spec.
- Hero Featured Image on `/services/` — user needs to upload a bokeh asset via WP admin just like `/home/` (currently the `/home/` hero uses the TLD wordmark and needs swapping too — tracked as a separate follow-up from the homepage plan).

---

## Self-review notes

**Spec coverage:**
- Hero, Audiences, Services, CTA sections → Task 2.
- Modal body swap + modal-xl + subtitle update → Task 3.
- ACF field group removal → Task 4 (with safekeeping dump in Task 1).
- Deploy + verification → Task 5.

**Placeholder scan:** No TBDs, all code blocks complete, all commands exact with expected output.

**Type consistency:** Class names (`.formation-hero--image__ctas`, `.tld-hover-card*`, `.tld-service-row*`, `.tld-cta-modern*`) match what's already present in `_bootscore-custom.scss` and `_formation.scss` (verified before writing the plan).
