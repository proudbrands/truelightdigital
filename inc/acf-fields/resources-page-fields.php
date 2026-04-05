<?php
/**
 * ACF Fields: Resources Landing Page Template
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

acf_add_local_field_group([
  'key'      => 'group_tld_resources_page',
  'title'    => 'Resources Page Settings',
  'location' => [
    [
      ['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-resources.php'],
    ],
  ],
  'position'   => 'normal',
  'style'      => 'default',
  'menu_order' => 0,
  'fields'     => [

    // ── Tab: Hero ──
    [
      'key'   => 'field_respage_tab_hero',
      'label' => 'Hero',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_respage_hero_eyebrow',
      'label'         => 'Hero Eyebrow',
      'name'          => 'resources_hero_eyebrow',
      'type'          => 'text',
      'default_value' => 'Free Resources',
    ],
    [
      'key'           => 'field_respage_hero_title',
      'label'         => 'Hero Title',
      'name'          => 'resources_hero_title',
      'type'          => 'text',
      'default_value' => 'Practical tools for churches and Christian organisations',
    ],
    [
      'key'           => 'field_respage_hero_subtitle',
      'label'         => 'Hero Subtitle',
      'name'          => 'resources_hero_subtitle',
      'type'          => 'textarea',
      'rows'          => 2,
      'new_lines'     => 'br',
      'default_value' => 'Communication plans, strategy frameworks, and templates built for faith-based teams. Download what you need, no strings attached.',
    ],

    // ── Tab: Email CTA ──
    [
      'key'   => 'field_respage_tab_email',
      'label' => 'Email Signup',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_respage_email_heading',
      'label'         => 'Email CTA Heading',
      'name'          => 'resources_email_heading',
      'type'          => 'text',
      'default_value' => 'Get new resources in your inbox',
    ],
    [
      'key'           => 'field_respage_email_text',
      'label'         => 'Email CTA Text',
      'name'          => 'resources_email_text',
      'type'          => 'textarea',
      'rows'          => 2,
      'new_lines'     => 'br',
      'default_value' => 'We publish new frameworks, templates, and guides regularly. Join the list and we will send them to you when they are ready.',
    ],
    [
      'key'           => 'field_respage_email_form_id',
      'label'         => 'Email Form ID (Gravity Forms)',
      'name'          => 'resources_email_form_id',
      'type'          => 'number',
      'instructions'  => 'Gravity Forms form ID for the email signup. Create a simple form with Name + Email fields.',
    ],

    // ── Tab: CTA Overrides ──
    [
      'key'   => 'field_respage_tab_cta',
      'label' => 'CTA Overrides',
      'type'  => 'tab',
    ],
    [
      'key'   => 'field_respage_cta_heading',
      'label' => 'CTA Heading',
      'name'  => 'cta_heading',
      'type'  => 'text',
    ],
    [
      'key'   => 'field_respage_cta_text',
      'label' => 'CTA Text',
      'name'  => 'cta_text',
      'type'  => 'textarea',
      'rows'  => 2,
    ],
    [
      'key'   => 'field_respage_cta_button',
      'label' => 'CTA Button Text',
      'name'  => 'cta_button_text',
      'type'  => 'text',
    ],
  ],
]);
