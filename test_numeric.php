<?php
use Drupal\views\Views;
\ = Views::getView('jsso_knowledge_hub');
\->setDisplay('block_3');
\->setExposedInput(['field_realated_focus_area_target_id' => ['1', '2']]);
\->build();
print_r(\->query->where);
