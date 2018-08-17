<?php

namespace Drupal\queenslaw_teasers\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a form for storing teaser settings.
 *
 * @ingroup queenslaw_teasers
 */
class QueensLawTeasersAdminForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'queenslaw_teasers_admin';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'queenslaw_teasers.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $config = $this->config('queenslaw_teasers.settings');
    $form['paths'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Alternate teaser pages'),
      '#description' => $this->t('Pages on which the alternate teaser style should appear.'),
      '#default_value' => $config->get('paths'),
      '#required' => FALSE,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    foreach ($values as $key => $value) $this->config('queenslaw_teasers.settings')->set($key, $value)->save();
    drupal_flush_all_caches();
    drupal_set_message($this->t('The configuration was updated.'));
  }

}
