<?php

use Drupal\views\Entity\View;

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
    try {
        $view->save();
        echo "Successfully saved View: $id\n";
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

// 1. JSSO News (No taxonomy filters requested)
update_view_config('jsso_news', 'JSSO News', [
    'default' => [
        'fields' => [
            'field_media_image' => ['id' => 'field_media_image', 'table' => 'node__field_media_image', 'field' => 'field_media_image', 'plugin_id' => 'field', 'type' => 'media_thumbnail', 'settings' => ['image_style' => 'thumbnail']],
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'plugin_id' => 'field'],
            'created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'plugin_id' => 'field', 'type' => 'timestamp', 'settings' => ['date_format' => 'medium']],
            'body' => ['id' => 'body', 'table' => 'node__body', 'field' => 'body', 'plugin_id' => 'field', 'type' => 'text_summary_or_trimmed'],
        ],
        'filters' => $default_filters('news'),
        'sorts' => [
            'created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'order' => 'DESC', 'plugin_id' => 'standard'],
        ],
        'pager' => ['type' => 'full', 'options' => ['items_per_page' => 10]],
    ],
    'page_1' => ['path' => 'news'],
    'block_1' => [
        'display_title' => 'Latest News',
        'display_options' => [
            'style' => ['type' => 'grid', 'options' => ['columns' => 3]],
            'pager' => ['type' => 'some', 'options' => ['items_per_page' => 3]],
        ],
    ],
]);

// 2. JSSO Events
update_view_config('jsso_events', 'JSSO Events', [
    'default' => [
        'style' => ['type' => 'table'],
        'fields' => [
            'field_date_range' => ['id' => 'field_date_range', 'table' => 'node__field_date_range', 'field' => 'field_date_range', 'plugin_id' => 'field', 'type' => 'smartdate_default'],
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'plugin_id' => 'field', 'label' => 'Event Name'],
            'field_location_text' => ['id' => 'field_location_text', 'table' => 'node__field_location_text', 'field' => 'field_location_text', 'plugin_id' => 'field'],
            'field_event_type' => ['id' => 'field_event_type', 'table' => 'node__field_event_type', 'field' => 'field_event_type', 'plugin_id' => 'field'],
        ],
        'filters' => array_merge($default_filters('event'), [
            'field_date_range_value' => [
                'id' => 'field_date_range_value',
                'table' => 'node__field_date_range',
                'field' => 'field_date_range_value',
                'operator' => '>=',
                'value' => ['value' => 'now'],
                'plugin_id' => 'date',
            ],
        ]),
        'sorts' => [
            'field_date_range_value' => ['id' => 'field_date_range_value', 'table' => 'node__field_date_range', 'field' => 'field_date_range_value', 'order' => 'ASC', 'plugin_id' => 'standard'],
        ],
    ],
    'page_1' => ['path' => 'events'],
    'block_1' => [
        'display_title' => 'Upcoming Events',
        'display_options' => [
            'pager' => ['type' => 'some', 'options' => ['items_per_page' => 2]],
        ],
    ],
]);

// 3. JSSO Projects (Avoiding taxonomy_index_tid for now to test save)
update_view_config('jsso_projects', 'JSSO Projects', [
    'default' => [
        'style' => ['type' => 'table'],
        'fields' => [
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'plugin_id' => 'field', 'label' => 'Project Title'],
            'field_status' => ['id' => 'field_status', 'table' => 'node__field_status', 'field' => 'field_status', 'plugin_id' => 'field', 'type' => 'entity_reference_label'],
            'field_budget' => ['id' => 'field_budget', 'table' => 'node__field_budget', 'field' => 'field_budget', 'plugin_id' => 'field'],
            'field_region' => ['id' => 'field_region', 'table' => 'node__field_region', 'field' => 'field_region', 'plugin_id' => 'field', 'type' => 'entity_reference_label'],
        ],
        'filters' => $default_filters('project'),
        'sorts' => [
            'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'order' => 'ASC', 'plugin_id' => 'standard'],
        ],
    ],
    'page_1' => ['path' => 'projects'],
]);

// 4. JSSO Partners
update_view_config('jsso_partners', 'JSSO Partners', [
    'default' => [
        'style' => ['type' => 'grid'],
        'fields' => [
            'field_media_image' => ['id' => 'field_media_image', 'table' => 'node__field_media_image', 'field' => 'field_media_image', 'plugin_id' => 'field', 'type' => 'media_thumbnail', 'settings' => ['image_style' => 'medium']],
        ],
        'filters' => $default_filters('partner'),
    ],
    'block_1' => ['display_title' => 'Our Partners'],
]);

echo "Initial 4 views updated without complex taxonomy filters to confirm save works.\n";
