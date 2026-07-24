<?php

/**
 * Configure TB Mega Menu for 'About Us' into 4 columns with headers and sub-links.
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

$categories = ['Commission', 'Secretariat', 'Collaborate', 'Get in touch'];

// Load active TB Mega Menu configuration
$config_factory = \Drupal::configFactory();
$tb_config_name = 'tb_megamenu.menu_config.main__jssotheme';
$config = $config_factory->getEditable($tb_config_name);

if (!$config || empty($config->get())) {
    $config_names = $config_factory->listAll('tb_megamenu');
    if (!empty($config_names)) {
        $tb_config_name = reset($config_names);
        $config = $config_factory->getEditable($tb_config_name);
    }
}

echo "Updating config: $tb_config_name\n";

$menu_config = json_decode($config->get('menu_config'), TRUE);
if (!is_array($menu_config)) {
    $menu_config = [];
}

$columns = [];

foreach ($categories as $cat_title) {
    $cat_items = $storage->loadByProperties([
        'menu_name' => 'main',
        'title' => $cat_title,
    ]);

    if (!empty($cat_items)) {
        $cat_entity = reset($cat_items);
        $cat_uuid = 'menu_link_content:' . $cat_entity->uuid();
        $cat_entity_id = 'menu_link_content:' . $cat_entity->uuid();

        // Find sub-items under this category
        $sub_items = $storage->loadByProperties([
            'menu_name' => 'main',
            'parent' => $cat_entity_id,
        ]);

        $col_items = [];

        // 1. Add Category Header Item
        $col_items[] = [
            'plugin_id' => $cat_uuid,
            'type' => 'menu_item',
            'tb_item_config' => [],
            'weight' => '-50',
        ];

        // Configure Category Item as Group Header
        $menu_config[$cat_uuid] = [
            'rows_content' => [],
            'submenu_config' => [
                'width' => '',
                'class' => '',
                'group' => '1',
            ],
            'item_config' => [
                'class' => 'mega-menu-header fw-bold text-white fs-5 mb-2',
                'xicon' => '',
                'caption' => '',
                'alignsub' => '',
                'group' => '1',
                'hidewcol' => '0',
                'hidesub' => '0',
                'label' => '',
            ],
        ];

        // 2. Add Sub-items under Category Header into the same Column
        $w = -40;
        foreach ($sub_items as $sub_entity) {
            $w++;
            $sub_uuid = 'menu_link_content:' . $sub_entity->uuid();
            $col_items[] = [
                'plugin_id' => $sub_uuid,
                'type' => 'menu_item',
                'tb_item_config' => [],
                'weight' => (string) $w,
            ];

            $menu_config[$sub_uuid] = [
                'rows_content' => [],
                'submenu_config' => [
                    'width' => '',
                    'class' => '',
                    'group' => '0',
                ],
                'item_config' => [
                    'class' => 'mega-menu-item text-white-50 hover-white py-1',
                    'xicon' => '',
                    'caption' => '',
                    'alignsub' => '',
                    'group' => '0',
                    'hidewcol' => '0',
                    'hidesub' => '0',
                    'label' => '',
                ],
            ];
        }

        // Add Column to Row (Width = 3 for 4 side-by-side columns)
        $columns[] = [
            'col_content' => $col_items,
            'col_config' => [
                'width' => '3',
                'class' => 'col-lg-3 col-md-6 mb-3',
                'hidewcol' => '0',
                'showblocktitle' => '1',
            ],
        ];
    }
}

// 5. Save About Us 4-column mega menu configuration
$menu_config[$about_uuid] = [
    'rows_content' => [
        $columns,
    ],
    'submenu_config' => [
        'width' => '100%',
        'class' => 'mega-menu-container p-4 bg-dark text-white shadow-lg',
        'group' => '0',
    ],
    'item_config' => [
        'class' => 'dropdown-mega',
        'xicon' => '',
        'caption' => '',
        'alignsub' => '',
        'group' => '0',
        'hidewcol' => '0',
        'hidesub' => '0',
        'label' => '',
    ],
];

$config->set('menu_config', json_encode($menu_config, JSON_PRETTY_PRINT));
$config->save();

echo "Full 4-column TB Mega Menu structure configured and saved!\n";
