<?php
use Drupal\webform\Entity\Webform;

$elements = <<<'EOD'
email:
  '#type': email
  '#title': Email
  '#placeholder': 'Enter your email address'
  '#required': true
  '#attributes':
    class:
      - newsletter-email-field
first_name:
  '#type': textfield
  '#title': 'First Name'
EOD;

$webform = Webform::create([
    'id' => 'newsletter_subscription',
    'title' => 'Newsletter Subscription',
    'description' => 'Stay informed about our latest publications, events and announcements.',
    'elements' => $elements,
    'settings' => [
        'confirmation_type' => 'message',
        'confirmation_message' => 'Thank you for subscribing. You will receive updates from us soon.',
        'submit_label' => 'Subscribe',
        'form_attributes' => [
            'class' => ['newsletter-form'],
        ],
        'submit_attributes' => [
            'class' => ['newsletter-submit-button'],
        ],
    ],
]);
$webform->save();
echo "Webform created successfully.\n";
