<?php
/**
 * ACF Fields: Resource (tld_resource CPT)
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

acf_add_local_field_group([
  'key'      => 'group_tld_resource',
  'title'    => 'Resource Details',
  'location' => [
    [
      ['param' => 'post_type', 'operator' => '==', 'value' => 'tld_resource'],
    ],
  ],
  'position'   => 'normal',
  'style'      => 'default',
  'menu_order' => 0,
  'fields'     => [

    // ── Tab: Details ──
    [
      'key'   => 'field_res_tab_details',
      'label' => 'Details',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_res_file',
      'label'         => 'Downloadable File',
      'name'          => 'resource_file',
      'type'          => 'file',
      'return_format' => 'array',
      'mime_types'    => 'pdf, doc, docx, xls, xlsx, ppt, pptx, zip',
      'instructions'  => 'Upload the resource file. Accepted: PDF, Word, Excel, PowerPoint, ZIP.',
    ],
    [
      'key'           => 'field_res_type',
      'label'         => 'Resource Type',
      'name'          => 'resource_type',
      'type'          => 'select',
      'choices'       => [
        'pdf'          => 'PDF Document',
        'spreadsheet'  => 'Spreadsheet',
        'presentation' => 'Presentation',
        'document'     => 'Word Document',
        'template'     => 'Template Pack',
        'toolkit'      => 'Toolkit / Bundle',
        'checklist'    => 'Checklist',
        'guide'        => 'Guide',
      ],
      'default_value' => 'pdf',
      'instructions'  => 'Determines the icon and badge shown on the resource card.',
    ],
    [
      'key'          => 'field_res_description',
      'label'        => 'Short Description',
      'name'         => 'resource_description',
      'type'         => 'textarea',
      'rows'         => 3,
      'new_lines'    => 'br',
      'instructions' => 'Displayed on the resource card. Keep it to 1-2 sentences.',
    ],
    [
      'key'           => 'field_res_featured',
      'label'         => 'Featured Resource',
      'name'          => 'resource_featured',
      'type'          => 'true_false',
      'default_value' => 0,
      'ui'            => 1,
      'instructions'  => 'Featured resources appear at the top of the library.',
    ],

    // ── Tab: Display Options ──
    [
      'key'   => 'field_res_tab_display',
      'label' => 'Display Options',
      'type'  => 'tab',
    ],
    [
      'key'           => 'field_res_preview_image',
      'label'         => 'Preview Image',
      'name'          => 'resource_preview_image',
      'type'          => 'image',
      'return_format' => 'url',
      'preview_size'  => 'medium',
      'instructions'  => 'Optional. A preview/mockup image of the resource. Falls back to featured image.',
    ],
    [
      'key'           => 'field_res_gated',
      'label'         => 'Require Email to Download',
      'name'          => 'resource_gated',
      'type'          => 'true_false',
      'default_value' => 0,
      'ui'            => 1,
      'instructions'  => 'If enabled, visitors must enter their email before downloading.',
    ],
    [
      'key'               => 'field_res_gate_form_id',
      'label'             => 'Gate Form ID',
      'name'              => 'resource_gate_form_id',
      'type'              => 'number',
      'instructions'      => 'Gravity Forms form ID for the email gate.',
      'conditional_logic' => [
        [['field' => 'field_res_gated', 'operator' => '==', 'value' => '1']],
      ],
    ],
  ],
]);
