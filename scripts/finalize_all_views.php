<?php

use Drupal\views\Entity\View;

/**
 * Helper to ensure a view is updated or created with a full configuration.
 */
function update_view_config($id, $label, $config_patch)
{
    $view = View::load($id);
    if (!$view) {
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
    $view->save();
    echo "Verified/Updated View: $id\n";
}

// Common Default Options (Filters for Published & Type)
$default_base = function ($bundle) {
    return [
        'filters' => [
            'status' => ['id' => 'status', 'table' => 'node_field_data', 'field' => 'status', 'value' => '1', 'plugin_id' => 'boolean', 'entity_status' => '1'],
            'type' => ['id' => 'type', 'table' => 'node_field_data', 'field' => 'type', 'value' => [$bundle => $bundle], 'plugin_id' => 'bundle'],
        ],
        'row' => ['type' => 'fields'],
        'style' => ['type' => 'default'],
    ];
};

// 1. JSSO News
update_view_config('jsso_news', 'JSSO News', [
    'default' => array_replace_recursive($default_base('news'), [
        'fields' => [
            'field_media_image' => ['id' => 'field_media_image', 'table' => 'node__field_media_image', 'field' => 'field_media_image', 'type' => 'media_thumbnail', 'settings' => ['image_style' => 'thumbnail']],
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title'],
            'created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'type' => 'timestamp', 'settings' => ['date_format' => 'medium']],
            'body' => ['id' => 'body', 'table' => 'node__body', 'field' => 'body', 'type' => 'text_summary_or_trimmed'],
        ],
        'sorts' => ['created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'order' => 'DESC']],
    ]),
    'page_1' => ['path' => 'news', 'pager' => ['type' => 'full', 'options' => ['items_per_page' => 10]]],
    'block_1' => ['display_title' => 'Latest News', 'style' => ['type' => 'grid', 'options' => ['columns' => 3]], 'pager' => ['type' => 'some', 'options' => ['items_per_page' => 3]]],
]);

// 2. JSSO Events
update_view_config('jsso_events', 'JSSO Events', [
    'default' => array_replace_recursive($default_base('event'), [
        'style' => ['type' => 'table'],
        'fields' => [
            'field_date_range' => ['id' => 'field_date_range', 'table' => 'node__field_date_range', 'field' => 'field_date_range', 'type' => 'smartdate_default'],
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'label' => 'Event Name'],
            'field_location_text' => ['id' => 'field_location_text', 'table' => 'node__field_location_text', 'field' => 'field_location_text'],
            'field_event_type' => ['id' => 'field_event_type', 'table' => 'node__field_event_type', 'field' => 'field_event_type'],
        ],
        'filters' => [
            'field_date_range_value' => ['id' => 'field_date_range_value', 'table' => 'node__field_date_range', 'field' => 'field_date_range_value', 'operator' => '>=', 'value' => ['value' => 'now'], 'plugin_id' => 'date'],
        ],
        'sorts' => ['field_date_range_value' => ['id' => 'field_date_range_value', 'table' => 'node__field_date_range', 'field' => 'field_date_range_value', 'order' => 'ASC']],
    ]),
    'page_1' => ['path' => 'events'],
    'block_1' => ['display_title' => 'Upcoming Events', 'style' => ['type' => 'default'], 'pager' => ['type' => 'some', 'options' => ['items_per_page' => 2]]],
]);

// 3. JSSO Projects
update_view_config('jsso_projects', 'JSSO Projects', [
    'default' => array_replace_recursive($default_base('project'), [
        'style' => ['type' => 'table'],
        'fields' => [
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'label' => 'Project Title', 'plugin_id' => 'field'],
            'field_status' => ['id' => 'field_status', 'table' => 'node__field_status', 'field' => 'field_status', 'plugin_id' => 'field', 'type' => 'entity_reference_label'],
            'field_budget' => ['id' => 'field_budget', 'table' => 'node__field_budget', 'field' => 'field_budget', 'plugin_id' => 'field'],
            'field_region' => ['id' => 'field_region', 'table' => 'node__field_region', 'field' => 'field_region', 'plugin_id' => 'field', 'type' => 'entity_reference_label'],
        ],
        'filters' => [
            'status' => ['id' => 'status', 'table' => 'node_field_data', 'field' => 'status', 'value' => '1', 'plugin_id' => 'boolean', 'entity_type' => 'node'],
            'type' => ['id' => 'type', 'table' => 'node_field_data', 'field' => 'type', 'value' => ['project' => 'project'], 'plugin_id' => 'bundle', 'entity_type' => 'node'],
            'field_status_target_id' => [
                'id' => 'field_status_target_id',
                'table' => 'node__field_status',
                'field' => 'field_status_target_id',
                'plugin_id' => 'numeric',
                'exposed' => TRUE,
                'expose' => ['label' => 'Status', 'identifier' => 'status'],
            ],
            'field_region_target_id' => [
                'id' => 'field_region_target_id',
                'table' => 'node__field_region',
                'field' => 'field_region_target_id',
                'plugin_id' => 'numeric',
                'exposed' => TRUE,
                'expose' => ['label' => 'Region', 'identifier' => 'region'],
            ],
        ],
        'sorts' => ['title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'order' => 'ASC', 'plugin_id' => 'standard']],
    ]),
    'page_1' => ['path' => 'projects'],
]);

// 4. JSSO Partners
update_view_config('jsso_partners', 'JSSO Partners', [
    'default' => array_replace_recursive($default_base('partner'), [
        'style' => ['type' => 'grid'],
        'fields' => [
            'field_media_image' => ['id' => 'field_media_image', 'table' => 'node__field_media_image', 'field' => 'field_media_image', 'type' => 'media_thumbnail', 'settings' => ['image_style' => 'medium'], 'plugin_id' => 'field'],
        ],
    ]),
    'block_1' => ['display_title' => 'Our Partners', 'pager' => ['type' => 'none']],
]);

// 5. Knowledge Hub
update_view_config('jsso_knowledge_hub', 'Knowledge Hub', [
    'default' => array_replace_recursive($default_base('resource'), [
        'fields' => [
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'plugin_id' => 'field'],
            'field_resource_type' => ['id' => 'field_resource_type', 'table' => 'node__field_resource_type', 'field' => 'field_resource_type', 'plugin_id' => 'field', 'type' => 'entity_reference_label'],
            'created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'label' => 'Publication Date', 'type' => 'timestamp', 'settings' => ['date_format' => 'html_date'], 'plugin_id' => 'date'],
            'field_media_document' => ['id' => 'field_media_document', 'table' => 'node__field_media_document', 'field' => 'field_media_document', 'label' => 'Download', 'type' => 'entity_reference_label', 'settings' => ['link' => TRUE], 'plugin_id' => 'field'],
        ],
        'filters' => [
            'status' => ['id' => 'status', 'table' => 'node_field_data', 'field' => 'status', 'value' => '1', 'plugin_id' => 'boolean', 'entity_type' => 'node'],
            'type' => ['id' => 'type', 'table' => 'node_field_data', 'field' => 'type', 'value' => ['resource' => 'resource'], 'plugin_id' => 'bundle', 'entity_type' => 'node'],
            'field_resource_type_target_id' => [
                'id' => 'field_resource_type_target_id',
                'table' => 'node__field_resource_type',
                'field' => 'field_resource_type_target_id',
                'plugin_id' => 'numeric',
                'exposed' => TRUE,
                'expose' => ['label' => 'Resource Type', 'identifier' => 'res_type'],
            ],
            'field_theme_target_id' => [
                'id' => 'field_theme_target_id',
                'table' => 'node__field_theme',
                'field' => 'field_theme_target_id',
                'plugin_id' => 'numeric',
                'exposed' => TRUE,
                'expose' => ['label' => 'Thematic Area', 'identifier' => 'theme'],
            ],
            'field_frameworks_target_id' => [
                'id' => 'field_frameworks_target_id',
                'table' => 'node__field_frameworks',
                'field' => 'field_frameworks_target_id',
                'plugin_id' => 'numeric',
                'exposed' => TRUE,
                'expose' => ['label' => 'Strategic Framework', 'identifier' => 'framework'],
            ],
        ],
        'sorts' => ['created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'order' => 'DESC', 'plugin_id' => 'date']],
    ]),
    'page_1' => ['path' => 'knowledge-hub', 'pager' => ['type' => 'mini', 'options' => ['items_per_page' => 12]]],
    'block_1' => ['display_title' => 'Recent Publications', 'pager' => ['type' => 'some', 'options' => ['items_per_page' => 3]]],
]);

// 6. Photo Albums
update_view_config('jsso_albums', 'Photo Albums', [
    'default' => array_replace_recursive($default_base('album'), [
        'style' => ['type' => 'grid', 'options' => ['columns' => 3]],
        'fields' => [
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'plugin_id' => 'field'],
            'field_gallery_images' => ['id' => 'field_gallery_images', 'table' => 'node__field_gallery_images', 'field' => 'field_gallery_images', 'label' => 'Cover', 'type' => 'media_thumbnail', 'settings' => ['image_style' => 'medium'], 'plugin_id' => 'field', 'delta_limit' => 1],
        ],
        'sorts' => ['created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'order' => 'DESC', 'plugin_id' => 'date']],
    ]),
    'page_1' => ['path' => 'media/photos'],
    'block_1' => ['display_title' => 'Latest Photos', 'pager' => ['type' => 'some', 'options' => ['items_per_page' => 4]]],
]);

// 7. Video Library
update_view_config('jsso_videos', 'Video Library', [
    'default' => array_replace_recursive($default_base('video'), [
        'style' => ['type' => 'grid', 'options' => ['columns' => 3]],
        'fields' => [
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'plugin_id' => 'field'],
            'field_media_video' => ['id' => 'field_media_video', 'table' => 'node__field_media_video', 'field' => 'field_media_video', 'type' => 'media_thumbnail', 'settings' => ['image_style' => 'medium'], 'plugin_id' => 'field'],
        ],
        'filters' => [
            'status' => ['id' => 'status', 'table' => 'node_field_data', 'field' => 'status', 'value' => '1', 'plugin_id' => 'boolean', 'entity_type' => 'node'],
            'type' => ['id' => 'type', 'table' => 'node_field_data', 'field' => 'type', 'value' => ['video' => 'video'], 'plugin_id' => 'bundle', 'entity_type' => 'node'],
            'field_tags_target_id' => [
                'id' => 'field_tags_target_id',
                'table' => 'node__field_tags',
                'field' => 'field_tags_target_id',
                'plugin_id' => 'numeric',
                'exposed' => TRUE,
                'expose' => ['label' => 'Topic', 'identifier' => 'topic'],
            ],
        ],
        'sorts' => ['created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'order' => 'DESC', 'plugin_id' => 'date']],
    ]),
    'page_1' => ['path' => 'media/videos'],
    'block_1' => ['display_title' => 'Featured Video', 'pager' => ['type' => 'some', 'options' => ['items_per_page' => 1]]],
]);

echo "All views finalized with fields and filters.\n";
