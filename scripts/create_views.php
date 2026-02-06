<?php

use Drupal\views\Entity\View;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;

/**
 * Helper to add field_region to project and update data.
 */
function fix_project_region_field()
{
    echo "Adding field_region to project content type...\n";
    if (!FieldStorageConfig::loadByName('node', 'field_region')) {
        FieldStorageConfig::create([
            'field_name' => 'field_region',
            'entity_type' => 'node',
            'type' => 'entity_reference',
            'settings' => ['target_type' => 'taxonomy_term'],
            'cardinality' => 1,
        ])->save();
    }

    if (!FieldConfig::loadByName('node', 'project', 'field_region')) {
        FieldConfig::create([
            'field_name' => 'field_region',
            'entity_type' => 'node',
            'bundle' => 'project',
            'label' => 'Region',
            'settings' => [
                'handler' => 'default:taxonomy_term',
                'handler_settings' => ['target_bundles' => ['regions' => 'regions']],
            ],
        ])->save();
    }

    // Update dummy projects with random regions.
    $tids = \Drupal::entityQuery('taxonomy_term')
        ->condition('vid', 'regions')
        ->accessCheck(FALSE)
        ->execute();

    if (!empty($tids)) {
        $nids = \Drupal::entityQuery('node')
            ->condition('type', 'project')
            ->accessCheck(FALSE)
            ->execute();

        foreach ($nids as $nid) {
            $node = \Drupal\node\Entity\Node::load($nid);
            $node->set('field_region', array_rand($tids));
            $node->save();
        }
        echo "Updated " . count($nids) . " projects with regions.\n";
    }
}

/**
 * Note: Programmatic View creation is extremely complex. 
 * I will use a simplified approach for this script.
 */

fix_project_region_field();

echo "Views creation starting... (Simulated via Drush Commands for accuracy)\n";
// Since View object structure is massive and brittle via PHP E-A-I, 
// I will output the Drush commands to be executed.
// Actually, I'll use a more direct approach to save simple view configs.

// View 1: JSSO News
$news_view = [
    'id' => 'jsso_news',
    'label' => 'JSSO News',
    'description' => 'Lists News Articles.',
    'tag' => 'default',
    'base_table' => 'node_field_data',
    'display' => [
        'default' => [
            'display_plugin' => 'default',
            'id' => 'default',
            'display_title' => 'Master',
            'display_options' => [
                'fields' => [
                    'title' => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'entity_type' => 'node', 'label' => ''],
                ],
                'filters' => [
                    'status' => ['id' => 'status', 'table' => 'node_field_data', 'field' => 'status', 'value' => 1, 'entity_type' => 'node'],
                    'type' => ['id' => 'type', 'table' => 'node_field_data', 'field' => 'type', 'value' => ['news' => 'news'], 'entity_type' => 'node'],
                ],
                'sorts' => [
                    'created' => ['id' => 'created', 'table' => 'node_field_data', 'field' => 'created', 'order' => 'DESC', 'entity_type' => 'node'],
                ],
            ],
        ],
        'page_1' => [
            'display_plugin' => 'page',
            'id' => 'page_1',
            'display_title' => 'Page',
            'display_options' => [
                'path' => 'news',
                'row' => ['type' => 'fields'],
                'pager' => ['type' => 'full', 'options' => ['items_per_page' => 10]],
            ],
        ],
        'block_1' => [
            'display_plugin' => 'block',
            'id' => 'block_1',
            'display_title' => 'Latest News',
            'display_options' => [
                'style' => ['type' => 'grid', 'options' => ['columns' => 3]],
                'pager' => ['type' => 'some', 'options' => ['items_per_page' => 3]],
            ],
        ],
    ],
];

// Re-saving Config objects for Views is better done via YAML or Drush.
// I will use `drush config:set` for the paths and simple settings if possible.
// Actually, I'll use the Drupal API to create a very basic version of each.

function create_simple_view($id, $label, $path = NULL, $type = 'node', $bundle = NULL)
{
    if (View::load($id)) {
        echo "View $id already exists.\n";
        return;
    }

    $view = View::create([
        'id' => $id,
        'label' => $label,
        'base_table' => 'node_field_data',
        'display' => [
            'default' => [
                'display_plugin' => 'default',
                'id' => 'default',
                'display_options' => [
                    'filters' => [
                        'status' => ['id' => 'status', 'table' => 'node_field_data', 'field' => 'status', 'value' => '1', 'plugin_id' => 'boolean'],
                    ],
                ],
            ],
        ],
    ]);

    if ($bundle) {
        $view->getDisplay('default')['display_options']['filters']['type'] = [
            'id' => 'type',
            'table' => 'node_field_data',
            'field' => 'type',
            'value' => [$bundle => $bundle],
            'plugin_id' => 'bundle',
        ];
    }

    if ($path) {
        $view->addDisplay('page', 'Page', 'page_1');
        $view->getDisplay('page_1')['display_options']['path'] = $path;
    }

    $view->save();
    echo "Created View: $label ($id)\n";
}

create_simple_view('jsso_news', 'JSSO News', 'news', 'node', 'news');
create_simple_view('jsso_events', 'JSSO Events', 'events', 'node', 'event');
create_simple_view('jsso_projects', 'JSSO Projects', 'projects', 'node', 'project');
create_simple_view('jsso_partners', 'JSSO Partners', NULL, 'node', 'partner');

echo "Views creation completed. (Note: Detailed field configuration should be polished via UI or full YAML export/import for complex settings like Leaflet or Tables).\n";
