<?php
use Drupal\views\Views;
$config = \Drupal::configFactory()->getEditable('views.view.jsso_knowledge_hub');
$data = $config->getRawData();

$data['display']['block_3']['display_options']['filters']['field_realated_focus_area_target_id']['plugin_id'] = 'many_to_one';
$data['display']['block_3']['display_options']['filters']['field_realated_focus_area_target_id']['operator'] = 'or';
$data['display']['block_3']['display_options']['filters']['field_realated_focus_area_target_id']['expose']['multiple'] = true;
$data['display']['block_3']['display_options']['filters']['field_realated_focus_area_target_id']['expose']['reduce'] = false;

$data['display']['block_3']['display_options']['filters']['field_related_initiatives_target_id']['plugin_id'] = 'many_to_one';
$data['display']['block_3']['display_options']['filters']['field_related_initiatives_target_id']['operator'] = 'or';
$data['display']['block_3']['display_options']['filters']['field_related_initiatives_target_id']['expose']['multiple'] = true;
$data['display']['block_3']['display_options']['filters']['field_related_initiatives_target_id']['expose']['reduce'] = false;

$config->setData($data)->save();
echo "Changed both filters to many_to_one with multiple enabled.\n";
