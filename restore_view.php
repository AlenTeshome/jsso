<?php
$yaml = file_get_contents('/var/www/html/knowledge_hub_view.yml');
$data = \Drupal\Component\Serialization\Yaml::decode($yaml);

$config = \Drupal::configFactory()->getEditable('views.view.jsso_knowledge_hub');
$config->setData($data)->save();
echo "Restored view.\n";
