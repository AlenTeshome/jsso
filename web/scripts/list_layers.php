<?php
$maps = \Drupal::moduleHandler()->invokeAll('leaflet_map_info');
echo implode("\n", array_keys($maps)) . "\n";
