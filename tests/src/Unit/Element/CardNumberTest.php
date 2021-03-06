<?php

namespace Drupal\Tests\creditfield\Unit\Element;

use Drupal\creditfield\Element\CardNumber;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\creditfield\Element\CardNumber
 * @group creditfield
 */
class CardNumberTest extends UnitTestCase {

  /**
   * @covers ::numberIsValid
   * @dataProvider providerValidCardNumbers
   */
  public function testGoodNumberValidation($number) {
    $this->assertTrue(CardNumber::numberIsValid($number), 'Number "' . $number . '" should have passed validation, but did not.');
  }

  /**
   * @covers ::numberIsValid
   * @dataProvider providerInvalidCardNumbers
   */
  public function testBadNumberValidation($number) {
    $this->assertFalse(CardNumber::numberIsValid($number), 'Number "' . $number . '" should not have passed validation, but did.');
  }

  /**
   * Data provider of valid test numbers. Includes variants that should pass validation.
   * @return array
   */
  public function providerValidCardNumbers() {
    return [
      ['4242424242424242'],
      ['4012888888881881'],
      ['4000056655665556'],
      ['5555555555554444'],
      ['5200828282828210'],
      ['5105105105105100'],
      ['378282246310005'],
      ['371449635398431'],
      ['6011111111111117'],
      ['6011000990139424'],
      ['30569309025904'],
      ['38520000023237'],
      ['3530111333300000'],
      ['3566002020360505']
    ];
  }

  /**
   * Data provider of valid test numbers. Includes variants that should fail, like negative numbers, alphanumeric characters, values that are too short, or too long.
   * @return array
   */
  public function providerInvalidCardNumbers() {
    return [
      ['424224242'],
      ['4012888888881881445353'],
      ['-4242424242424242'],
      ['40128888.10'],
      ['4242aBcD24244242'],
      ['ABCDEFGHIJKL'],
      ['1234828282828210'],
      [''],
    ];
  }
}
