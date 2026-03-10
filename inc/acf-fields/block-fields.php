<?php
/**
 * ACF Field Group: Block Fields
 *
 * Fields for all 4 ACF blocks: FAQ Accordion, CTA Band, Process Steps, Stats Strip.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

// ── FAQ Accordion Block ──
acf_add_local_field_group([
  'key'      => 'group_tld_block_faq',
  'title'    => 'FAQ Accordion',
  'fields'   => [
    [
      'key'          => 'field_tld_faq_heading',
      'label'        => 'Section Heading',
      'name'         => 'faq_heading',
      'type'         => 'text',
      'default_value' => 'Frequently Asked Questions',
    ],
    [
      'key'        => 'field_tld_faq_items',
      'label'      => 'FAQ Items',
      'name'       => 'faq_items',
      'type'       => 'repeater',
      'layout'     => 'block',
      'button_label' => 'Add Question',
      'sub_fields' => [
        [
          'key'   => 'field_tld_faq_question',
          'label' => 'Question',
          'name'  => 'question',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_faq_answer',
          'label' => 'Answer',
          'name'  => 'answer',
          'type'  => 'wysiwyg',
          'tabs'  => 'all',
          'toolbar' => 'basic',
          'media_upload' => 0,
        ],
      ],
    ],
  ],
  'location' => [
    [
      [
        'param'    => 'block',
        'operator' => '==',
        'value'    => 'acf/tld-faq-accordion',
      ],
    ],
  ],
]);


// ── CTA Band Block ──
acf_add_local_field_group([
  'key'      => 'group_tld_block_cta',
  'title'    => 'CTA Band',
  'fields'   => [
    [
      'key'          => 'field_tld_cta_heading',
      'label'        => 'Heading',
      'name'         => 'cta_heading',
      'type'         => 'text',
      'default_value' => 'Ready to Get Started?',
    ],
    [
      'key'   => 'field_tld_cta_text',
      'label' => 'Text',
      'name'  => 'cta_text',
      'type'  => 'textarea',
      'rows'  => 2,
    ],
    [
      'key'          => 'field_tld_cta_button_text',
      'label'        => 'Button Text',
      'name'         => 'cta_button_text',
      'type'         => 'text',
      'default_value' => 'Book a Discovery Call',
    ],
    [
      'key'          => 'field_tld_cta_button_url',
      'label'        => 'Button URL',
      'name'         => 'cta_button_url',
      'type'         => 'url',
      'default_value' => '/contact/',
    ],
  ],
  'location' => [
    [
      [
        'param'    => 'block',
        'operator' => '==',
        'value'    => 'acf/tld-cta-band',
      ],
    ],
  ],
]);


// ── Process Steps Block ──
acf_add_local_field_group([
  'key'      => 'group_tld_block_process',
  'title'    => 'Process Steps',
  'fields'   => [
    [
      'key'          => 'field_tld_process_heading',
      'label'        => 'Section Heading',
      'name'         => 'process_heading',
      'type'         => 'text',
      'default_value' => 'How It Works',
    ],
    [
      'key'        => 'field_tld_process_steps',
      'label'      => 'Steps',
      'name'       => 'process_steps',
      'type'       => 'repeater',
      'layout'     => 'block',
      'button_label' => 'Add Step',
      'sub_fields' => [
        [
          'key'   => 'field_tld_step_title',
          'label' => 'Title',
          'name'  => 'title',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_step_description',
          'label' => 'Description',
          'name'  => 'description',
          'type'  => 'textarea',
          'rows'  => 2,
        ],
      ],
    ],
  ],
  'location' => [
    [
      [
        'param'    => 'block',
        'operator' => '==',
        'value'    => 'acf/tld-process-steps',
      ],
    ],
  ],
]);


// ── Stats Strip Block ──
acf_add_local_field_group([
  'key'      => 'group_tld_block_stats',
  'title'    => 'Stats Strip',
  'fields'   => [
    [
      'key'        => 'field_tld_stats_items',
      'label'      => 'Stats',
      'name'       => 'stats_items',
      'type'       => 'repeater',
      'layout'     => 'table',
      'button_label' => 'Add Stat',
      'max'        => 4,
      'sub_fields' => [
        [
          'key'   => 'field_tld_stat_number',
          'label' => 'Number',
          'name'  => 'number',
          'type'  => 'text',
          'instructions' => 'e.g. "50+", "98%", "3x"',
        ],
        [
          'key'   => 'field_tld_stat_label',
          'label' => 'Label',
          'name'  => 'label',
          'type'  => 'text',
        ],
      ],
    ],
  ],
  'location' => [
    [
      [
        'param'    => 'block',
        'operator' => '==',
        'value'    => 'acf/tld-stats-strip',
      ],
    ],
  ],
]);
