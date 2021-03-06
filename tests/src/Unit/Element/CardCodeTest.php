<?php

namespace Drupal\Tests\creditfield\Unit\Element;

use Drupal\creditfield\Element\CardCode;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\creditfield\Element\CardCode
 * @group creditfield
 */
class CardCodeTest extends UnitTestCase {

  /**
   * @covers ::numberIsValid
   * @dataProvider providerValidCardCodeNumbers
   */
  public function testGoodCodeValidation($number) {
    $this->assertTrue(CardCode::numberIsValid($number), 'Number code "' . $number . '" should have passed validation, but did not.');
  }

  /**
   * @covers ::numberIsValid
   * @dataProvider providerInvalidCardCodeNumbers
   */
  public function testBadCodeValidation($number) {
    $this->assertFalse(CardCode::numberIsValid($number), 'Number code "' . $number . '" should not have passed validation, but did.');
  }

  /**
   * Data provider of valid test codes. Includes variants that should pass validation.
   * @return array
   */
  public function providerValidCardCodeNumbers() {
    return [
      ['012'],
      ['123'],
      ['555'],
      ['0123'],
      ['1234'],
    ];
  }

  /**
   * Data provider of valid test codes. Includes variants that should fail, like negative numbers, alphanumeric characters, values that are too short, or too long.
   * @return array
   */
  public function providerInvalidCardCodeNumbers() {
    return [
      ['1.1'],
      ['4af'],
      ['8724372'],
      ['3'],
      ['-134'],
      [''],
    ];
  }
}
