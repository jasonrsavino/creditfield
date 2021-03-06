<?php

namespace Drupal\creditfield\Element;

use \Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\Render\Element\FormElement;
use \Drupal\Core\Render\Element;

/**
 * Provides a one-line credit card number field form element.
 *
 * @FormElement("creditfield_expiration")
 */
class CardExpiration extends FormElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);

    return [
      '#input' => TRUE,
      '#element_validate' => [
        [$class, 'validateCardExpiration']
      ],
      '#process' => [
        [$class, 'processCardExpiration'],
      ],
      '#pre_render' => [
        [$class, 'preRenderCardExpiration'],
      ],
      '#theme' => 'input__date',
      '#theme_wrappers' => ['form_element'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function processCardExpiration(&$element, FormStateInterface $form_state, &$complete_form) {
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function validateCardExpiration(&$element, FormStateInterface $form_state, &$complete_form) {
    if (!static::dateIsValid($element['#value'])) {
      $form_state->setError($element, t('Please enter a valid expiration date.'));
    }
  }

  /**
   * Adds form-specific attributes to a 'creditfield_expiration' #type element.
   *
   * Supports HTML5 types of 'date', 'datetime', 'datetime-local', and 'time'.
   * Falls back to a plain textfield. Used as a sub-element by the datetime
   * element type.
   *
   * @param array $element
   *   An associative array containing the properties of the element.
   *   Properties used: #title, #value, #options, #description, #required,
   *   #attributes, #id, #name, #type, #min, #max, #step, #value, #size.
   *
   * Note: The input "name" attribute needs to be sanitized before output, which
   *       is currently done by initializing Drupal\Core\Template\Attribute with
   *       all the attributes.
   *
   * @return array
   *   The $element with prepared variables ready for #theme 'input__date'.
   */
  public static function preRenderCardExpiration($element) {
    $element['#attributes']['type'] = 'month';
    Element::setAttributes($element, ['id', 'name', 'type', 'min', 'max', 'step', 'value', 'size']);
    static::setAttributes($element, ['form-' . $element['#attributes']['type']]);

    return $element;
  }

  /**
   * Simple date check to determine if the expiration date is in the future from right now.
   * @param $value
   * @return bool
   */
  public static function dateIsValid($value) {
    if (!mb_strlen($value)) {
      return FALSE;
    }

    $dateparts = explode('-', $value);
    $year = (int) $dateparts[0];
    $month = (int) $dateparts[1];

    if ($month > 12) {
      return FALSE;
    }

    if ($year < date('Y') || !is_integer($year)) {
      return FALSE;
    }

    if ($year == date('Y') && $month < date('m')) {
      return FALSE;
    }

    return TRUE;
  }
}
