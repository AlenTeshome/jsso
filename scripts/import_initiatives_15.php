<?php

use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

$initiatives = [
  ['title' => 'Regional Initiative for Maritime Sustainability', 'body' => '<p>This initiative aims to protect marine ecosystems.</p>', 'subtitle' => 'Protecting our shared waters'],
  ['title' => 'Strengthening Institutional Frameworks in Post-Conflict Zones', 'body' => '<p>A comprehensive program to rebuild governance.</p>', 'subtitle' => 'Building peace'],
  ['title' => 'Blue Economy Data Platform for the Arab Region', 'body' => '<p>An open data portal providing critical marine info.</p>', 'subtitle' => 'Data-driven sustainability'],
  ['title' => 'Access to Justice and Legal Empowerment Program', 'body' => '<p>Working to improve access to legal resources.</p>', 'subtitle' => 'Justice for all'],
  ['title' => 'Transboundary Water Resources Management Initiative', 'body' => '<p>Promoting peaceful cooperation for water sharing.</p>', 'subtitle' => 'Water as an instrument of peace'],
  ['title' => 'Digital Government Acceleration Framework', 'body' => '<p>Modernizing public services through digital transformation.</p>', 'subtitle' => 'Digital future'],
  ['title' => 'Youth Entrepreneurship and Innovation Hubs', 'body' => '<p>Fostering innovation and job creation for youth.</p>', 'subtitle' => 'Empowering youth'],
  ['title' => 'Gender Equality in Public Service Delivery', 'body' => '<p>Ensuring equal access to essential public services.</p>', 'subtitle' => 'Equality for all'],
  ['title' => 'Climate Resilience and Disaster Risk Reduction', 'body' => '<p>Building regional capacity to respond to climate change.</p>', 'subtitle' => 'Climate action'],
  ['title' => 'Smart Cities for Sustainable Urbanization', 'body' => '<p>Implementing smart technologies for better urban living.</p>', 'subtitle' => 'Smart urban growth'],
  ['title' => 'Economic Diversification and Trade Integration', 'body' => '<p>Supporting economic policies for regional trade.</p>', 'subtitle' => 'Stronger economies'],
  ['title' => 'Social Protection Floors for Vulnerable Populations', 'body' => '<p>Developing safety nets for the most vulnerable.</p>', 'subtitle' => 'Protecting the vulnerable'],
  ['title' => 'Energy Efficiency and Renewable Energy Transition', 'body' => '<p>Promoting a shift to sustainable energy sources.</p>', 'subtitle' => 'Clean energy'],
  ['title' => 'Data and Statistics for Evidence-Based Policy', 'body' => '<p>Improving regional data collection and analysis.</p>', 'subtitle' => 'Evidence-based decisions'],
  ['title' => 'Anti-Corruption and Transparency Initiative', 'body' => '<p>Strengthening integrity in public institutions.</p>', 'subtitle' => 'Transparent governance'],
];

$focus_ids = [77, 78, 79];

echo "Starting creation of 15 placeholder initiatives with images...\n";

// Ensure public directory exists
$dir = 'public://initiatives';
\Drupal::service('file_system')->prepareDirectory($dir, \Drupal\Core\File\FileSystemInterface::CREATE_DIRECTORY | \Drupal\Core\File\FileSystemInterface::MODIFY_PERMISSIONS);

foreach ($initiatives as $i => $init) {
  // Download placeholder image
  $image_data = file_get_contents('https://picsum.photos/seed/' . rand(1, 10000) . '/800/600');
  $file_uri = $dir . '/initiative_' . $i . '.jpg';
  $file = \Drupal::service('file.repository')->writeData($image_data, $file_uri, \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);

  // Create Media entity
  $media = Media::create([
    'bundle' => 'image',
    'uid' => 1,
    'name' => $init['title'] . ' Image',
    'status' => 1,
    'field_media_image' => [
      'target_id' => $file->id(),
      'alt' => $init['title'] . ' illustration',
    ],
  ]);
  $media->save();

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
    'field_featured_image' => [
      ['target_id' => $media->id()]
    ],
    'status' => 1,
    'uid' => 1,
  ];

  $focus_references = [];
  foreach ($selected_focuses as $fid) {
    $focus_references[] = ['target_id' => $fid];
  }
  $node_values['field_related_focus_area'] = $focus_references;

  $node = Node::create($node_values);
  $node->save();
  
  echo "Created initiative: '{$init['title']}' with image ID {$media->id()} and focus areas: " . implode(', ', $selected_focuses) . "\n";
}

echo "Completed.\n";
