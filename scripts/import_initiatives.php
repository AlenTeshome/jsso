<?php

use Drupal\node\Entity\Node;

// Hardcoded placeholder initiatives since UN ESCWA returned 403
$initiatives = [
  [
    'title' => 'Regional Initiative for Maritime Sustainability',
    'body' => '<p>This initiative aims to protect marine ecosystems and promote sustainable blue economy practices across the region.</p>',
    'subtitle' => 'Protecting our shared waters',
  ],
  [
    'title' => 'Strengthening Institutional Frameworks in Post-Conflict Zones',
    'body' => '<p>A comprehensive program to rebuild governance structures and ensure accountability in areas recovering from conflict.</p>',
    'subtitle' => 'Building peace and strong institutions',
  ],
  [
    'title' => 'Blue Economy Data Platform for the Arab Region',
    'body' => '<p>An open data portal providing critical information on marine biodiversity, fishing practices, and coastal development.</p>',
    'subtitle' => 'Data-driven sustainability',
  ],
  [
    'title' => 'Access to Justice and Legal Empowerment Program',
    'body' => '<p>Working with local judiciaries to improve access to legal resources for marginalized communities.</p>',
    'subtitle' => 'Justice for all',
  ],
  [
    'title' => 'Transboundary Water Resources Management Initiative',
    'body' => '<p>Promoting peaceful cooperation among neighboring countries for the equitable sharing of water resources.</p>',
    'subtitle' => 'Water as an instrument of peace',
  ],
];

// Available Focus Area Node IDs (from our previous DB check)
$focus_ids = [77, 78, 79];

echo "Starting creation of placeholder initiatives...\n";

foreach ($initiatives as $init) {
  // Randomly select 1 or 2 focus areas
  shuffle($focus_ids);
  $num_focuses = rand(1, 2);
  $selected_focuses = array_slice($focus_ids, 0, $num_focuses);

  $node_values = [
    'type' => 'initiative',
    'title' => $init['title'],
    'body' => [
      'value' => $init['body'],
      'format' => 'full_html',
    ],
    'field_subtitle' => $init['subtitle'],
    'status' => 1,
    'uid' => 1,
  ];

  // Add the randomly selected focus area references
  $focus_references = [];
  foreach ($selected_focuses as $fid) {
    $focus_references[] = ['target_id' => $fid];
  }
  $node_values['field_related_focus_area'] = $focus_references;

  $node = Node::create($node_values);
  $node->save();
  
  echo "Created initiative: '{$init['title']}' with focus area IDs: " . implode(', ', $selected_focuses) . "\n";
}

echo "Completed.\n";
