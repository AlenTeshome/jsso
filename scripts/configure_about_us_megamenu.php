<?php

/**
 * Configure TB Mega Menu for 'About Us' into 4 columns side by side.
 */

use Drupal\menu_link_content\Entity\MenuLinkContent;

$storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');

// 1. Find 'About Us' parent menu item
$about_items = $storage->loadByProperties(['menu_name' => 'main', 'title' => 'About Us']);
if (empty($about_items)) {
    die("About Us menu item not found!\n");
}
$about_item = reset($about_items);
$about_uuid = 'menu_link_content:' . $about_item->uuid();

echo "About Us UUID: $about_uuid\n";

// 2. Find the 4 category items under 'About Us'
$categories = ['Commission', 'Secretariat', 'Collaborate', 'Get in touch'];
$cat_uuids = [];

foreach ($categories as $cat_title) {
    $items = $storage->loadByProperties([
        'menu_name' => 'main',
        'title' => $cat_title,
    ]);
    if (!empty($items)) {
        $item = reset($items);
        $cat_uuids[$cat_title] = 'menu_link_content:' . $item->uuid();
        echo "Category '$cat_title' UUID: " . $cat_uuids[$cat_title] . "\n";
    }
}

// 3. Load active TB Mega Menu configuration
$config_factory = \Drupal::configFactory();
$tb_config_name = 'tb_megamenu.theme.jssotheme.main';
$config = $config_factory->getEditable($tb_config_name);

if (!$config || empty($config->get())) {
    // Try fallback theme config if jssotheme not found
    $config_names = $config_factory->listAll('tb_megamenu');
    if (!empty($config_names)) {
        $tb_config_name = reset($config_names);
        $config = $config_factory->getEditable($tb_config_name);
    }
}

echo "Updating config: $tb_config_name\n";

$menu_config = json_decode($config->get('menu_config'), TRUE);

// 4. Construct 4-column row for 'About Us'
$columns = [];

foreach ($categories as $cat_title) {
    if (isset($cat_uuids[$cat_title])) {
        $cat_uuid = $cat_uuids[$cat_title];
        
        // Ensure category item itself is configured as a group header in TB Mega Menu
        $menu_config[$cat_uuid] = [
            'rows_content' => [],
            'submenu_config' => [
                'width' => '',
                'class' => '',
                'group' => '1',
            ],
            'item_config' => [
                'class' => '',
                'xicon' => '',
                'caption' => '',
                'alignsub' => '',
                'group' => '1',
                'hidewcol' => '0',
                'hidesub' => '0',
                'label' => '',
            ],
        ];

        // Add to columns array (width = 3 for 4 equal columns in Bootstrap 12-col grid)
        $columns[] = [
            'col_content' => [
                [
                    'plugin_id' => $cat_uuid,
                    'type' => 'menu_item',
                    'tb_item_config' => [],
                    'weight' => '0',
                ],
            ],
            'col_config' => [
                'width' => '3',
                'class' => '',
                'hidewcol' => '0',
                'showblocktitle' => '1',
            ],
        ];
    }
}

// Set 'About Us' configuration with 1 row containing 4 columns (width 3 each)
$menu_config[$about_uuid] = [
    'rows_content' => [
        $columns,
    ],
    'submenu_config' => [
        'width' => '',
        'class' => 'mega-menu-4col',
        'group' => '0',
    ],
    'item_config' => [
        'class' => '',
        'xicon' => '',
        'caption' => '',
        'alignsub' => '',
        'group' => '0',
        'hidewcol' => '0',
        'hidesub' => '0',
        'label' => '',
    ],
];

// Save updated TB Mega Menu JSON config
$config->set('menu_config', json_encode($menu_config, JSON_PRETTY_PRINT));
$config->save();

echo "TB Mega Menu 4-column configuration saved successfully!\n";
