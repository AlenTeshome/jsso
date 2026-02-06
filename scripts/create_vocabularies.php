<?php

use Drupal\taxonomy\Entity\Vocabulary;

$vocabularies = [
  'strategic_frameworks' => [
    'name' => 'Strategic Frameworks',
    'description' => 'The high-level mandates governing JSSO initiatives.',
  ],
  'thematic_areas' => [
    'name' => 'Thematic Areas',
    'description' => 'Sectors of intervention for filtering Knowledge Hub and Projects.',
  ],
  'project_status' => [
    'name' => 'Project Status',
    'description' => 'Lifecycle stages for the Project Database.',
  ],
  'resource_types' => [
    'name' => 'Resource Types',
    'description' => 'Categorization for documents in the Knowledge Hub.',
  ],
  'regions' => [
    'name' => 'Regions (RECs)',
    'description' => 'Regional Economic Communities for geographic filtering.',
  ],
];

foreach ($vocabularies as $vid => $data) {
  if (!Vocabulary::load($vid)) {
    Vocabulary::create([
      'vid' => $vid,
      'name' => $data['name'],
      'description' => $data['description'],
    ])->save();
    echo "Created vocabulary: $vid\n";
  }
  else {
    echo "Vocabulary already exists: $vid\n";
  }
}
