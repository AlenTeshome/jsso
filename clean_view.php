<?php
$config = \Drupal::configFactory()->getEditable('views.view.jsso_knowledge_hub');
$data = $config->getRawData();

// Remove ALL field_realated_focus_area_target_id and field_related_initiatives_target_id filters 
// and eref_node_titles filters from block_3 to ensure it's completely clean.
unset($data['display']['block_3']['display_options']['filters']['eref_node_titles']);
unset($data['display']['block_3']['display_options']['filters']['eref_node_titles_1']);
unset($data['display']['block_3']['display_options']['filters']['field_realated_focus_area_target_id']);
unset($data['display']['block_3']['display_options']['filters']['field_related_initiatives_target_id']);

// Remove relationships if they exist
unset($data['display']['block_3']['display_options']['relationships']['field_related_focus_area']);
unset($data['display']['block_3']['display_options']['relationships']['field_related_initiatives']);

$config->setData($data)->save();
echo "Cleaned View Configuration.\n";
