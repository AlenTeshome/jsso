<?php

/**
 * Inspect TB Mega Menu configuration for 'main' menu and 'jssotheme'.
 */

$config_names = \Drupal::configFactory()->listAll('tb_megamenu');
print_r($config_names);

foreach ($config_names as $name) {
    echo "=== Config: $name ===\n";
    $config = \Drupal::config($name)->get();
    print_r($config);
}
