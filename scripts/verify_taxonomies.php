<?php

$vids = ['strategic_frameworks', 'thematic_areas', 'project_status', 'resource_types', 'regions'];
foreach ($vids as $vid) {
    $vocabulary = \Drupal\taxonomy\Entity\Vocabulary::load($vid);
    if ($vocabulary) {
        $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
        echo "Vocabulary: $vid, Count: " . count($terms) . "\n";
    } else {
        echo "Vocabulary NOT FOUND: $vid\n";
    }
}
