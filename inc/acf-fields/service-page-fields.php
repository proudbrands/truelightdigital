<?php
/**
 * ACF Field Group: Service Page Fields
 *
 * Hero, intro statement, service pillars, process steps, results, FAQ, CTA overrides, related.
 * Structured sections render as designed landing page blocks in the template.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

acf_add_local_field_group([
  'key'      => 'group_tld_service_page',
  'title'    => 'Service Page Settings',
  'fields'   => [

    // ── Hero ──
    [
      'key'   => 'field_tld_svc_tab_hero',
      'label' => 'Hero',
      'type'  => 'tab',
    ],
    [
      'key'   => 'field_tld_svc_hero_subtitle',
      'label' => 'Hero Subtitle',
      'name'  => 'hero_subtitle',
      'type'  => 'textarea',
      'rows'  => 2,
      'instructions' => 'Short description shown below the page title in the hero section.',
    ],
    [
      'key'   => 'field_tld_svc_hero_eyebrow',
      'label' => 'Hero Eyebrow',
      'name'  => 'hero_eyebrow',
      'type'  => 'text',
      'instructions' => 'Small uppercase text above the title (e.g. "Our Services").',
      'default_value' => 'Our Services',
    ],
    [
      'key'          => 'field_tld_svc_hero_cta1_text',
      'label'        => 'Primary CTA Text',
      'name'         => 'hero_cta_primary_text',
      'type'         => 'text',
      'instructions' => 'Gold button in hero (e.g. "Get a Free SEO Audit").',
    ],
    [
      'key'          => 'field_tld_svc_hero_cta1_url',
      'label'        => 'Primary CTA URL',
      'name'         => 'hero_cta_primary_url',
      'type'         => 'url',
    ],
    [
      'key'          => 'field_tld_svc_hero_cta2_text',
      'label'        => 'Secondary CTA Text',
      'name'         => 'hero_cta_secondary_text',
      'type'         => 'text',
      'instructions' => 'Outline button in hero (e.g. "See How We Work").',
    ],
    [
      'key'          => 'field_tld_svc_hero_cta2_url',
      'label'        => 'Secondary CTA URL',
      'name'         => 'hero_cta_secondary_url',
      'type'         => 'url',
    ],

    // ── Intro Statement ──
    [
      'key'   => 'field_tld_svc_tab_intro',
      'label' => 'Intro Statement',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_svc_intro_statement',
      'label'        => 'Statement Heading',
      'name'         => 'intro_statement',
      'type'         => 'textarea',
      'rows'         => 2,
      'instructions' => 'Bold centered statement. Use &lt;span class="text-gold"&gt; for gold accent. HTML allowed.',
    ],
    [
      'key'          => 'field_tld_svc_intro_text',
      'label'        => 'Supporting Text',
      'name'         => 'intro_text',
      'type'         => 'textarea',
      'rows'         => 3,
      'instructions' => 'Paragraph below the statement heading.',
    ],

    // ── Service Pillars ──
    [
      'key'   => 'field_tld_svc_tab_pillars',
      'label' => 'Service Pillars',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_svc_pillars_heading',
      'label'        => 'Pillars Section Heading',
      'name'         => 'pillars_heading',
      'type'         => 'text',
      'instructions' => 'Override heading for this section.',
    ],
    [
      'key'          => 'field_tld_svc_pillars',
      'label'        => 'Pillars',
      'name'         => 'service_pillars',
      'type'         => 'repeater',
      'layout'       => 'block',
      'min'          => 0,
      'max'          => 8,
      'button_label' => 'Add Pillar',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_svc_pillar_title',
          'label' => 'Title',
          'name'  => 'title',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_svc_pillar_desc',
          'label' => 'Description',
          'name'  => 'description',
          'type'  => 'textarea',
          'rows'  => 3,
        ],
      ],
    ],

    // ── Visual Content Sections ──
    [
      'key'   => 'field_tld_svc_tab_visual',
      'label' => 'Visual Content',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_svc_stats_bg_image',
      'label'        => 'Stats Section Background Image',
      'name'         => 'stats_bg_image',
      'type'         => 'image',
      'return_format' => 'url',
      'preview_size'  => 'medium',
      'instructions' => 'Optional background image for the dark stats strip. Displayed at center/center, 100% width, with dark gradient overlay.',
    ],
    [
      'key'          => 'field_tld_svc_stats_heading',
      'label'        => 'Stats Section Heading',
      'name'         => 'stats_heading',
      'type'         => 'text',
      'instructions' => 'Heading above the stats strip (e.g. "The Reality").',
    ],
    [
      'key'          => 'field_tld_svc_stats',
      'label'        => 'Stats',
      'name'         => 'stat_items',
      'type'         => 'repeater',
      'layout'       => 'table',
      'min'          => 0,
      'max'          => 4,
      'button_label' => 'Add Stat',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_svc_stat_number',
          'label' => 'Number / Stat',
          'name'  => 'number',
          'type'  => 'text',
          'instructions' => 'e.g. "92%", "3×", "46%"',
          'wrapper' => ['width' => '20'],
        ],
        [
          'key'   => 'field_tld_svc_stat_label',
          'label' => 'Label',
          'name'  => 'label',
          'type'  => 'text',
          'instructions' => 'Short label below the number.',
          'wrapper' => ['width' => '30'],
        ],
        [
          'key'   => 'field_tld_svc_stat_desc',
          'label' => 'Description',
          'name'  => 'description',
          'type'  => 'text',
          'instructions' => 'Supporting detail.',
          'wrapper' => ['width' => '50'],
        ],
      ],
    ],
    [
      'key'          => 'field_tld_svc_problems_heading',
      'label'        => 'Problem Points Heading',
      'name'         => 'problems_heading',
      'type'         => 'text',
      'instructions' => 'Heading above the problems grid.',
    ],
    [
      'key'          => 'field_tld_svc_problems_text',
      'label'        => 'Problem Points Intro',
      'name'         => 'problems_intro',
      'type'         => 'textarea',
      'rows'         => 2,
      'instructions' => 'Short intro text below the heading.',
    ],
    [
      'key'          => 'field_tld_svc_problems',
      'label'        => 'Problem Points',
      'name'         => 'problem_items',
      'type'         => 'repeater',
      'layout'       => 'table',
      'min'          => 0,
      'max'          => 8,
      'button_label' => 'Add Problem',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_svc_problem_text',
          'label' => 'Problem Statement',
          'name'  => 'text',
          'type'  => 'text',
        ],
      ],
    ],
    [
      'key'          => 'field_tld_svc_audience_heading',
      'label'        => 'Audience Section Heading',
      'name'         => 'audience_heading',
      'type'         => 'text',
      'instructions' => 'Heading for "Who this is for".',
    ],
    [
      'key'          => 'field_tld_svc_audience',
      'label'        => 'Audience Cards',
      'name'         => 'audience_items',
      'type'         => 'repeater',
      'layout'       => 'block',
      'min'          => 0,
      'max'          => 6,
      'button_label' => 'Add Audience',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_svc_audience_title',
          'label' => 'Title',
          'name'  => 'title',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_svc_audience_desc',
          'label' => 'Description',
          'name'  => 'description',
          'type'  => 'textarea',
          'rows'  => 2,
        ],
      ],
    ],
    [
      'key'          => 'field_tld_svc_whyus_bg_image',
      'label'        => 'Why Us Background Image',
      'name'         => 'whyus_bg_image',
      'type'         => 'image',
      'return_format' => 'url',
      'preview_size'  => 'medium',
      'instructions' => 'Optional background image for the dark "Why Us" section.',
    ],
    [
      'key'          => 'field_tld_svc_whyus_heading',
      'label'        => 'Why Us Heading',
      'name'         => 'whyus_heading',
      'type'         => 'text',
      'instructions' => 'Heading for differentiators section.',
    ],
    [
      'key'          => 'field_tld_svc_whyus',
      'label'        => 'Differentiators',
      'name'         => 'whyus_items',
      'type'         => 'repeater',
      'layout'       => 'block',
      'min'          => 0,
      'max'          => 4,
      'button_label' => 'Add Differentiator',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_svc_whyus_title',
          'label' => 'Title',
          'name'  => 'title',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_svc_whyus_desc',
          'label' => 'Description',
          'name'  => 'description',
          'type'  => 'textarea',
          'rows'  => 3,
        ],
      ],
    ],

    // ── Process Steps ──
    [
      'key'   => 'field_tld_svc_tab_process',
      'label' => 'Process Steps',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_svc_process_bg_image',
      'label'        => 'Process Section Background Image',
      'name'         => 'process_bg_image',
      'type'         => 'image',
      'return_format' => 'url',
      'preview_size'  => 'medium',
      'instructions' => 'Optional background image for the dark process steps section.',
    ],
    [
      'key'          => 'field_tld_svc_process_heading',
      'label'        => 'Process Section Heading',
      'name'         => 'process_heading',
      'type'         => 'text',
      'instructions' => 'Override heading for this section.',
    ],
    [
      'key'          => 'field_tld_svc_steps',
      'label'        => 'Steps',
      'name'         => 'process_steps',
      'type'         => 'repeater',
      'layout'       => 'block',
      'min'          => 0,
      'max'          => 6,
      'button_label' => 'Add Step',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_svc_step_title',
          'label' => 'Title',
          'name'  => 'title',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_svc_step_desc',
          'label' => 'Description',
          'name'  => 'description',
          'type'  => 'textarea',
          'rows'  => 3,
        ],
      ],
    ],

    // ── Results / Impact ──
    [
      'key'   => 'field_tld_svc_tab_results',
      'label' => 'Results',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_svc_results_heading',
      'label'        => 'Results Section Heading',
      'name'         => 'results_heading',
      'type'         => 'text',
      'instructions' => 'Override heading for this section.',
    ],
    [
      'key'          => 'field_tld_svc_results',
      'label'        => 'Result Items',
      'name'         => 'results_items',
      'type'         => 'repeater',
      'layout'       => 'block',
      'min'          => 0,
      'max'          => 8,
      'button_label' => 'Add Result',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_svc_result_title',
          'label' => 'Title',
          'name'  => 'title',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_svc_result_desc',
          'label' => 'Description',
          'name'  => 'description',
          'type'  => 'textarea',
          'rows'  => 2,
        ],
      ],
    ],

    // ── FAQ ──
    [
      'key'   => 'field_tld_svc_tab_faq',
      'label' => 'FAQ',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_svc_faqs',
      'label'        => 'FAQ Items',
      'name'         => 'faq_items',
      'type'         => 'repeater',
      'layout'       => 'block',
      'min'          => 0,
      'max'          => 12,
      'button_label' => 'Add FAQ',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_svc_faq_q',
          'label' => 'Question',
          'name'  => 'question',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_svc_faq_a',
          'label' => 'Answer',
          'name'  => 'answer',
          'type'  => 'textarea',
          'rows'  => 4,
        ],
      ],
    ],

    // ── CTA Overrides ──
    [
      'key'   => 'field_tld_svc_tab_cta',
      'label' => 'CTA Overrides',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_svc_cta_heading',
      'label'        => 'CTA Heading',
      'name'         => 'cta_heading',
      'type'         => 'text',
      'instructions' => 'Leave blank to use the site default.',
    ],
    [
      'key'          => 'field_tld_svc_cta_text',
      'label'        => 'CTA Text',
      'name'         => 'cta_text',
      'type'         => 'textarea',
      'rows'         => 2,
    ],
    [
      'key'          => 'field_tld_svc_cta_button_text',
      'label'        => 'CTA Button Text',
      'name'         => 'cta_button_text',
      'type'         => 'text',
    ],

    // ── Related ──
    [
      'key'   => 'field_tld_svc_tab_related',
      'label' => 'Related Content',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_svc_related_services',
      'label'        => 'Related Services',
      'name'         => 'related_services',
      'type'         => 'relationship',
      'post_type'    => ['page'],
      'filters'      => ['search'],
      'max'          => 3,
      'return_format' => 'id',
      'instructions' => 'Select up to 3 related service pages.',
    ],
  ],
  'location' => [
    [
      [
        'param'    => 'page_template',
        'operator' => '==',
        'value'    => 'page-templates/page-service.php',
      ],
    ],
  ],
  'position' => 'normal',
  'style'    => 'default',
]);
