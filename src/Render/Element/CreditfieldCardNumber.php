<?php

/**
 * @file
 * Contains \Drupal\Creditfield\Render\Element\CreditfieldCardnumber.
 */

namespace Drupal\Creditfield\Render\Element;

use \Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\Render\Element;
use \Drupal\Component\Utility\Unicode as Unicode;

/**
 * Provides a one-line credit card number field form element.
 *
 * @FormElement("creditfield_cardnumber")
 */
class CreditfieldCardnumber extends FormElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);

    return array(
      '#input' => TRUE,
      '#size' => 60,
      '#maxlength' => 16,
      '#autocomplete_route_name' => FALSE,
      '#element_validate' => array(
        array($class, 'validateCreditfieldCardnumber')
      ),
      '#process' => array(
        array($class, 'processCreditfieldCardnumber'),
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
  public static function processCreditfieldCardnumber(&$element, FormStateInterface $form_state, &$complete_form) {
    return $element;
  }

  /**
   * Validate callback for credit card number fields.
   * Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org
   * @param array $element
   */
  public static function validateCreditfieldCardnumber(&$element, FormStateInterface $form_state, &$complete_form) {
    // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
    $cardnumber = preg_replace('/\D/', '', $element['#value']);

    if ($cardnumber === '') {
      return;
    }

    if (!is_numeric($cardnumber)) {
      $form_state->setError($element, t('Please enter a valid credit card number.'));
      return;
    }

    // Set the string length and parity
    $cardnumber_length = Unicode::strlen($cardnumber);
    $parity = $cardnumber_length % 2;

    // Loop through each digit and do the maths
    $total=0;

    for ($i = 0; $i < $cardnumber_length; $i++) {
      $digit = $cardnumber[$i];
      // Multiply alternate digits by two
      if ($i % 2 == $parity) {
        $digit *= 2;
        // If the sum is two digits, add them together (in effect)
        if ($digit > 9) {
          $digit -= 9;
        }
      }
      // Total up the digits
      $total += $digit;
    }

    // If the total mod 10 equals 0, the number is valid
    $valid = ($total % 10 == 0) ? TRUE : FALSE;

    if (!$valid) {
      $form_state->setError($element, t('Your card appears to be invalid. Please check the numbers and try again.'));
      return;
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