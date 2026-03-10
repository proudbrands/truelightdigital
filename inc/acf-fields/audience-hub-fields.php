<?php
/**
 * ACF Field Group: Audience Hub Fields
 *
 * Hero, intro statement, challenge, what-we-do pillars, closing, linked services, CTA.
 * Structured sections render as designed landing page blocks in page-hub.php.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

acf_add_local_field_group([
  'key'      => 'group_tld_audience_hub',
  'title'    => 'Audience Hub Settings',
  'fields'   => [

    // ── Hero ──
    [
      'key'   => 'field_tld_hub_tab_hero',
      'label' => 'Hero',
      'type'  => 'tab',
    ],
    [
      'key'   => 'field_tld_hub_hero_subtitle',
      'label' => 'Hero Subtitle',
      'name'  => 'hero_subtitle',
      'type'  => 'textarea',
      'rows'  => 2,
      'instructions' => 'Short description shown below the page title in the hero section.',
    ],
    [
      'key'   => 'field_tld_hub_hero_eyebrow',
      'label' => 'Hero Eyebrow',
      'name'  => 'hero_eyebrow',
      'type'  => 'text',
      'instructions' => 'Small uppercase text above the title.',
      'default_value' => 'Who We Serve',
    ],
    [
      'key'     => 'field_tld_hub_segment_type',
      'label'   => 'Segment Type',
      'name'    => 'segment_type',
      'type'    => 'select',
      'choices' => [
        'churches'   => 'Churches & Ministries',
        'businesses' => 'Christian Businesses',
        'catholic'   => 'Catholic Organisations',
      ],
      'instructions' => 'Used to style the hub and filter related content.',
    ],

    // ── Intro Statement ──
    [
      'key'   => 'field_tld_hub_tab_intro',
      'label' => 'Intro Statement',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_hub_intro_heading',
      'label'        => 'Statement Heading',
      'name'         => 'hub_intro_heading',
      'type'         => 'textarea',
      'rows'         => 2,
      'instructions' => 'Bold centred statement. Use &lt;span class="text-gold"&gt; for gold accent. HTML allowed.',
    ],
    [
      'key'          => 'field_tld_hub_intro_text',
      'label'        => 'Supporting Text',
      'name'         => 'hub_intro_text',
      'type'         => 'textarea',
      'rows'         => 3,
      'instructions' => 'Paragraph below the statement heading.',
    ],

    // ── The Challenge ──
    [
      'key'   => 'field_tld_hub_tab_challenge',
      'label' => 'Challenge',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_hub_challenge_eyebrow',
      'label'        => 'Eyebrow',
      'name'         => 'hub_challenge_eyebrow',
      'type'         => 'text',
      'default_value' => 'Sound Familiar?',
    ],
    [
      'key'          => 'field_tld_hub_challenge_heading',
      'label'        => 'Challenge Heading',
      'name'         => 'hub_challenge_heading',
      'type'         => 'text',
      'instructions' => 'E.g. "The common church digital scenario".',
    ],
    [
      'key'          => 'field_tld_hub_challenge_text',
      'label'        => 'Challenge Text',
      'name'         => 'hub_challenge_text',
      'type'         => 'textarea',
      'rows'         => 4,
      'instructions' => '1–2 paragraphs describing the problem. Separate paragraphs with a blank line.',
    ],
    [
      'key'          => 'field_tld_hub_challenge_points',
      'label'        => 'Pain Points',
      'name'         => 'hub_challenge_points',
      'type'         => 'repeater',
      'layout'       => 'table',
      'min'          => 0,
      'max'          => 6,
      'button_label' => 'Add Pain Point',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_hub_challenge_point_text',
          'label' => 'Pain Point',
          'name'  => 'text',
          'type'  => 'text',
        ],
      ],
    ],

    // ── What We Do ──
    [
      'key'   => 'field_tld_hub_tab_services',
      'label' => 'What We Do',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_hub_services_eyebrow',
      'label'        => 'Eyebrow',
      'name'         => 'hub_services_eyebrow',
      'type'         => 'text',
      'default_value' => 'What We Do',
    ],
    [
      'key'          => 'field_tld_hub_services_heading',
      'label'        => 'Section Heading',
      'name'         => 'hub_services_heading',
      'type'         => 'text',
      'instructions' => 'E.g. "What we do for churches and ministries".',
    ],
    [
      'key'          => 'field_tld_hub_services',
      'label'        => 'Service Pillars',
      'name'         => 'hub_services',
      'type'         => 'repeater',
      'layout'       => 'block',
      'min'          => 0,
      'max'          => 6,
      'button_label' => 'Add Service',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_hub_service_title',
          'label' => 'Title',
          'name'  => 'title',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_hub_service_desc',
          'label' => 'Description',
          'name'  => 'description',
          'type'  => 'textarea',
          'rows'  => 3,
        ],
      ],
    ],

    // ── Closing ──
    [
      'key'   => 'field_tld_hub_tab_closing',
      'label' => 'Closing',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_hub_closing_heading',
      'label'        => 'Closing Heading',
      'name'         => 'hub_closing_heading',
      'type'         => 'text',
      'instructions' => 'E.g. "Built for how churches actually work".',
    ],
    [
      'key'          => 'field_tld_hub_closing_text',
      'label'        => 'Closing Text',
      'name'         => 'hub_closing_text',
      'type'         => 'textarea',
      'rows'         => 4,
      'instructions' => '1–2 closing paragraphs. Separate with a blank line.',
    ],

    // ── Linked Services ──
    [
      'key'   => 'field_tld_hub_tab_linked',
      'label' => 'Linked Services',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_hub_linked_services',
      'label'        => 'Featured Services',
      'name'         => 'linked_services',
      'type'         => 'relationship',
      'post_type'    => ['page'],
      'filters'      => ['search'],
      'max'          => 6,
      'return_format' => 'id',
      'instructions' => 'Select service pages to feature in the pathway cards section.',
    ],

    // ── CTA Overrides ──
    [
      'key'   => 'field_tld_hub_tab_cta',
      'label' => 'CTA Overrides',
      'type'  => 'tab',
    ],
    [
      'key'   => 'field_tld_hub_cta_heading',
      'label' => 'CTA Heading',
      'name'  => 'cta_heading',
      'type'  => 'text',
    ],
    [
      'key'   => 'field_tld_hub_cta_text',
      'label' => 'CTA Text',
      'name'  => 'cta_text',
      'type'  => 'textarea',
      'rows'  => 2,
    ],
    [
      'key'   => 'field_tld_hub_cta_button_text',
      'label' => 'CTA Button Text',
      'name'  => 'cta_button_text',
      'type'  => 'text',
    ],
  ],
  'location' => [
    [
      [
        'param'    => 'page_template',
        'operator' => '==',
        'value'    => 'page-templates/page-hub.php',
      ],
    ],
  ],
  'position' => 'normal',
  'style'    => 'default',
]);
