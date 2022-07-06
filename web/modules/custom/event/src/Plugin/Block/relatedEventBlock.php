<?php

namespace Drupal\event\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Provides related event block.
 *
 * @Block(
 *   id = "related_event_block",
 *   admin_label = @Translation("Related event block")
 * )
 */
 class relatedEventBlock extends BlockBase implements ContainerFactoryPluginInterface {

   /**
    * The current route match.
    *
    * @var \Drupal\Core\Routing\RouteMatchInterface
    */
   protected $currentRouteMatch;

   /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
   protected $entityTypeManager;

   /**
    * @param array $configuration
    * @param string $plugin_id
    * @param mixed $plugin_definition
    * @param \Drupal\Core\Routing\RouteMatchInterface $current_route_match
    * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
    */
   public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $current_route_match, EntityTypeManager $entity_type_manager) {
     parent::__construct($configuration, $plugin_id, $plugin_definition);
     $this->currentRouteMatch = $current_route_match;
     $this->entityTypeManager = $entity_type_manager;
   }

   /**
    * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
    * @param array $configuration
    * @param string $plugin_id
    * @param mixed $plugin_definition
    *
    * @return static
    */
   public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
     return new static(
       $configuration,
       $plugin_id,
       $plugin_definition,
       $container->get('current_route_match'),
       $container->get('entity_type.manager')
     );
   }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $currentNode = $this->currentRouteMatch->getParameter('node');
    $taxoTermId = $currentNode->get('field_event_type')->getValue()[0]["target_id"];
    $relatedNodes = $this->entityTypeManager->getStorage('node')->loadByProperties([
      'field_event_type' => $taxoTermId,
    ]);
    unset($relatedNodes[$currentNode->id()]);

    if (!empty($relatedNodes)) {
      $firstNodeFound = $relatedNodes[array_rand($relatedNodes, 1)];
      $found = true;
      $event_title = $firstNodeFound->get('title')->getValue()[0]["value"];
      $event_path = $firstNodeFound->toUrl()->toString();
    }
    else {
      $found = false;
      $event_title = "";
      $event_path = "";
    }

    return [
      '#theme' => 'related_event_block',
      '#event_title' => $event_title,
      '#event_path' => $event_path,
      '#found' => $found,
    ];
  }

  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
