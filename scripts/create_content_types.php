<?php

use Drupal\node\Entity\NodeType;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityViewDisplay;

/**
 * Helper to create a content type.
 */
function create_node_type($id, $name, $description)
{
    if (!NodeType::load($id)) {
        $type = NodeType::create([
            'type' => $id,
            'name' => $name,
            'description' => $description,
        ]);
        $type->save();
        node_add_body_field($type);
        echo "Created content type: $name ($id)\n";
    } else {
        echo "Content type already exists: $id\n";
    }
}

/**
 * Helper to create/reuse a field storage and instance.
 */
function add_field($bundle, $field_name, $label, $type, $settings = [], $instance_settings = [], $cardinality = 1, $required = FALSE)
{
    // Create Field Storage if it doesn't exist.
    if (!FieldStorageConfig::loadByName('node', $field_name)) {
        $storage = FieldStorageConfig::create([
            'field_name' => $field_name,
            'entity_type' => 'node',
            'type' => $type,
            'settings' => $settings,
            'cardinality' => $cardinality,
        ]);
        $storage->save();
        echo "Created field storage: $field_name\n";
    }

    // Create Field Instance if it doesn't exist.
    if (!FieldConfig::loadByName('node', $bundle, $field_name)) {
        $field = FieldConfig::create([
            'field_name' => $field_name,
            'entity_type' => 'node',
            'bundle' => $bundle,
            'label' => $label,
            'required' => $required,
            'settings' => $instance_settings,
        ]);
        $field->save();
        echo "Added field $field_name to bundle $bundle\n";
    }
}

// 1. Create Content Types
create_node_type('project', 'Project Profile', 'A database entry for JSSO development initiatives, feeding the Map and Dashboard.');
create_node_type('resource', 'Knowledge Resource', 'A digital document or publication for the Knowledge Hub.');
create_node_type('twg', 'Working Group', 'A collaborative space for thematic experts.');
create_node_type('partner', 'Partner Organization', 'Profiles of RECs, Agencies, and Donors.');

// 2. Add Fields to Project Profile
add_field('project', 'field_geofield', 'Project Location', 'geofield', [], [], 1, TRUE);
add_field('project', 'field_status', 'Implementation Status', 'entity_reference', ['target_type' => 'taxonomy_term'], ['handler' => 'default:taxonomy_term', 'handler_settings' => ['target_bundles' => ['project_status' => 'project_status']]], 1, TRUE);
add_field('project', 'field_frameworks', 'Strategic Frameworks', 'entity_reference', ['target_type' => 'taxonomy_term'], ['handler' => 'default:taxonomy_term', 'handler_settings' => ['target_bundles' => ['strategic_frameworks' => 'strategic_frameworks']]], -1, TRUE);
add_field('project', 'field_theme', 'Sector / Theme', 'entity_reference', ['target_type' => 'taxonomy_term'], ['handler' => 'default:taxonomy_term', 'handler_settings' => ['target_bundles' => ['thematic_areas' => 'thematic_areas']]], -1);
add_field('project', 'field_budget', 'Total Budget (USD)', 'decimal', ['precision' => 20, 'scale' => 2]);
add_field('project', 'field_date_range', 'Project Duration', 'smartdate', []);

// 3. Add Fields to Knowledge Resource
add_field('resource', 'field_media_document', 'Upload Document', 'entity_reference', ['target_type' => 'media'], ['handler' => 'default:media', 'handler_settings' => ['target_bundles' => ['document' => 'document']]], 1, TRUE);
add_field('resource', 'field_resource_type', 'Document Type', 'entity_reference', ['target_type' => 'taxonomy_term'], ['handler' => 'default:taxonomy_term', 'handler_settings' => ['target_bundles' => ['resource_types' => 'resource_types']]], 1, TRUE);
add_field('resource', 'field_theme', 'Related Themes', 'entity_reference', ['target_type' => 'taxonomy_term'], ['handler' => 'default:taxonomy_term', 'handler_settings' => ['target_bundles' => ['thematic_areas' => 'thematic_areas']]], -1);
add_field('resource', 'field_frameworks', 'Strategic Alignment', 'entity_reference', ['target_type' => 'taxonomy_term'], ['handler' => 'default:taxonomy_term', 'handler_settings' => ['target_bundles' => ['strategic_frameworks' => 'strategic_frameworks']]], -1);

// 4. Add Fields to Working Group
add_field('twg', 'field_chair', 'Group Chair', 'entity_reference', ['target_type' => 'user'], ['handler' => 'default:user']);
add_field('twg', 'field_members', 'Group Members', 'entity_reference', ['target_type' => 'user'], ['handler' => 'default:user'], -1);
add_field('twg', 'field_theme', 'Associated Theme', 'entity_reference', ['target_type' => 'taxonomy_term'], ['handler' => 'default:taxonomy_term', 'handler_settings' => ['target_bundles' => ['thematic_areas' => 'thematic_areas']]], -1);

// 5. Add Fields to Partner Organization
add_field('partner', 'field_media_image', 'Partner Logo', 'entity_reference', ['target_type' => 'media'], ['handler' => 'default:media', 'handler_settings' => ['target_bundles' => ['image' => 'image']]]);
add_field('partner', 'field_website', 'Website URL', 'link', []);

// 6. Display Configurations (Example for Leaflet)
foreach (['project', 'resource', 'twg', 'partner'] as $bundle) {
    $form_display = EntityFormDisplay::load("node.$bundle.default");
    if (!$form_display) {
        $form_display = EntityFormDisplay::create([
            'targetEntityType' => 'node',
            'bundle' => $bundle,
            'mode' => 'default',
            'status' => TRUE,
        ]);
    }

    if ($bundle == 'project') {
        $form_display->setComponent('field_geofield', ['type' => 'leaflet_widget_default']);
        $form_display->setComponent('field_date_range', ['type' => 'smartdate_default']);
    }

    $form_display->save();
}

echo "Content types and fields creation completed.\n";
