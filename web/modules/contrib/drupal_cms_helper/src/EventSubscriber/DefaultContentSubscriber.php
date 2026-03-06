<?php

declare(strict_types=1);

namespace Drupal\drupal_cms_helper\EventSubscriber;

use Drupal\Core\DefaultContent\PreExportEvent;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\menu_link_content\MenuLinkContentInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Adjusts exported default content for Drupal CMS.
 *
 * @internal
 *   This is an internal part of Drupal CMS and may be changed or removed at any
 *   time without warning. External code should not interact with this class.
 */
final readonly class DefaultContentSubscriber implements EventSubscriberInterface {

  public function __construct(
    private EntityRepositoryInterface $entityRepository,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      PreExportEvent::class => 'preExport',
    ];
  }

  /**
   * Prepares to export a content entity.
   */
  public function preExport(PreExportEvent $event): void {
    // @todo Remove when https://www.drupal.org/i/3562072 is released.
    if ($event->entity instanceof MenuLinkContentInterface) {
      $parent_id = $event->entity->getParentId();
      // @see menu_link_content.links.menu.yml
      if (str_starts_with($parent_id, 'menu_link_content:')) {
        [, $uuid] = explode(':', $parent_id, 2);
        $parent = $this->entityRepository->loadEntityByUuid('menu_link_content', $uuid);
        if ($parent instanceof MenuLinkContentInterface) {
          $event->metadata->addDependency($parent);
        }
      }
    }
  }

}
