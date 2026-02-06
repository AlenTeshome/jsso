<?php

use Drupal\node\Entity\NodeType;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;
use Drupal\Core\Entity\Entity\EntityFormDisplay;

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
create_node_type('news', 'News Article', 'Press releases, updates, and stories.');
create_node_type('event', 'Event', 'Upcoming summits, webinars, and meetings.');
create_node_type('album', 'Photo Album', 'Collections of photos from JSSO events and missions.');
create_node_type('video', 'Video', 'Featured YouTube videos and multimedia content.');

// 2. News Article Fields
add_field('news', 'field_media_image', 'Main Image', 'entity_reference', ['target_type' => 'media'], ['handler' => 'default:media', 'handler_settings' => ['target_bundles' => ['image' => 'image']]], 1, TRUE);
add_field('news', 'field_tags', 'Topics', 'entity_reference', ['target_type' => 'taxonomy_term'], ['handler' => 'default:taxonomy_term', 'handler_settings' => ['target_bundles' => ['tags' => 'tags']]], -1);

// 3. Event Fields
add_field('event', 'field_date_range', 'Event Dates', 'smartdate', [], [], 1, TRUE);
add_field('event', 'field_location_text', 'Location / Venue', 'string');
add_field('event', 'field_event_type', 'Event Category', 'list_string', ['allowed_values' => ['conference' => 'Conference', 'meeting' => 'Meeting', 'webinar' => 'Webinar', 'launch' => 'Launch']]);

// 4. Photo Album Fields
add_field('album', 'field_gallery_images', 'Photos', 'entity_reference', ['target_type' => 'media'], ['handler' => 'default:media', 'handler_settings' => ['target_bundles' => ['image' => 'image']]], -1, TRUE);
add_field('album', 'field_related_event', 'Related Event', 'entity_reference', ['target_type' => 'node'], ['handler' => 'default:node', 'handler_settings' => ['target_bundles' => ['event' => 'event']]]);

// 5. Video Fields
add_field('video', 'field_media_video', 'YouTube Video', 'entity_reference', ['target_type' => 'media'], ['handler' => 'default:media', 'handler_settings' => ['target_bundles' => ['remote_video' => 'remote_video']]], 1, TRUE);
add_field('video', 'field_tags', 'Topics', 'entity_reference', ['target_type' => 'taxonomy_term'], ['handler' => 'default:taxonomy_term', 'handler_settings' => ['target_bundles' => ['tags' => 'tags']]], -1);

// 6. Display Configurations
foreach (['news', 'event', 'album', 'video'] as $bundle) {
    $form_display = EntityFormDisplay::load("node.$bundle.default");
    if (!$form_display) {
        $form_display = EntityFormDisplay::create([
            'targetEntityType' => 'node',
            'bundle' => $bundle,
            'mode' => 'default',
            'status' => TRUE,
        ]);
    }

    // Set specific widgets
    if ($bundle == 'event') {
        $form_display->setComponent('field_date_range', ['type' => 'smartdate_default']);
    }
    if ($bundle == 'news' || $bundle == 'album') {
        $form_display->setComponent($bundle == 'news' ? 'field_media_image' : 'field_gallery_images', ['type' => 'media_library_widget']);
    }
    if ($bundle == 'video') {
        $form_display->setComponent('field_media_video', ['type' => 'media_library_widget']);
    }

    $form_display->save();
}

echo "Communication content types and fields creation completed.\n";
