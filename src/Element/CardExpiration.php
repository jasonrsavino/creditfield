<?php

/**
 * @file
 * Contains \Drupal\creditfield\Render\CardExpiration.
 */

namespace Drupal\creditfield\Element;

use \Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\Render\Element\FormElement;
use \Drupal\Core\Render\Element;
use \Drupal\Component\Utility\Unicode as Unicode;

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

    return array(
      '#input' => TRUE,
      '#element_validate' => array(
        array($class, 'validateCreditfieldExpiration')
      ),
      '#process' => array(
        array($class, 'processCreditfieldExpiration'),
      ),
      '#pre_render' => array(
        array($class, 'preRenderDate'),
      ),
      '#theme' => 'input__date',
      '#theme_wrappers' => array('form_element'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function processCreditfieldExpiration(&$element, FormStateInterface $form_state, &$complete_form) {
    // Default to current date
    if (empty($element['#value'])) {
      $element['#value'] = array(
        'month' => format_date(REQUEST_TIME, 'custom', 'n'),
        'year' => format_date(REQUEST_TIME, 'custom', 'Y'),
      );
    }

    $element['#tree'] = TRUE;

    // Determine the order of month & year in the site's chosen date format.
    $format = variable_get('date_format_short', 'm/Y');
    $sort = array();
    $sort['month'] = max(strpos($format, 'm'), strpos($format, 'M'));
    $sort['year'] = strpos($format, 'Y');
    asort($sort);
    $order = array_keys($sort);
    $options = array();

    // Output multi-selector for date.
    foreach ($order as $type) {
      switch ($type) {
        case 'month':
          $options = drupal_map_assoc(range(1, 12), 'map_month');
          $title = t('Month');
          break;

        case 'year':
          $options = drupal_map_assoc(range(date('Y', time()), date('Y', time()) + 10));
          $title = t('Year');
          break;
      }

      $element[$type] = array(
        '#type' => 'select',
        '#title' => $title,
        '#title_display' => 'invisible',
        '#value' => $element['#value'][$type],
        '#attributes' => $element['#attributes'],
        '#options' => $options,
      );
    }

    return $element;
  }

  /**
   * Validate callback for credit card number fields.
   * Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org
   * @param array $element
   */
  public static function validateCreditfieldExpiration(&$element, FormStateInterface $form_state, &$complete_form) {
    if ($element['#value']['year'] == date('Y', time()) && $element['#value']['month'] < date('m', time())) {
      $form_state->form_error($element, t('Please enter a valid expiration date.'));
    }
  }

  /**
   * Adds form-specific attributes to a 'date' #type element.
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
  public static function preRenderDate($element) {
    if (empty($element['#attributes']['type'])) {
      $element['#attributes']['type'] = 'date';
    }
    Element::setAttributes($element, array('id', 'name', 'type', 'min', 'max', 'step', 'value', 'size'));
    static::setAttributes($element, array('form-' . $element['#attributes']['type']));

    return $element;
  }
}