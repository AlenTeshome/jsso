<?php

use Drupal\views\Entity\View;

function update_view_config($id, $label, $config_patch)
{
    $view = View::load($id);
    if (!$view) {
        echo "Creating View: $id\n";
        $view = View::create([
            'id' => $id,
            'label' => $label,
            'base_table' => 'node_field_data',
            'status' => TRUE,
        ]);
    }

    $display = $view->get('display');
    foreach ($config_patch as $display_id => $options) {
        if (!isset($display[$display_id])) {
            $display[$display_id] = [
                'id' => $display_id,
                'display_plugin' => $display_id === 'default' ? 'default' : (strpos($display_id, 'page') !== FALSE ? 'page' : 'block'),
                'display_title' => ucfirst(str_replace('_', ' ', $display_id)),
                'display_options' => [],
            ];
        }
        $display[$display_id]['display_options'] = array_replace_recursive($display[$display_id]['display_options'] ?? [], $options);
    }

    $view->set('display', $display);
    try {
        $view->save();
        echo "Successfully saved/updated View: $id\n";
    } catch (\Exception $e) {
        echo "Error saving View $id: " . $e->getMessage() . "\n";
    }
}

$default_filters = function ($bundle) {
    return [
        'status' => [
            'id' => 'status',
            'table' => 'node_field_data',
            'field' => 'status',
            'value' => '1',
            'group' => 1,
            'plugin_id' => 'boolean',
            'entity_type' => 'node',
            'entity_field' => 'status',
        ],
        'type' => [
            'id' => 'type',
            'table' => 'node_field_data',
            'field' => 'type',
            'value' => [$bundle => $bundle],
            'group' => 1,
            'plugin_id' => 'bundle',
            'entity_type' => 'node',
            'entity_field' => 'type',
        ],
    ];
};

/**
 * Helper to create taxonomy filter config.
 */
function tax_filter($id, $field, $vid, $label)
{
    return [
        'id' => $id,
        'table' => 'node__' . $field,
        'field' => $field . '_target_id',
        'plugin_id' => 'taxonomy_index_tid',
        'vid' => $vid,
        'exposed' => TRUE,
        'expose' => [
            'label' => $label,
            'identifier' => $id,
            'multiple' => FALSE,
        ],
        'hierarchy' => FALSE,
        'type' => 'select',
    ];
}

// 5. Knowledge Hub
update_view_config('jsso_knowledge_hub', 'Knowledge Hub', [
    'default' => [
        'fields' => [
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'plugin_id' => 'field'],
            'field_resource_type' => ['id' => 'field_resource_type', 'table' => 'node__field_resource_type', 'field' => 'field_resource_type', 'plugin_id' => 'field', 'type' => 'entity_reference_label'],
            'created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'plugin_id' => 'field', 'type' => 'timestamp', 'settings' => ['date_format' => 'html_date']],
            'field_media_document' => ['id' => 'field_media_document', 'table' => 'node__field_media_document', 'field' => 'field_media_document', 'plugin_id' => 'field', 'type' => 'entity_reference_label', 'settings' => ['link' => TRUE]],
        ],
        'filters' => array_merge($default_filters('resource'), [
            'res_type' => tax_filter('res_type', 'field_resource_type', 'resource_types', 'Resource Type'),
            'theme' => tax_filter('theme', 'field_theme', 'thematic_areas', 'Thematic Area'),
            'framework' => tax_filter('framework', 'field_frameworks', 'strategic_frameworks', 'Strategic Framework'),
        ]),
        'sorts' => [
            'created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'order' => 'DESC', 'plugin_id' => 'standard'],
        ],
        'pager' => ['type' => 'mini', 'options' => ['items_per_page' => 12]],
    ],
    'page_1' => ['path' => 'knowledge-hub'],
    'block_1' => ['display_title' => 'Recent Publications', 'display_options' => ['pager' => ['type' => 'some', 'options' => ['items_per_page' => 3]]]],
]);

// 6. Photo Albums
update_view_config('jsso_albums', 'Photo Albums', [
    'default' => [
        'style' => ['type' => 'grid', 'options' => ['columns' => 3]],
        'fields' => [
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'plugin_id' => 'field'],
            'field_gallery_images' => ['id' => 'field_gallery_images', 'table' => 'node__field_gallery_images', 'field' => 'field_gallery_images', 'plugin_id' => 'field', 'type' => 'media_thumbnail', 'settings' => ['image_style' => 'medium'], 'delta_limit' => 1],
        ],
        'filters' => $default_filters('album'),
        'sorts' => [
            'created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'order' => 'DESC', 'plugin_id' => 'standard'],
        ],
    ],
    'page_1' => ['path' => 'media/photos'],
    'block_1' => ['display_title' => 'Latest Photos', 'display_options' => ['pager' => ['type' => 'some', 'options' => ['items_per_page' => 4]]]],
]);

// 7. Video Library
update_view_config('jsso_videos', 'Video Library', [
    'default' => [
        'style' => ['type' => 'grid', 'options' => ['columns' => 3]],
        'fields' => [
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'plugin_id' => 'field'],
            'field_media_video' => ['id' => 'field_media_video', 'table' => 'node__field_media_video', 'field' => 'field_media_video', 'plugin_id' => 'field', 'type' => 'media_thumbnail', 'settings' => ['image_style' => 'medium']],
        ],
        'filters' => array_merge($default_filters('video'), [
            'topic' => tax_filter('topic', 'field_tags', 'tags', 'Topic'),
        ]),
        'sorts' => [
            'created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'order' => 'DESC', 'plugin_id' => 'standard'],
        ],
    ],
    'page_1' => ['path' => 'media/videos'],
    'block_1' => ['display_title' => 'Featured Video', 'display_options' => ['pager' => ['type' => 'some', 'options' => ['items_per_page' => 1]]]],
]);

// Final Sync for Projects (Now adding filters)
update_view_config('jsso_projects', 'JSSO Projects', [
    'default' => [
        'filters' => array_merge($default_filters('project'), [
            'status_filter' => tax_filter('status_filter', 'field_status', 'project_status', 'Status'),
            'region_filter' => tax_filter('region_filter', 'field_region', 'regions', 'Region'),
        ]),
    ],
]);

echo "All views finalized with full field mapping and taxonomy filters.\n";
