<?php

/**
 * @file
 * Programmatically create main menu links for JSSO.
 */

use Drupal\menu_link_content\Entity\MenuLinkContent;

$links = [
    'Home' => [
        'uri' => 'internal:/',
        'weight' => 0,
    ],
    'Projects' => [
        'uri' => 'internal:/projects',
        'weight' => 10,
    ],
    'Knowledge Hub' => [
        'uri' => 'internal:/knowledge-hub',
        'weight' => 20,
    ],
    'News' => [
        'uri' => 'internal:/news',
        'weight' => 30,
    ],
    'Events' => [
        'uri' => 'internal:/events',
        'weight' => 40,
    ],
    'Media' => [
        'uri' => 'internal:/media/photos',
        'weight' => 50,
    ],
    'Partners' => [
        'uri' => 'internal:/partners',
        'weight' => 60,
    ],
];

foreach ($links as $title => $data) {
    // Check if link already exists.
    $existing = \Drupal::entityTypeManager()->getStorage('menu_link_content')->loadByProperties([
        'menu_name' => 'main',
        'title' => $title,
    ]);

    if (empty($existing)) {
        $menu_link = MenuLinkContent::create([
            'title' => $title,
            'link' => ['uri' => $data['uri']],
            'menu_name' => 'main',
            'expanded' => TRUE,
            'weight' => $data['weight'],
        ]);
        $menu_link->save();
        echo "Created menu link: $title\n";
    } else {
        echo "Menu link already exists: $title\n";
    }
}
