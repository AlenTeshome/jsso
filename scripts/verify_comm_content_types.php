<?php

$bundles = ['news', 'event', 'album', 'video'];
foreach ($bundles as $bundle) {
    $type = \Drupal\node\Entity\NodeType::load($bundle);
    if ($type) {
        echo "Content Type: " . $type->label() . " ($bundle)\n";
        $fields = \Drupal::service('entity_field.manager')->getFieldDefinitions('node', $bundle);
        foreach ($fields as $field_name => $definition) {
            if ($definition instanceof \Drupal\field\Entity\FieldConfig) {
                echo "  - Field: $field_name (" . $definition->getType() . ")\n";
            }
        }
    } else {
        echo "Content Type NOT FOUND: $bundle\n";
    }
    echo "\n";
}
