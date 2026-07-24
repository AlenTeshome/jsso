<?php
use Drupal\views\Views;
$config = \Drupal::configFactory()->getEditable('views.view.jsso_knowledge_hub');
$data = $config->getRawData();

$data['display']['block_3']['display_options']['filters']['field_realated_focus_area_target_id']['plugin_id'] = 'many_to_one';
$data['display']['block_3']['display_options']['filters']['field_realated_focus_area_target_id']['operator'] = 'or'; // 'or' is IN

$config->setData($data)->save();
echo "Changed to many_to_one.\n";
