<?php
/**
 * ACF Field Group: Blog Post Fields
 *
 * Primary keyword, read time, linked service.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

acf_add_local_field_group([
  'key'      => 'group_tld_blog_post',
  'title'    => 'Blog Post Settings',
  'fields'   => [
    [
      'key'          => 'field_tld_blog_primary_keyword',
      'label'        => 'Primary Keyword',
      'name'         => 'primary_keyword',
      'type'         => 'text',
      'instructions' => 'Main SEO keyword for this post.',
    ],
    [
      'key'          => 'field_tld_blog_read_time',
      'label'        => 'Read Time (minutes)',
      'name'         => 'read_time',
      'type'         => 'number',
      'default_value' => 5,
      'min'          => 1,
      'max'          => 60,
    ],
    [
      'key'          => 'field_tld_blog_linked_service',
      'label'        => 'Related Service Page',
      'name'         => 'linked_service',
      'type'         => 'post_object',
      'post_type'    => ['page'],
      'return_format' => 'id',
      'instructions' => 'Link this post to a service page for cross-promotion.',
    ],
  ],
  'location' => [
    [
      [
        'param'    => 'post_type',
        'operator' => '==',
        'value'    => 'post',
      ],
    ],
  ],
  'position' => 'side',
]);
