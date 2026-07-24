<?php

/**
 * @file
 * Script to create all 14 About Us pages as 'page' content type nodes
 * and set up their main menu structure in Drupal.
 */

use Drupal\node\Entity\Node;
use Drupal\menu_link_content\Entity\MenuLinkContent;

$pages_data = [
    // Main Parent Page
    [
        'title' => 'About Us',
        'subtitle' => 'Promoting Sustainable & Inclusive Development in the Region',
        'path' => '/about',
        'category' => 'Main',
        'callout' => 'Connecting regional priorities with global commitments to foster sustainable socio-economic growth.',
        'body' => '<p>Welcome to the About Us section. Our mandate is to foster economic and social integration, support national development priorities, and drive progress toward the Sustainable Development Goals (SDGs).</p><h2>Core Pillars</h2><ul><li><strong>Policy Analysis & Research:</strong> Evidence-based analysis to inform regional policymaking.</li><li><strong>Intergovernmental Dialogue:</strong> Convening member states to address shared regional challenges.</li><li><strong>Technical Assistance:</strong> Hands-on capacity building and advisory support.</li></ul>',
    ],
    // Commission Section
    [
        'title' => 'Member States',
        'subtitle' => '20 Member Countries Working Towards Shared Regional Goals',
        'path' => '/about/member-states',
        'category' => 'Commission',
        'callout' => 'Representing a diverse network of member countries dedicated to regional cooperation.',
        'body' => '<p>The Commission comprises 20 member states across the region, bringing together governments to collaborate on economic stability, social integration, and sustainable resource management.</p><h3>Member State Collaboration</h3><p>Through annual sessions and ministerial meetings, member countries define key strategic priorities and joint initiatives for regional impact.</p>',
    ],
    [
        'title' => 'Vision, mission & history',
        'subtitle' => 'Fostering Regional Integration and Sustainable Development Since 1973',
        'path' => '/about/mission',
        'category' => 'Commission',
        'callout' => 'Established in 1973 to stimulate economic activity and strengthen regional ties.',
        'body' => '<h2>Our Vision</h2><p>An integrated, resilient, and prosperous region where sustainable development benefits all citizens.</p><h2>Our Mission</h2><p>To promote inclusive socio-economic growth, support evidence-based policy, and champion regional cooperation.</p>',
    ],
    [
        'title' => 'Governing bodies',
        'subtitle' => 'Intergovernmental Committees and Decision-Making Bodies',
        'path' => '/about/governing',
        'category' => 'Commission',
        'callout' => 'Guiding strategic direction and oversight through specialized committees.',
        'body' => '<p>Our governing structure includes ministerial sessions and specialized subsidiary committees focusing on key sectors such as energy, statistics, social development, and trade.</p>',
    ],

    // Secretariat Section
    [
        'title' => 'Leadership',
        'subtitle' => 'Executive Leadership & Strategic Direction',
        'path' => '/about/leadership',
        'category' => 'Secretariat',
        'callout' => 'Led by the Executive Secretary to ensure impactful regional governance.',
        'body' => '<p>The Secretariat is headed by the Executive Secretary, guiding our strategic initiatives, policy research, and collaborative programs across all member countries.</p>',
    ],
    [
        'title' => 'Thematic Clusters',
        'subtitle' => 'Core Areas of Expertise and Interdisciplinary Pillars',
        'path' => '/clusters',
        'category' => 'Secretariat',
        'callout' => 'Organized around specialized technical clusters to address complex regional issues.',
        'body' => '<p>Our operational framework is structured into thematic clusters, covering macroeconomic policy, social development, climate change, governance, gender equality, and statistics.</p>',
    ],
    [
        'title' => 'Strategy, planning & accountability',
        'subtitle' => 'Results-Based Management and Strategic Frameworks',
        'path' => '/about/strategy',
        'category' => 'Secretariat',
        'callout' => 'Ensuring transparency, measurable impact, and alignment with UN strategic standards.',
        'body' => '<p>We implement rigorous results-based management frameworks to monitor program effectiveness, evaluate outcomes, and uphold institutional accountability.</p>',
    ],
    [
        'title' => 'External relations & communications',
        'subtitle' => 'Global Outreach, Advocacy, and Stakeholder Engagement',
        'path' => '/about/external',
        'category' => 'Secretariat',
        'callout' => 'Amplifying regional perspectives on global platforms and fostering transparent public communication.',
        'body' => '<p>The Communications and External Relations team coordinates global media outreach, publication distribution, and multi-stakeholder advocacy initiatives.</p>',
    ],
    [
        'title' => 'Resource management & service development',
        'subtitle' => 'Operational Excellence and Capacity Building',
        'path' => '/about/services',
        'category' => 'Secretariat',
        'callout' => 'Providing robust administrative, digital, and operational infrastructure.',
        'body' => '<p>Resource Management oversees institutional operations, human resources, financial stewardship, and digital technology solutions supporting overall mandate delivery.</p>',
    ],

    // Collaborate Section
    [
        'title' => 'Technical cooperation',
        'subtitle' => 'Advisory Services and Capacity Development Programs',
        'path' => '/about/technical-cooperation',
        'category' => 'Collaborate',
        'callout' => 'Delivering tailored technical support and capacity building directly to member governments.',
        'body' => '<p>Our technical cooperation services assist member states in policy formulation, institutional strengthening, and target achievement under the 2030 Agenda.</p>',
    ],
    [
        'title' => 'Donors',
        'subtitle' => 'Funding Partners Supporting Regional Growth',
        'path' => '/about/donors',
        'category' => 'Collaborate',
        'callout' => 'Partnering with international donors and multilateral funds to implement high-impact projects.',
        'body' => '<p>Generous support from donor governments, funds, and international financial institutions enables the scaling of flagship programs across the region.</p>',
    ],
    [
        'title' => 'Partners',
        'subtitle' => 'Strategic Coalitions with Global and Regional Entities',
        'path' => '/about/partners',
        'category' => 'Collaborate',
        'callout' => 'Working alongside UN organizations, civil society, and academia.',
        'body' => '<p>We cultivate strategic partnerships with UN system entities, regional organizations, private sector leaders, and research institutes to drive joint action.</p>',
    ],
    [
        'title' => 'Regional Collaborative Platform',
        'subtitle' => 'Unifying UN Entities for Sustainable Impact',
        'path' => '/about/rcp',
        'category' => 'Collaborate',
        'callout' => 'Coordinating UN regional assets to deliver joint support to member states.',
        'body' => '<p>The Regional Collaborative Platform (RCP) brings together all UN agencies operating in the region to address transboundary development challenges collaboratively.</p>',
    ],

    // Get in touch Section
    [
        'title' => 'Work with us',
        'subtitle' => 'Careers, Consultancies, and Fellowships',
        'path' => '/about/jobs',
        'category' => 'Get in touch',
        'callout' => 'Explore professional opportunities, consultancies, and internship programs.',
        'body' => '<p>Join our team of dedicated professionals and experts committed to advancing sustainable development. Discover open vacancies and consultancy opportunities.</p>',
    ],
    [
        'title' => 'Contact us',
        'subtitle' => 'Get in Touch with Our Regional Headquarters',
        'path' => '/contact',
        'category' => 'Get in touch',
        'callout' => 'Reach out to our offices for inquiries, partnerships, or media requests.',
        'body' => '<p>We welcome your inquiries. Contact our headquarters or reach out directly to relevant departments for information and collaboration.</p>',
    ],
];

echo "Creating 'page' nodes...\n";

$created_nodes = [];

foreach ($pages_data as $data) {
    // Check if path alias or title exists
    $alias = $data['path'];
    
    // Check if node exists by path or title
    $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
    $nids = $query->condition('type', 'page')
        ->condition('title', $data['title'])
        ->accessCheck(FALSE)
        ->execute();

    if (!empty($nids)) {
        $node = Node::load(reset($nids));
        echo "Updating existing node: {$data['title']} (NID: {$node->id()})\n";
    } else {
        $node = Node::create([
            'type' => 'page',
            'title' => $data['title'],
            'status' => 1,
            'uid' => 1,
        ]);
    }

    $node->set('field_subtitle', $data['subtitle']);
    $node->set('field_callout', $data['callout']);
    $node->set('body', [
        'value' => $data['body'],
        'format' => 'full_html',
    ]);
    $node->set('path', ['alias' => $data['path']]);
    $node->save();

    $created_nodes[$data['title']] = [
        'nid' => $node->id(),
        'path' => $data['path'],
        'category' => $data['category'],
    ];

    echo "Saved node: {$data['title']} -> {$data['path']} (NID: {$node->id()})\n";
}

echo "\nSetting up menu structure...\n";

// 1. Ensure 'About Us' parent menu link exists
$parent_storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');

$about_menu_links = $parent_storage->loadByProperties([
    'menu_name' => 'main',
    'title' => 'About Us',
]);

if (!empty($about_menu_links)) {
    $about_link = reset($about_menu_links);
    echo "Found existing main parent menu link: About Us\n";
} else {
    $about_link = MenuLinkContent::create([
        'title' => 'About Us',
        'link' => ['uri' => 'internal:/about'],
        'menu_name' => 'main',
        'expanded' => TRUE,
        'weight' => 5,
    ]);
    $about_link->save();
    echo "Created main parent menu link: About Us\n";
}

$about_link_id = 'menu_link_content:' . $about_link->uuid();

// 2. Create sub-category menu links under 'About Us'
$categories = [
    'Commission' => 10,
    'Secretariat' => 20,
    'Collaborate' => 30,
    'Get in touch' => 40,
];

$category_menu_parents = [];

foreach ($categories as $cat_title => $weight) {
    $cat_links = $parent_storage->loadByProperties([
        'menu_name' => 'main',
        'title' => $cat_title,
        'parent' => $about_link_id,
    ]);

    if (!empty($cat_links)) {
        $cat_link = reset($cat_links);
    } else {
        $cat_link = MenuLinkContent::create([
            'title' => $cat_title,
            'link' => ['uri' => 'internal:/about'],
            'menu_name' => 'main',
            'parent' => $about_link_id,
            'expanded' => TRUE,
            'weight' => $weight,
        ]);
        $cat_link->save();
        echo "Created category header menu link: $cat_title\n";
    }
    $category_menu_parents[$cat_title] = 'menu_link_content:' . $cat_link->uuid();
}

// 3. Attach individual page menu links under their categories
$item_weight = 0;
foreach ($pages_data as $data) {
    if ($data['category'] === 'Main') {
        continue;
    }
    $item_weight += 5;
    $cat_name = $data['category'];
    $parent_id = isset($category_menu_parents[$cat_name]) ? $category_menu_parents[$cat_name] : $about_link_id;

    $existing_item = $parent_storage->loadByProperties([
        'menu_name' => 'main',
        'title' => $data['title'],
        'parent' => $parent_id,
    ]);

    if (empty($existing_item)) {
        $menu_item = MenuLinkContent::create([
            'title' => $data['title'],
            'link' => ['uri' => 'internal:' . $data['path']],
            'menu_name' => 'main',
            'parent' => $parent_id,
            'expanded' => FALSE,
            'weight' => $item_weight,
        ]);
        $menu_item->save();
        echo "Created sub-menu item: {$data['title']} under $cat_name\n";
    } else {
        echo "Sub-menu item already exists: {$data['title']}\n";
    }
}

echo "\nAll 14 pages and menu hierarchy created successfully!\n";
