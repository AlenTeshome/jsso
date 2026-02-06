<?php
use Drupal\views\Views;

// Load the view.
$view = Views::getView('project_map');
if (!$view) {
    echo "View 'project_map' not found.\n";
    exit;
}

$display = &$view->storage->getDisplay('default');
if (!isset($display['display_options']['style'])) {
    echo "Display 'default' does not have style options.\n";
    exit;
}

$style = &$display['display_options']['style'];

if ($style['type'] == 'leaflet_map') {
    // 1. Change map tiles to physical.
    $style['options']['leaflet_map'] = 'esri-world_physical_map';

    // 2. Set Min Zoom.
    $style['options']['map_position']['minZoom'] = 3;
    $style['options']['map_position']['maxZoom'] = 18;

    // 3. Set Force and Initial Zoom/Center.
    $style['options']['map_position']['force'] = TRUE;
    $style['options']['map_position']['zoom'] = 4;
    $style['options']['map_position']['center']['lat'] = 0;
    $style['options']['map_position']['center']['lon'] = 20;

    // Save the view.
    $view->storage->save();
    echo "View 'project_map' reconfigured successfully.\n";
} else {
    echo "View 'project_map' does not use Leaflet Map format (type: " . $style['type'] . ").\n";
}
