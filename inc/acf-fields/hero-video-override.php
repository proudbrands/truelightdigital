<?php
/**
 * ACF Field Group: Hero Video Override
 *
 * A single URL field that, when set, renders a looping WebM video behind
 * the hero (with the Featured Image as poster / fallback). Shows on:
 *  - Any page (post_type=page) — covers /home/, /formation/, /services/,
 *    each individual /services/<service>/ page, /welcome/, etc.
 *  - Any formation_piece — cornerstone heroes that use `.formation-hero`
 *  - Each pillar term — pillar archive pages use `.formation-hero` via
 *    taxonomy-pillar.php
 *
 * Reads resolution helper: `tld_get_hero_video_url()` in functions.php.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

if (!function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group([
    'key'    => 'group_tld_hero_video_override',
    'title'  => 'Hero Video Override',
    'fields' => [
        [
            'key'          => 'field_tld_hero_video_url',
            'label'        => 'Hero video URL (WebM)',
            'name'         => 'hero_video_url',
            'type'         => 'url',
            'instructions' => 'Optional. Full URL to a WebM video (e.g. https://truelight.digital/wp-content/uploads/2026/04/example.webm). When set, the hero renders a looping muted video behind the copy, with the Featured Image as the poster/fallback image. Leave blank to keep the static image.',
            'required'     => 0,
            'placeholder'  => 'https://truelight.digital/wp-content/uploads/YYYY/MM/example.webm',
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'page',
            ],
        ],
        [
            [
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'formation_piece',
            ],
        ],
        [
            [
                'param'    => 'taxonomy',
                'operator' => '==',
                'value'    => 'pillar',
            ],
        ],
    ],
    'menu_order'         => 0,
    'position'           => 'side',
    'style'              => 'default',
    'label_placement'    => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen'     => '',
    'active'             => true,
    'description'        => 'One URL per page/term. Points at a WebM uploaded to the Media Library (or /wp-content/uploads/). Featured Image is used as the video poster.',
    'show_in_rest'       => 0,
]);
