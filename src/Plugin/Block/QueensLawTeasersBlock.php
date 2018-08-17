<?php

namespace Drupal\queenslaw_teasers\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Entity\Menu;

/**
 * Provides a Teasers block.
 *
 * @Block(
 *   id = "queenslaw_teasers",
 *   admin_label = @Translation("Teasers"),
 * )
 */
class QueensLawTeasersBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();
    if (!isset($config['menu'])) $config['menu'] = [];
    $menus = Menu::loadMultiple();
    $menu_options = [];
    foreach ($menus as $id => $menu) $menu_options[$id] = $menu->label();
    $form['menu'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Menu(s)'),
      '#description' => $this->t('When the block appears on the front page, it will show all top level items in the selected menu(s).'),
      '#default_value' => $config['menu'],
      '#options' => $menu_options,
      '#multiple' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['menu'] = $values['menu'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $return = [
      '#cache' => [
        'max-age' => 0,
        'contexts' => [],
        'tags' => [],
      ],
    ];
    $config = $this->getConfiguration();
    if (!isset($config['menu'])) $config['menu'] = [];
    // use #children instead of #markup to avoid having the "style" attribute stripped
    if ($markup = _queenslaw_teasers_block_content($config['menu'])) $return['#children'] = $markup;
    return $return;
  }

}
?>
