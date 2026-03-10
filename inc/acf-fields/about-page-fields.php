<?php
/**
 * ACF Field Group: About Page Fields
 *
 * Hero, mission statement, story, values, approach, credentials, team, CTA overrides.
 * Structured sections render as designed landing page blocks in the template.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

acf_add_local_field_group([
  'key'      => 'group_tld_about_page',
  'title'    => 'About Page Settings',
  'fields'   => [

    // ── Hero ──
    [
      'key'   => 'field_tld_about_tab_hero',
      'label' => 'Hero',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_about_hero_subtitle',
      'label'        => 'Hero Subtitle',
      'name'         => 'hero_subtitle',
      'type'         => 'textarea',
      'rows'         => 2,
      'instructions' => 'Short description shown below the page title in the hero.',
    ],
    [
      'key'           => 'field_tld_about_hero_eyebrow',
      'label'         => 'Hero Eyebrow',
      'name'          => 'hero_eyebrow',
      'type'          => 'text',
      'default_value' => 'About Us',
      'instructions'  => 'Small uppercase text above the title.',
    ],

    // ── Mission ──
    [
      'key'   => 'field_tld_about_tab_mission',
      'label' => 'Mission',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_about_mission_statement',
      'label'        => 'Mission Statement',
      'name'         => 'about_mission_statement',
      'type'         => 'textarea',
      'rows'         => 2,
      'instructions' => 'Bold centred statement. Use &lt;span class="text-gold"&gt; for gold accent. HTML allowed.',
    ],
    [
      'key'          => 'field_tld_about_mission_text',
      'label'        => 'Supporting Text',
      'name'         => 'about_mission_text',
      'type'         => 'textarea',
      'rows'         => 3,
      'instructions' => 'Paragraph below the mission statement.',
    ],

    // ── Our Story ──
    [
      'key'   => 'field_tld_about_tab_story',
      'label' => 'Our Story',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_tld_about_story_eyebrow',
      'label'         => 'Story Eyebrow',
      'name'          => 'about_story_eyebrow',
      'type'          => 'text',
      'default_value' => 'Our Story',
    ],
    [
      'key'          => 'field_tld_about_story_heading',
      'label'        => 'Story Heading',
      'name'         => 'about_story_heading',
      'type'         => 'textarea',
      'rows'         => 2,
      'instructions' => 'Large left-column heading. Line breaks preserved.',
    ],
    [
      'key'          => 'field_tld_about_story_text',
      'label'        => 'Story Body',
      'name'         => 'about_story_text',
      'type'         => 'wysiwyg',
      'toolbar'      => 'basic',
      'media_upload' => 0,
      'instructions' => 'The narrative body text. 2-3 paragraphs recommended.',
    ],
    [
      'key'           => 'field_tld_about_story_image',
      'label'         => 'Story Image',
      'name'          => 'about_story_image',
      'type'          => 'image',
      'return_format' => 'url',
      'preview_size'  => 'medium',
      'instructions'  => 'Optional photo to accompany the story (shown below the text on the right column).',
    ],

    // ── Values ──
    [
      'key'   => 'field_tld_about_tab_values',
      'label' => 'Values',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_tld_about_values_eyebrow',
      'label'         => 'Values Eyebrow',
      'name'          => 'about_values_eyebrow',
      'type'          => 'text',
      'default_value' => 'What We Believe',
    ],
    [
      'key'           => 'field_tld_about_values_heading',
      'label'         => 'Values Heading',
      'name'          => 'about_values_heading',
      'type'          => 'text',
      'default_value' => 'Convictions that shape our work',
    ],
    [
      'key'           => 'field_tld_about_values_bg_image',
      'label'         => 'Values Background Image',
      'name'          => 'about_values_bg_image',
      'type'          => 'image',
      'return_format' => 'url',
      'preview_size'  => 'medium',
      'instructions'  => 'Optional background image for the dark values section.',
    ],
    [
      'key'          => 'field_tld_about_values',
      'label'        => 'Values',
      'name'         => 'about_values',
      'type'         => 'repeater',
      'layout'       => 'block',
      'min'          => 0,
      'max'          => 6,
      'button_label' => 'Add Value',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_about_value_title',
          'label' => 'Title',
          'name'  => 'title',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_about_value_desc',
          'label' => 'Description',
          'name'  => 'description',
          'type'  => 'textarea',
          'rows'  => 3,
        ],
      ],
    ],

    // ── Approach ──
    [
      'key'   => 'field_tld_about_tab_approach',
      'label' => 'Approach',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_tld_about_approach_eyebrow',
      'label'         => 'Approach Eyebrow',
      'name'          => 'about_approach_eyebrow',
      'type'          => 'text',
      'default_value' => 'Our Approach',
    ],
    [
      'key'           => 'field_tld_about_approach_heading',
      'label'         => 'Approach Heading',
      'name'          => 'about_approach_heading',
      'type'          => 'text',
      'default_value' => 'How we think about the work',
    ],
    [
      'key'          => 'field_tld_about_approach_items',
      'label'        => 'Approach Pillars',
      'name'         => 'about_approach_items',
      'type'         => 'repeater',
      'layout'       => 'block',
      'min'          => 0,
      'max'          => 4,
      'button_label' => 'Add Pillar',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_about_approach_title',
          'label' => 'Title',
          'name'  => 'title',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_about_approach_desc',
          'label' => 'Description',
          'name'  => 'description',
          'type'  => 'textarea',
          'rows'  => 3,
        ],
      ],
    ],

    // ── Credentials ──
    [
      'key'   => 'field_tld_about_tab_credentials',
      'label' => 'Credentials',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_tld_about_stats_heading',
      'label'         => 'Stats Heading',
      'name'          => 'about_stats_heading',
      'type'          => 'text',
      'default_value' => 'By the numbers',
    ],
    [
      'key'           => 'field_tld_about_stats_bg_image',
      'label'         => 'Stats Background Image',
      'name'          => 'about_stats_bg_image',
      'type'          => 'image',
      'return_format' => 'url',
      'preview_size'  => 'medium',
      'instructions'  => 'Optional background image for the dark stats section.',
    ],
    [
      'key'          => 'field_tld_about_stats',
      'label'        => 'Stats',
      'name'         => 'about_stats',
      'type'         => 'repeater',
      'layout'       => 'table',
      'min'          => 0,
      'max'          => 4,
      'button_label' => 'Add Stat',
      'sub_fields'   => [
        [
          'key'     => 'field_tld_about_stat_number',
          'label'   => 'Number / Stat',
          'name'    => 'number',
          'type'    => 'text',
          'instructions' => 'e.g. "14+", "100%", "3×"',
          'wrapper' => ['width' => '20'],
        ],
        [
          'key'     => 'field_tld_about_stat_label',
          'label'   => 'Label',
          'name'    => 'label',
          'type'    => 'text',
          'wrapper' => ['width' => '30'],
        ],
        [
          'key'     => 'field_tld_about_stat_desc',
          'label'   => 'Description',
          'name'    => 'description',
          'type'    => 'text',
          'wrapper' => ['width' => '50'],
        ],
      ],
    ],
    [
      'key'           => 'field_tld_about_legal_name',
      'label'         => 'Legal Company Name',
      'name'          => 'about_company_legal_name',
      'type'          => 'text',
      'default_value' => 'Proud Brands Limited',
      'instructions'  => 'Registered company name.',
    ],
    [
      'key'           => 'field_tld_about_trading_as',
      'label'         => 'Trading As',
      'name'          => 'about_company_trading_as',
      'type'          => 'text',
      'default_value' => 'True Light Digital',
    ],
    [
      'key'          => 'field_tld_about_company_number',
      'label'        => 'Company Number',
      'name'         => 'about_company_number',
      'type'         => 'text',
      'instructions' => 'UK Companies House number (optional).',
    ],
    [
      'key'           => 'field_tld_about_location',
      'label'         => 'Location',
      'name'          => 'about_company_location',
      'type'          => 'text',
      'default_value' => 'United Kingdom',
    ],
    [
      'key'           => 'field_tld_about_market',
      'label'         => 'Market',
      'name'          => 'about_company_market',
      'type'          => 'text',
      'default_value' => 'Serving churches, ministries, and Christian organisations worldwide',
    ],
    [
      'key'          => 'field_tld_about_company_detail',
      'label'        => 'Additional Company Detail',
      'name'         => 'about_company_detail',
      'type'         => 'textarea',
      'rows'         => 3,
      'instructions' => 'Free-form additional detail about the company.',
    ],

    // ── Team ──
    [
      'key'   => 'field_tld_about_tab_team',
      'label' => 'Team',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_tld_about_team_eyebrow',
      'label'         => 'Team Eyebrow',
      'name'          => 'about_team_eyebrow',
      'type'          => 'text',
      'default_value' => 'The Team',
    ],
    [
      'key'           => 'field_tld_about_team_heading',
      'label'         => 'Team Heading',
      'name'          => 'about_team_heading',
      'type'          => 'text',
      'default_value' => 'The people behind the work',
    ],
    [
      'key'          => 'field_tld_about_team_text',
      'label'        => 'Team Intro',
      'name'         => 'about_team_text',
      'type'         => 'textarea',
      'rows'         => 2,
      'instructions' => 'Optional intro paragraph above team cards.',
    ],
    [
      'key'          => 'field_tld_about_team',
      'label'        => 'Team Members',
      'name'         => 'about_team',
      'type'         => 'repeater',
      'layout'       => 'block',
      'min'          => 0,
      'max'          => 8,
      'button_label' => 'Add Team Member',
      'sub_fields'   => [
        [
          'key'   => 'field_tld_about_team_name',
          'label' => 'Name',
          'name'  => 'name',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_tld_about_team_role',
          'label' => 'Role',
          'name'  => 'role',
          'type'  => 'text',
        ],
        [
          'key'           => 'field_tld_about_team_image',
          'label'         => 'Photo',
          'name'          => 'image',
          'type'          => 'image',
          'return_format' => 'url',
          'preview_size'  => 'thumbnail',
        ],
        [
          'key'   => 'field_tld_about_team_bio',
          'label' => 'Bio',
          'name'  => 'bio',
          'type'  => 'textarea',
          'rows'  => 3,
        ],
      ],
    ],

    // ── CTA Overrides ──
    [
      'key'   => 'field_tld_about_tab_cta',
      'label' => 'CTA Overrides',
      'type'  => 'tab',
    ],
    [
      'key'          => 'field_tld_about_cta_heading',
      'label'        => 'CTA Heading',
      'name'         => 'cta_heading',
      'type'         => 'text',
      'instructions' => 'Leave blank to use site default.',
    ],
    [
      'key'   => 'field_tld_about_cta_text',
      'label' => 'CTA Text',
      'name'  => 'cta_text',
      'type'  => 'textarea',
      'rows'  => 2,
    ],
    [
      'key'   => 'field_tld_about_cta_button_text',
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
        'value'    => 'page-templates/page-about.php',
      ],
    ],
  ],
  'position' => 'normal',
  'style'    => 'default',
]);
