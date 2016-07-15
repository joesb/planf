<?php
/**
 * Created by PhpStorm.
 * User: joebaker
 * Date: 22/04/2016
 * Time: 11:31
 */

namespace Drupal\plf_client\Form;

use Drupal\plf_client\ClientInterface;
use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Entity\ContentEntityStorageInterface;

class ClientAdminOverview extends FormBase {

  /**
   * The entity storage.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;


  /**
   * The client entity storage.
   *
   * @var \Drupal\Core\Entity\ContentEntityStorageInterface
   */
  protected $entityStorage;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Creates a CommentAdminOverview form.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager service.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(EntityManagerInterface $entity_manager, ContentEntityStorageInterface $entity_storage, DateFormatterInterface $date_formatter, ModuleHandlerInterface $module_handler) {
    $this->entityManager = $entity_manager;
    $this->entityStorage = $entity_storage;
    $this->dateFormatter = $date_formatter;
    $this->moduleHandler = $module_handler;
  }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager'),
      $container->get('entity.manager')->getStorage('plf_client'),
      $container->get('date.formatter'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'client_admin_overview';
  }


  /**
   * Form constructor for the client overview administration form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Build an 'Update options' form.
    $form['options'] = array(
      '#type' => 'details',
      '#title' => $this->t('Update options'),
      '#open' => TRUE,
      '#attributes' => array('class' => array('container-inline')),
    );

    $options['delete'] = $this->t('Delete the selected clients');

    $form['options']['operation'] = array(
      '#type' => 'select',
      '#title' => $this->t('Action'),
      '#title_display' => 'invisible',
      '#options' => $options,
      '#default_value' => 'publish',
    );
    $form['options']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Update'),
    );

    $header = array(
      'name' => array(
        'data' => $this->t('Name'),
        'specifier' => 'name',
      ),
      'user' => array(
        'data' => $this->t('Author'),
        'specifier' => 'user',
        'class' => array(RESPONSIVE_PRIORITY_MEDIUM),
      ),
//      'posted_in' => array(
//        'data' => $this->t('Posted in'),
//        'class' => array(RESPONSIVE_PRIORITY_LOW),
//      ),
      'changed' => array(
        'data' => $this->t('Updated'),
        'specifier' => 'changed',
        'sort' => 'desc',
        'class' => array(RESPONSIVE_PRIORITY_LOW),
      ),
      'operations' => $this->t('Operations'),
    );
    $cids = $this->entityStorage->getQuery()
      ->tableSort($header)
      ->pager(50)
      ->execute();

    /** @var $clients \Drupal\plf_client\ClientInterface[] */
    $clients = $this->entityStorage->loadMultiple($cids);

    // Build a table listing the appropriate clients.
    $options = array();
    $destination = $this->getDestinationArray();

    foreach ($clients as $client) {
      $options[$client->id()] = array(
        'name' => array(
          'data' => array(
            '#type' => 'link',
            '#title' => $client->getName(),
            '#url' => $client->urlInfo(),
          ),
        ),
        'user' => array(
          'data' => array(
            '#theme' => 'username',
            '#account' => $client->getOwner(),
          ),
        ),
        'changed' => $this->dateFormatter->format($client->getChangedTime(), 'short'),
      );
      $client_uri_options = $client->urlInfo()->getOptions() + ['query' => $destination];
      $links = array();
      $links['edit'] = array(
        'title' => $this->t('Edit'),
        'url' => $client->urlInfo('edit-form', $client_uri_options),
      );
      $options[$client->id()]['operations']['data'] = array(
        '#type' => 'operations',
        '#links' => $links,
      );
    }
    $form['clients'] = array(
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $options,
      '#empty' => $this->t('No clients available.'),
    );

    $form['pager'] = array('#type' => 'pager');

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}