<?php
$webform = \Drupal\webform\Entity\Webform::load('newsletter_subscription');
echo $webform ? 'EXISTS' : 'MISSING';
