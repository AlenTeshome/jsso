<?php

use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\user\Entity\User;

/**
 * Helper to get random term IDs from a vocabulary.
 */
function get_random_tids($vid, $count = 1)
{
    $tids = \Drupal::entityQuery('taxonomy_term')
        ->condition('vid', $vid)
        ->accessCheck(FALSE)
        ->execute();
    if (empty($tids))
        return [];
    shuffle($tids);
    return array_slice($tids, 0, $count);
}

/**
 * Helper to create a placeholder Media Image.
 */
function create_media_image($name)
{
    $data = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==');
    $filename = 'placeholder_' . strtolower(str_replace(' ', '_', $name)) . '.png';
    /** @var \Drupal\file\FileRepositoryInterface $file_repository */
    $file_repository = \Drupal::service('file.repository');
    $file = $file_repository->writeData($data, 'public://' . $filename, \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);

    if ($file) {
        $media = Media::create([
            'bundle' => 'image',
            'uid' => 1,
            'field_media_image' => [
                'target_id' => $file->id(),
                'alt' => $name,
            ],
            'name' => $name,
        ]);
        $media->save();
        return $media->id();
    }
    return NULL;
}

/**
 * Helper to create a placeholder Media Document.
 */
function create_media_document($name)
{
    $data = "Dummy document content for $name";
    $filename = 'doc_' . strtolower(str_replace(' ', '_', $name)) . '.txt';
    /** @var \Drupal\file\FileRepositoryInterface $file_repository */
    $file_repository = \Drupal::service('file.repository');
    $file = $file_repository->writeData($data, 'public://' . $filename, \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);

    if ($file) {
        $media = Media::create([
            'bundle' => 'document',
            'uid' => 1,
            'field_media_document' => [
                'target_id' => $file->id(),
            ],
            'name' => $name,
        ]);
        $media->save();
        return $media->id();
    }
    return NULL;
}

/**
 * Helper to create a placeholder Media Remote Video.
 */
function create_media_video($name, $url)
{
    $media = Media::create([
        'bundle' => 'remote_video',
        'uid' => 1,
        'field_media_oembed_video' => $url,
        'name' => $name,
    ]);
    $media->save();
    return $media->id();
}

echo "Generating media placeholders...\n";
$img_id = create_media_image('JSSO Header Image');
$doc_id = create_media_document('Policy Brief 2026');
$vid_id = create_media_video('AU Summit Overview', 'https://www.youtube.com/watch?v=L7J37H3Ww7Q');

// 1. Partners
echo "Generating Partners...\n";
$partners = [
    ['name' => 'African Union Commission', 'url' => 'https://au.int'],
    ['name' => 'United Nations Economic Commission for Africa', 'url' => 'https://uneca.org'],
    ['name' => 'African Development Bank', 'url' => 'https://afdb.org'],
    ['name' => 'European Union', 'url' => 'https://european-union.europa.eu'],
    ['name' => 'AUDA-NEPAD', 'url' => 'https://nepad.org'],
];
$partner_ids = [];
foreach ($partners as $p) {
    $node = Node::create([
        'type' => 'partner',
        'title' => $p['name'],
        'field_website' => ['uri' => $p['url']],
        'field_media_image' => $img_id,
        'uid' => 1,
    ]);
    $node->save();
    $partner_ids[] = $node->id();
}

// 2. TWGs
echo "Generating TWGs...\n";
$twgs = ['Infrastructure & Energy', 'Gender & Civil Society', 'Regional Integration & AfCFTA'];
foreach ($twgs as $name) {
    $node = Node::create([
        'type' => 'twg',
        'title' => $name,
        'field_theme' => get_random_tids('thematic_areas', 1),
        'field_chair' => 1,
        'field_members' => [1],
        'uid' => 1,
    ]);
    $node->save();
}

// 3. Projects
echo "Generating Projects...\n";
$projects = [
    ['title' => 'Trans-African Highway Network Evolution', 'loc' => 'POINT(38.74 9.03)', 'budget' => 45000000], // Addis
    ['title' => 'Great Green Wall Initiative Support', 'loc' => 'POINT(2.11 13.51)', 'budget' => 12000000], // Niamey
    ['title' => 'AfCFTA Digital Trade Hub', 'loc' => 'POINT(3.37 6.52)', 'budget' => 8500000], // Lagos
    ['title' => 'Grand Inga Hydropower Phase A', 'loc' => 'POINT(15.31 -4.32)', 'budget' => 50000000], // Kinshasa
    ['title' => 'Solar Power Africa - Desert to Power', 'loc' => 'POINT(31.23 30.04)', 'budget' => 25000000], // Cairo
];
foreach ($projects as $p) {
    $node = Node::create([
        'type' => 'project',
        'title' => $p['title'],
        'field_geofield' => $p['loc'],
        'field_budget' => $p['budget'],
        'field_status' => get_random_tids('project_status', 1),
        'field_frameworks' => get_random_tids('strategic_frameworks', 2),
        'field_theme' => get_random_tids('thematic_areas', 1),
        'field_date_range' => [
            'value' => date('Y-m-d\TH:i:s', strtotime('now')),
            'end_value' => date('Y-m-d\TH:i:s', strtotime('+2 years')),
        ],
        'uid' => 1,
    ]);
    $node->save();
}

// 4. Resources
echo "Generating Resources...\n";
$resources = [
    'Policy Brief: Scaling Renewable Energy in Africa',
    'Technical Report: AfCFTA Implementation Progress 2025',
    'Strategic Framework for Digital Transformation (JSSO-26)',
    'Research Paper: Impact of Grand Inga on Regional Trade',
    'Outcome Document: 5th Annual Infrastructure Summit',
];
foreach ($resources as $res) {
    $node = Node::create([
        'type' => 'resource',
        'title' => $res,
        'field_resource_type' => get_random_tids('resource_types', 1),
        'field_media_document' => $doc_id,
        'field_theme' => get_random_tids('thematic_areas', 1),
        'field_frameworks' => get_random_tids('strategic_frameworks', 1),
        'uid' => 1,
    ]);
    $node->save();
}

// 5. News
echo "Generating News...\n";
$news = [
    'JSSO Launch: New Digital Infrastructure Fund Announced',
    'AU-EU Partnership Strengthened for Climate Resilience',
    'AfCFTA Trade Corridors: Landmark Agreement Signed in Accra',
    'JSSO Summit 2026: Ministers Convene in Addis Ababa',
    'Youth in Dev: Entrepreneurship Grant Winners Revealed',
];
foreach ($news as $n) {
    $node = Node::create([
        'type' => 'news',
        'title' => $n,
        'field_media_image' => $img_id,
        'field_tags' => get_random_tids('tags', 2),
        'uid' => 1,
    ]);
    $node->save();
}

// 6. Events
echo "Generating Events...\n";
$events = [
    ['title' => 'AU Summit 2026', 'type' => 'conference', 'start' => '+6 months'],
    ['title' => 'Regional Trade Integration Webinar', 'type' => 'webinar', 'start' => '-1 month'],
    ['title' => 'Infrastructure Finance Launch', 'type' => 'launch', 'start' => '+2 weeks'],
    ['title' => 'JSSO Annual Working Group Meeting', 'type' => 'meeting', 'start' => '+1 month'],
    ['title' => 'Climate Adaptation Workshop', 'type' => 'conference', 'start' => '+2 months'],
];
$event_ids = [];
foreach ($events as $e) {
    $start = strtotime($e['start']);
    $node = Node::create([
        'type' => 'event',
        'title' => $e['title'],
        'field_event_type' => $e['type'],
        'field_date_range' => [
            'value' => date('Y-m-d\TH:i:s', $start),
            'end_value' => date('Y-m-d\TH:i:s', $start + 3600 * 8),
        ],
        'field_location_text' => 'Hybrid / African Union HQ',
        'uid' => 1,
    ]);
    $node->save();
    $event_ids[] = $node->id();
}

// 7. Albums
echo "Generating Albums...\n";
for ($i = 1; $i <= 5; $i++) {
    $node = Node::create([
        'type' => 'album',
        'title' => "Photo Highlights: " . $events[array_rand($events)]['title'],
        'field_gallery_images' => [$img_id],
        'field_related_event' => $event_ids[array_rand($event_ids)],
        'uid' => 1,
    ]);
    $node->save();
}

// 8. Videos
echo "Generating Videos...\n";
$videos = [
    'Understanding AfCFTA: The Future of Intra-African Trade',
    'Grand Inga Dam: Powering the African Continent',
    'Digital Africa: JSSO Infrastructure Overview',
    'AU Summit Highlights: Agenda 2063 in Focus',
    'JSSO Partner Spotlight: AUDA-NEPAD Interview',
];
foreach ($videos as $v) {
    $node = Node::create([
        'type' => 'video',
        'title' => $v,
        'field_media_video' => $vid_id,
        'field_tags' => get_random_tids('tags', 1),
        'uid' => 1,
    ]);
    $node->save();
}

echo "Dummy data population completed!\n";
