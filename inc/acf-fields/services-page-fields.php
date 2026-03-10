<?php
/**
 * ACF Field Group: Services Landing Page
 *
 * 6 tabs: Hero, Intro, Services Grid, Who We Serve, Approach, Stats & CTA.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

acf_add_local_field_group([
  'key'      => 'group_tld_services_landing',
  'title'    => 'Services Landing Page',
  'fields'   => [

    // ════════════════════════════════════════
    // Tab 1: Hero
    // ════════════════════════════════════════
    [
      'key'   => 'field_tld_svclp_tab_hero',
      'label' => 'Hero',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_tld_svclp_hero_eyebrow',
      'label'         => 'Hero Eyebrow',
      'name'          => 'services_hero_eyebrow',
      'type'          => 'text',
      'default_value' => 'Our Services',
      'instructions'  => 'Small uppercase text above the title.',
    ],
    [
      'key'          => 'field_tld_svclp_hero_subtitle',
      'label'        => 'Hero Subtitle',
      'name'         => 'services_hero_subtitle',
      'type'         => 'textarea',
      'rows'         => 2,
      'instructions' => 'Short description shown below the page title.',
    ],
    [
      'key'          => 'field_tld_svclp_hero_cta_text',
      'label'        => 'Primary CTA Text',
      'name'         => 'services_hero_cta_text',
      'type'         => 'text',
      'instructions' => 'Gold button (opens discovery modal). Leave blank to hide.',
    ],
    [
      'key'          => 'field_tld_svclp_hero_cta2_text',
      'label'        => 'Secondary CTA Text',
      'name'         => 'services_hero_cta2_text',
      'type'         => 'text',
      'instructions' => 'Outline button. Defaults to scroll to services grid.',
    ],
    [
      'key'  => 'field_tld_svclp_hero_cta2_url',
      'label' => 'Secondary CTA URL',
      'name'  => 'services_hero_cta2_url',
      'type'  => 'url',
    ],

    // ════════════════════════════════════════
    // Tab 2: Intro Statement
    // ════════════════════════════════════════
    [
      'key'   => 'field_tld_svclp_tab_intro',
      'label' => 'Intro Statement',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_svclp_intro_statement',
      'label'        => 'Statement Heading',
      'name'         => 'services_intro_statement',
      'type'         => 'textarea',
      'rows'         => 2,
      'instructions' => 'Bold centered statement. Use &lt;span class="text-gold"&gt; for gold accent. HTML allowed.',
    ],
    [
      'key'          => 'field_tld_svclp_intro_text',
      'label'        => 'Supporting Text',
      'name'         => 'services_intro_text',
      'type'         => 'textarea',
      'rows'         => 3,
      'instructions' => 'Paragraph below the statement heading.',
    ],

    // ════════════════════════════════════════
    // Tab 3: Services Grid
    // ════════════════════════════════════════
    [
      'key'   => 'field_tld_svclp_tab_services',
      'label' => 'Services Grid',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_tld_svclp_grid_eyebrow',
      'label'         => 'Eyebrow',
      'name'          => 'services_grid_eyebrow',
      'type'          => 'text',
      'default_value' => 'What We Offer',
    ],
    [
      'key'          => 'field_tld_svclp_grid_heading',
      'label'        => 'Heading',
      'name'         => 'services_grid_heading',
      'type'         => 'text',
      'instructions' => 'Override heading for the services grid. Service cards are auto-generated from child pages.',
    ],

    // ════════════════════════════════════════
    // Tab 4: Who We Serve
    // ════════════════════════════════════════
    [
      'key'   => 'field_tld_svclp_tab_serve',
      'label' => 'Who We Serve',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_tld_svclp_serve_eyebrow',
      'label'         => 'Eyebrow',
      'name'          => 'services_serve_eyebrow',
      'type'          => 'text',
      'default_value' => 'Who We Serve',
    ],
    [
      'key'  => 'field_tld_svclp_serve_heading',
      'label' => 'Heading',
      'name'  => 'services_serve_heading',
      'type'  => 'text',
    ],
    [
      'key'          => 'field_tld_svclp_serve_text',
      'label'        => 'Supporting Text',
      'name'         => 'services_serve_text',
      'type'         => 'textarea',
      'rows'         => 2,
      'instructions' => 'Short paragraph below the heading.',
    ],
    [
      'key'          => 'field_tld_svclp_audiences',
      'label'        => 'Audiences',
      'name'         => 'services_audiences',
      'type'         => 'repeater',
      'layout'       => 'block',
      'min'          => 0,
      'max'          => 4,
      'button_label' => 'Add Audience',
      'sub_fields'   => [
        [
          'key'     => 'field_tld_svclp_aud_title',
          'label'   => 'Title',
          'name'    => 'title',
          'type'    => 'text',
          'wrapper' => ['width' => '40'],
        ],
        [
          'key'     => 'field_tld_svclp_aud_desc',
          'label'   => 'Description',
          'name'    => 'description',
          'type'    => 'textarea',
          'rows'    => 2,
          'wrapper' => ['width' => '60'],
        ],
        [
          'key'          => 'field_tld_svclp_aud_link',
          'label'        => 'Link URL',
          'name'         => 'link',
          'type'         => 'url',
          'instructions' => 'Link to the audience hub page.',
        ],
        [
          'key'          => 'field_tld_svclp_aud_icon',
          'label'        => 'Icon SVG',
          'name'         => 'icon',
          'type'         => 'textarea',
          'rows'         => 2,
          'instructions' => 'Paste inline SVG for the icon (optional). 24x24 recommended.',
        ],
      ],
    ],

    // ════════════════════════════════════════
    // Tab 5: Approach
    // ════════════════════════════════════════
    [
      'key'   => 'field_tld_svclp_tab_approach',
      'label' => 'Approach',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_tld_svclp_approach_eyebrow',
      'label'         => 'Eyebrow',
      'name'          => 'services_approach_eyebrow',
      'type'          => 'text',
      'default_value' => 'How We Work',
    ],
    [
      'key'  => 'field_tld_svclp_approach_heading',
      'label' => 'Heading',
      'name'  => 'services_approach_heading',
      'type'  => 'text',
    ],
    [
      'key'          => 'field_tld_svclp_approach_text',
      'label'        => 'Body Text',
      'name'         => 'services_approach_text',
      'type'         => 'textarea',
      'rows'         => 4,
      'instructions' => 'Paragraph explaining your approach.',
    ],
    [
      'key'          => 'field_tld_svclp_approach_points',
      'label'        => 'Steps',
      'name'         => 'services_approach_points',
      'type'         => 'repeater',
      'layout'       => 'block',
      'min'          => 0,
      'max'          => 6,
      'button_label' => 'Add Step',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_svclp_step_title',
          'label' => 'Title',
          'name'  => 'title',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_svclp_step_desc',
          'label' => 'Description',
          'name'  => 'description',
          'type'  => 'textarea',
          'rows'  => 3,
        ],
      ],
    ],

    // ════════════════════════════════════════
    // Tab 6: Stats & CTA
    // ════════════════════════════════════════
    [
      'key'   => 'field_tld_svclp_tab_stats',
      'label' => 'Stats & CTA',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_svclp_stats_heading',
      'label'        => 'Stats Heading',
      'name'         => 'services_stats_heading',
      'type'         => 'text',
      'instructions' => 'Heading above the stats strip.',
    ],
    [
      'key'           => 'field_tld_svclp_stats_bg',
      'label'         => 'Stats Background Image',
      'name'          => 'services_stats_bg_image',
      'type'          => 'image',
      'return_format' => 'url',
      'preview_size'  => 'medium',
    ],
    [
      'key'          => 'field_tld_svclp_stats',
      'label'        => 'Stats',
      'name'         => 'services_stat_items',
      'type'         => 'repeater',
      'layout'       => 'table',
      'min'          => 0,
      'max'          => 4,
      'button_label' => 'Add Stat',
      'sub_fields'   => [
        [
          'key'     => 'field_tld_svclp_stat_number',
          'label'   => 'Number',
          'name'    => 'number',
          'type'    => 'text',
          'instructions' => 'e.g. "92%", "3x", "50+"',
          'wrapper' => ['width' => '20'],
        ],
        [
          'key'     => 'field_tld_svclp_stat_label',
          'label'   => 'Label',
          'name'    => 'label',
          'type'    => 'text',
          'wrapper' => ['width' => '30'],
        ],
        [
          'key'     => 'field_tld_svclp_stat_desc',
          'label'   => 'Description',
          'name'    => 'description',
          'type'    => 'text',
          'wrapper' => ['width' => '50'],
        ],
      ],
    ],
    [
      'key'          => 'field_tld_svclp_cta_heading',
      'label'        => 'CTA Heading Override',
      'name'         => 'cta_heading',
      'type'         => 'text',
      'instructions' => 'Leave blank to use the site default.',
    ],
    [
      'key'  => 'field_tld_svclp_cta_text',
      'label' => 'CTA Text Override',
      'name'  => 'cta_text',
      'type'  => 'textarea',
      'rows'  => 2,
    ],
    [
      'key'  => 'field_tld_svclp_cta_btn_text',
      'label' => 'CTA Button Text Override',
      'name'  => 'cta_button_text',
      'type'  => 'text',
    ],
  ],
  'location' => [
    [
      [
        'param'    => 'page_template',
        'operator' => '==',
        'value'    => 'page-templates/page-services.php',
      ],
    ],
  ],
  'position' => 'normal',
  'style'    => 'default',
]);
