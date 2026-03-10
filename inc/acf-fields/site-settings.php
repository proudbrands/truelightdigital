<?php
/**
 * ACF Field Group: Site Settings (Options Page)
 *
 * Company info, social URLs, default CTA text.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

acf_add_local_field_group([
  'key'      => 'group_tld_site_settings',
  'title'    => 'Site Settings',
  'fields'   => [

    // ── Company Info Tab ──
    [
      'key'   => 'field_tld_tab_company',
      'label' => 'Company Info',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_company_name',
      'label'        => 'Company Name',
      'name'         => 'company_name',
      'type'         => 'text',
      'default_value' => 'True Light Digital',
    ],
    [
      'key'   => 'field_tld_company_tagline',
      'label' => 'Tagline',
      'name'  => 'company_tagline',
      'type'  => 'textarea',
      'rows'  => 2,
      'default_value' => 'Faith-driven digital strategy for churches, Christian businesses, and kingdom-minded entrepreneurs.',
    ],
    [
      'key'   => 'field_tld_company_email',
      'label' => 'Email',
      'name'  => 'company_email',
      'type'  => 'email',
    ],
    [
      'key'   => 'field_tld_company_phone',
      'label' => 'Phone',
      'name'  => 'company_phone',
      'type'  => 'text',
    ],

    // ── Social Tab ──
    [
      'key'   => 'field_tld_tab_social',
      'label' => 'Social Media',
      'type'  => 'tab',
    ],
    [
      'key'   => 'field_tld_social_linkedin',
      'label' => 'LinkedIn URL',
      'name'  => 'social_linkedin',
      'type'  => 'url',
    ],
    [
      'key'   => 'field_tld_social_facebook',
      'label' => 'Facebook URL',
      'name'  => 'social_facebook',
      'type'  => 'url',
    ],
    [
      'key'   => 'field_tld_social_instagram',
      'label' => 'Instagram URL',
      'name'  => 'social_instagram',
      'type'  => 'url',
    ],
    [
      'key'   => 'field_tld_social_youtube',
      'label' => 'YouTube URL',
      'name'  => 'social_youtube',
      'type'  => 'url',
    ],

    // ── Homepage Background Images Tab ──
    [
      'key'   => 'field_tld_tab_home_bg',
      'label' => 'Homepage Backgrounds',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_home_hero_bg_image',
      'label'        => 'Hero Background Image',
      'name'         => 'home_hero_bg_image',
      'type'         => 'image',
      'return_format' => 'url',
      'preview_size'  => 'medium',
      'instructions' => 'Optional background image for the homepage hero. Center/center, full-width, dark gradient overlay.',
    ],
    [
      'key'          => 'field_tld_home_proof_bg_image',
      'label'        => 'Proof Strip Background Image',
      'name'         => 'home_proof_bg_image',
      'type'         => 'image',
      'return_format' => 'url',
      'preview_size'  => 'medium',
      'instructions' => 'Optional background image for the "What better looks like" proof strip.',
    ],
    [
      'key'          => 'field_tld_home_cta_bg_image',
      'label'        => 'CTA Background Image',
      'name'         => 'home_cta_bg_image',
      'type'         => 'image',
      'return_format' => 'url',
      'preview_size'  => 'medium',
      'instructions' => 'Optional background image for the bottom CTA section.',
    ],

    // ── Default CTA Tab ──
    [
      'key'   => 'field_tld_tab_cta',
      'label' => 'Default CTA',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_default_cta_heading',
      'label'        => 'Default CTA Heading',
      'name'         => 'default_cta_heading',
      'type'         => 'text',
      'default_value' => 'Ready to Grow Your Digital Presence?',
    ],
    [
      'key'          => 'field_tld_default_cta_text',
      'label'        => 'Default CTA Text',
      'name'         => 'default_cta_text',
      'type'         => 'textarea',
      'rows'         => 2,
      'default_value' => 'Book a free discovery call and let\'s talk about what better looks like for your organisation.',
    ],
    [
      'key'          => 'field_tld_default_cta_button_text',
      'label'        => 'Default CTA Button Text',
      'name'         => 'default_cta_button_text',
      'type'         => 'text',
      'default_value' => 'Book a Discovery Call',
    ],
    [
      'key'          => 'field_tld_default_cta_button_url',
      'label'        => 'Default CTA Button URL',
      'name'         => 'default_cta_button_url',
      'type'         => 'url',
      'default_value' => '/contact/',
    ],
  ],
  'location' => [
    [
      [
        'param'    => 'options_page',
        'operator' => '==',
        'value'    => 'tld-site-settings',
      ],
    ],
  ],
]);
