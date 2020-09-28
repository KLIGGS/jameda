<?php

namespace Drupal\jameda\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Jameda' Block.
 *
 * @Block(
 *   id = "jameda_block",
 *   admin_label = @Translation("Jameda block"),
 *   category = @Translation("Jameda"),
 * )
 */
 class JamedaBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
  
  	$jameda_ref_id = \Drupal::config('jameda.settings')->get('jameda_ref_id');
  	$jameda_url = \Drupal::config('jameda.settings')->get('jameda_url');
  
  
    return array(
      '#markup' => $this->t('<div id="jam-ota-info_' . $jameda_ref_id . '"></div><script src="' . $jameda_url . '?refid=' . $jameda_ref_id . '&amp;version=1" async></script>'),
    );
  }
}
