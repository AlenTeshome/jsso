<?php
use Drupal\block\Entity\Block;

$block_id = 'newsletter_subscription_block';
$theme = 'jssotheme';

if (Block::load($block_id)) {
    echo "Block $block_id already exists.\n";
    exit;
}

$block = Block::create([
    'id' => $block_id,
    'theme' => $theme,
    'weight' => 0,
    'status' => 1,
    'region' => 'content',
    'plugin' => 'webform_block',
    'settings' => [
        'id' => 'webform_block',
        'label' => 'Newsletter Subscription Block',
        'provider' => 'webform',
        'label_display' => '0',
        'webform_id' => 'newsletter_subscription',
    ],
]);
$block->save();
echo "Block $block_id created successfully in 'content' region.\n";
