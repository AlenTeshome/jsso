<?php
$config = \Drupal::configFactory()->getEditable('views.view.jsso_knowledge_hub');
$data = $config->getRawData();

unset($data['display']['block_3']['display_options']['filters']['eref_node_titles']);
unset($data['display']['block_3']['display_options']['filters']['eref_node_titles_1']);

unset($data['display']['block_3']['display_options']['relationships']['field_related_focus_area']);
unset($data['display']['block_3']['display_options']['relationships']['field_related_initiatives']);

$data['display']['block_3']['display_options']['filters']['field_realated_focus_area_target_id'] = [
  'id' => 'field_realated_focus_area_target_id',
  'table' => 'node__field_realated_focus_area',
  'field' => 'field_realated_focus_area_target_id',
  'relationship' => 'none',
  'group_type' => 'group',
  'admin_label' => '',
  'plugin_id' => 'numeric',
  'operator' => '=',
  'value' => ['min' => '', 'max' => '', 'value' => ''],
  'group' => 1,
  'exposed' => true,
  'expose' => [
    'operator_id' => 'field_realated_focus_area_target_id_op',
    'label' => 'Focus Areas',
    'description' => '',
    'use_operator' => false,
    'operator' => 'field_realated_focus_area_target_id_op',
    'operator_limit_selection' => false,
    'operator_list' => [],
    'identifier' => 'field_realated_focus_area_target_id',
    'required' => false,
    'remember' => false,
    'multiple' => false,
    'remember_roles' => ['authenticated' => 'authenticated'],
  ],
  'is_grouped' => false,
  'group_info' => [
    'label' => '',
    'description' => '',
    'identifier' => '',
    'optional' => true,
    'widget' => 'select',
    'multiple' => false,
    'remember' => false,
    'default_group' => 'All',
    'default_group_multiple' => [],
    'group_items' => [],
  ],
];

$data['display']['block_3']['display_options']['filters']['field_related_initiatives_target_id'] = [
  'id' => 'field_related_initiatives_target_id',
  'table' => 'node__field_related_initiatives',
  'field' => 'field_related_initiatives_target_id',
  'relationship' => 'none',
  'group_type' => 'group',
  'admin_label' => '',
  'plugin_id' => 'numeric',
  'operator' => '=',
  'value' => ['min' => '', 'max' => '', 'value' => ''],
  'group' => 1,
  'exposed' => true,
  'expose' => [
    'operator_id' => 'field_related_initiatives_target_id_op',
    'label' => 'Initiatives',
    'description' => '',
    'use_operator' => false,
    'operator' => 'field_related_initiatives_target_id_op',
    'operator_limit_selection' => false,
    'operator_list' => [],
    'identifier' => 'field_related_initiatives_target_id',
    'required' => false,
    'remember' => false,
    'multiple' => false,
    'remember_roles' => ['authenticated' => 'authenticated'],
  ],
  'is_grouped' => false,
  'group_info' => [
    'label' => '',
    'description' => '',
    'identifier' => '',
    'optional' => true,
    'widget' => 'select',
    'multiple' => false,
    'remember' => false,
    'default_group' => 'All',
    'default_group_multiple' => [],
    'group_items' => [],
  ],
];

$config->setData($data)->save();
echo "Fixed view.\n";
