<?php

namespace Drupal\pcx_orgs\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\pcx_orgs\Entity\OrganizationInterface;

/**
 * Class OrganizationController.
 *
 *  Returns responses for Organization routes.
 */
class OrganizationController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Organization  revision.
   *
   * @param int $organization_revision
   *   The Organization  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($organization_revision) {

  }

  /**
   * Page title callback for a Organization  revision.
   *
   * @param int $organization_revision
   *   The Organization  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($organization_revision) {
    $organization = $this->entityManager()->getStorage('organization')->loadRevision($organization_revision);
    return $this->t('Revision of %title from %date', ['%title' => $organization->label(), '%date' => format_date($organization->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Organization .
   *
   * @param \Drupal\pcx_orgs\Entity\OrganizationInterface $organization
   *   A Organization  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(OrganizationInterface $organization) {
    $account = $this->currentUser();
    $langcode = $organization->language()->getId();
    $langname = $organization->language()->getName();
    $languages = $organization->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $organization_storage = $this->entityManager()->getStorage('organization');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $organization->label()]) : $this->t('Revisions for %title', ['%title' => $organization->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all organization revisions") || $account->hasPermission('administer organization entities')));
    $delete_permission = (($account->hasPermission("delete all organization revisions") || $account->hasPermission('administer organization entities')));

    $rows = [];

    $vids = $organization_storage->revisionIds($organization);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\pcx_orgs\OrganizationInterface $revision */
      $revision = $organization_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $organization->getRevisionId()) {
          $link = $this->l($date, new Url('entity.organization.revision', ['organization' => $organization->id(), 'organization_revision' => $vid]));
        }
        else {
          $link = $organization->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.organization.translation_revert', ['organization' => $organization->id(), 'organization_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.organization.revision_revert', ['organization' => $organization->id(), 'organization_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.organization.revision_delete', ['organization' => $organization->id(), 'organization_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['organization_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
