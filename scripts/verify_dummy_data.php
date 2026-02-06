<?php

echo "Node counts by type:\n";
$types = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();
foreach ($types as $type_id => $type) {
    $count = \Drupal::entityQuery('node')
        ->condition('type', $type_id)
        ->accessCheck(FALSE)
        ->count()
        ->execute();
    echo "- " . $type->label() . " ($type_id): $count\n";
}

echo "\nMedia counts by type:\n";
$media_types = \Drupal::entityTypeManager()->getStorage('media_type')->loadMultiple();
foreach ($media_types as $mtype_id => $mtype) {
    $count = \Drupal::entityQuery('media')
        ->condition('bundle', $mtype_id)
        ->accessCheck(FALSE)
        ->count()
        ->execute();
    echo "- " . $mtype->label() . " ($mtype_id): $count\n";
}
