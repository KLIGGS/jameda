<?php

namespace Drupal\jameda\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\user;
use Drupal\Core\Link;
use Drupal\Core\Render\Element;
use Drupal\Core\Path\PathValidator;


class JamedaSettingsForm extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'jameda.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'jameda_settings';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
  
  	// Form constructor.
    $form = parent::buildForm($form, $form_state);
    
    $config = $this->config(static::SETTINGS);

	// HTML5 URL Element best practice https://www.drupal.org/docs/drupal-apis/form-api/form-render-elements
    $form['jameda_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Jameda API URL'),
      '#description' => $this->t('Enter URL with trailing slash to API like: https://www.jameda.de/api/otb-widget/'),
      '#default_value' => $config->get('jameda_url'),
    ];  

    $form['jameda_ref_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Jameda API Key'),
      '#description' => $this->t('Enter API Key of jameda.de'),
       '#default_value' => $config->get('jameda_ref_id'),

    ];

    
    $form['advanced_settings'] = [
      '#type' => 'vertical_tabs',
    ];
    
    
   $form['advanced'] = [
      '#type' => 'details',
      '#title' => $this->t('Advanced'),
      '#group' => 'additional_settings',
      '#description' => $this->t('Configure where the API Script should appear.'),
    ];


	$form['advanced']['jameda_js_scope'] = [
		  '#type' => 'radios',
		  '#title' => $this->t('Location to add the API Integration script'),
		  '#description' => $this->t('Controls where on the page the tracking script is added. Default is footer'),
		  '#default_value' => $config->get('jameda_js_scope'),
		  '#options' => [
			'header' => $this->t('Header'),
			'footer' => $this->t('Footer <em>(recommended)</em>'),
		  ],
		];
    
    
    $roles = array_map(['\\Drupal\\Component\\Utility\\Html',
						  'escape',
						  ],
						  user_role_names(TRUE));
    
     $form['advanced']['jameda_roles_excluded'] = [
      '#type' => 'checkboxes',
      '#options' => $roles,
      '#title' => $this->t('Excluded roles (optional)'),
      '#description' => $this->t('You can control which visits and clicks are tracked in Jameda Video Chat Script by excluding roles.'),
      '#default_value' => $config->get('jameda_roles_excluded'),
    ];

    $form['advanced']['jameda_paths'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Enter path with leading slash "/" where external API Script shall be included'),
      '#default_value' => $config->get('jameda_paths'),
      '#description' => $this->t('Limit external API Script to match GDPR. Inform your visitors and get consent. Jameda external Video Chat API Script will may track users of your domain.'),
      '#cols' => 100,
      '#rows' => 5,
      '#resizable' => FALSE,
      '#required' => FALSE,
      '#weight' => 40,
    ];
    

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
  
    $config = $this->config('jameda.settings');
   
      $config->set('jameda_url', $form_state->getValue('jameda_url'))
      ->set('jameda_ref_id', $form_state->getValue('jameda_ref_id'))
       ->set('jameda_paths', $form_state->getValue('jameda_paths'))
       ->set('jameda_js_scope', $form_state->getValue('jameda_js_scope'))
       ->set('jameda_roles_excluded', $form_state->getValue('jameda_roles_excluded'));
       
      $config->save();

    parent::submitForm($form, $form_state);
  }

}
