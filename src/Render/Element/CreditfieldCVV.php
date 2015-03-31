<?php

/**
 * @file
 * Contains \Drupal\Creditfield\Render\Element\CreditfieldCVV.
 */

namespace Drupal\Creditfield\Render\Element;

use \Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\Render\Element;
use \Drupal\Component\Utility\Unicode as Unicode;

/**
 * Provides a one-line credit card number field form element.
 *
 * @FormElement("creditfield_cvv")
 */
class CreditfieldCVV extends FormElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);

    return array(
      '#input' => TRUE,
      '#maxlength' => 4,
      '#autocomplete_route_name' => FALSE,
      '#element_validate' => array(
        array($class, 'validateCreditfieldCVV')
      ),
      '#process' => array(
        array($class, 'processCreditfieldCVV'),
      ),
      '#pre_render' => array(
        array($class, 'preRenderTextfield'),
        array($class, 'preRenderGroup'),
      ),
      '#theme' => 'input__textfield',
      '#theme_wrappers' => array('form_element'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function processCreditfieldCVV(&$element, FormStateInterface $form_state, &$complete_form) {
    return $element;
  }

  /**
   * Validate callback for credit card number fields.
   * Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org
   * @param array $element
   */
  public static function validateCreditfieldCVV(&$element, FormStateInterface $form_state, &$complete_form) {
    if (!is_numeric($element['#value'])) {
      $form_state->form_error($element, t('Please enter a valid CVV number.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function valueCallback(&$element, $input, FormStateInterface $form_state) {
    if ($input !== FALSE && $input !== NULL) {
      // Equate $input to the form value to ensure it's marked for
      // validation.
      return str_replace(array("\r", "\n"), '', $input);
    }
  }
}