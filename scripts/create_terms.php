<?php

use Drupal\taxonomy\Entity\Term;

function create_term($name, $vid, $parent_id = 0) {
  $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties([
    'name' => $name,
    'vid' => $vid,
  ]);
  
  foreach ($terms as $term) {
    if ($term->get('parent')->target_id == $parent_id) {
      return $term->id();
    }
  }

  $term = Term::create([
    'name' => $name,
    'vid' => $vid,
    'parent' => $parent_id,
  ]);
  $term->save();
  return $term->id();
}

// 1. Strategic Frameworks (Hierarchical)
$vid = 'strategic_frameworks';
$agenda_id = create_term('Agenda 2063', $vid);
$agenda_children = [
  'A Prosperous Africa',
  'An Integrated Continent',
  'Good Governance & Human Rights',
  'Peace & Security',
  'Cultural Renaissance',
  'People-Driven Development',
  'Global Player',
];
foreach ($agenda_children as $name) {
  create_term($name, $vid, $agenda_id);
}

$afdb_id = create_term('AfDB High 5s', $vid);
$afdb_children = [
  'Light up and Power Africa',
  'Feed Africa',
  'Industrialize Africa',
  'Integrate Africa',
  'Improve the Quality of Life for the People of Africa',
];
foreach ($afdb_children as $name) {
  create_term($name, $vid, $afdb_id);
}

$sdgs_id = create_term('SDGs (Sustainable Development Goals)', $vid);
$sdgs_children = [
  'Goal 1: No Poverty',
  'Goal 2: Zero Hunger',
  'Goal 5: Gender Equality',
  'Goal 7: Affordable and Clean Energy',
  'Goal 8: Decent Work and Economic Growth',
  'Goal 9: Industry, Innovation and Infrastructure',
  'Goal 13: Climate Action',
  'Goal 17: Partnerships for the Goals',
];
foreach ($sdgs_children as $name) {
  create_term($name, $vid, $sdgs_id);
}

// 2. Thematic Areas
$vid = 'thematic_areas';
$terms = [
  'Agriculture & Food Security',
  'Climate Change & Environment',
  'Regional Integration & Trade (AfCFTA)',
  'Energy & Infrastructure',
  'Governance & Human Rights',
  'Digital Transformation & Innovation',
  'Gender & Youth',
  'Peace & Security',
];
foreach ($terms as $name) {
  create_term($name, $vid);
}

// 3. Project Status
$vid = 'project_status';
$terms = [
  'Pipeline / Identification',
  'Ongoing / Active',
  'Completed / Closed',
  'On Hold / Suspended',
];
foreach ($terms as $name) {
  create_term($name, $vid);
}

// 4. Resource Types
$vid = 'resource_types';
$terms = [
  'Policy Brief',
  'Technical Report',
  'Strategic Framework',
  'Meeting Minute / Outcome Document',
  'Research Paper',
  'Treaty / Protocol',
  'Press Release',
];
foreach ($terms as $name) {
  create_term($name, $vid);
}

// 5. Regions
$vid = 'regions';
$terms = [
  'ECOWAS (West Africa)',
  'SADC (Southern Africa)',
  'EAC (East Africa)',
  'ECCAS (Central Africa)',
  'AMU (North Africa)',
  'CEN-SAD',
  'IGAD',
  'COMESA',
];
foreach ($terms as $name) {
  create_term($name, $vid);
}

echo "Terms creation completed.\n";
