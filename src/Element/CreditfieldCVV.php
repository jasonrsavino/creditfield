<?php

/**
 * @file
 * Contains \Drupal\creditfield\Element\CreditfieldCVV.
 */

namespace Drupal\creditfield\Element;

use \Drupal\Core\Render\Element\FormElement;
use \Drupal\Core\Render\Element;
use \Drupal\Core\Form\FormStateInterface;

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
        array($class, 'validateCVV')
      ),
      '#process' => array(
        array($class, 'processCVV'),
      ),
      '#pre_render' => array(
        array($class, 'preRenderCVV'),
      ),
      '#theme' => 'input__textfield',
      '#theme_wrappers' => array('form_element'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function processCVV(&$element, FormStateInterface $form_state, &$complete_form) {
    return $element;
  }

  /**
   * Validate callback for credit card number fields.
   * Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org
   * @param array $element
   */
  public static function validateCVV(&$element, FormStateInterface $form_state, &$complete_form) {
    if (!is_numeric($element['#value'])) {
      $form_state->setError($element, t('Please enter a valid CVV number.'));
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

  /**
   * Prepares a #type 'creditfield_cvv' render element for input.html.twig.
   *
   * @param array $element
   *   An associative array containing the properties of the element.
   *   Properties used: #title, #value, #description, #size, #maxlength,
   *   #placeholder, #required, #attributes.
   *
   * @return array
   *   The $element with prepared variables ready for input.html.twig.
   */
  public static function preRenderCVV($element) {
    $element['#attributes']['type'] = 'text';
    Element::setAttributes($element, array('id', 'name', 'value', 'size', 'maxlength', 'placeholder'));
    static::setAttributes($element, array('form-text'));

    return $element;
  }
}