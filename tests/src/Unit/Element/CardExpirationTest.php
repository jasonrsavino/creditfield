<?php

namespace Drupal\Tests\creditfield\Unit\Element;

use Drupal\creditfield\Element\CardExpiration;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\creditfield\Element\CardExpiration
 * @group creditfield
 */
class CardExpirationTest extends UnitTestCase {

  /**
   * @covers ::dateIsValid
   * @dataProvider providerValidCardExpirationDate
   */
  public function testGoodDateValidation($value) {
    $this->assertTrue(CardExpiration::dateIsValid($value), 'Date "' . $value . '" should have passed validation, but did not.');
  }

  /**
   * @covers ::dateIsValid
   * @dataProvider providerInvalidCardExpirationDate
   */
  public function testBadDateValidation($value) {
    $this->assertFalse(CardExpiration::dateIsValid($value), 'Date "' . $value . '" should not have passed validation, but did.');
  }

  /**
   * Data provider of valid dates. Includes variants that should pass validation.
   * Since our validator simply checks that the date is in the future, any future month/year combo should pass.
   * @return array
   */
  public function providerValidCardExpirationDate() {
    $year = date('Y') + 1;

    return [
      [$year . '-' . '01'],
      [$year + 1 . '-' . '03'],
      [$year + 2 . '-' . '07'],
      [$year + 2 . '-' . '12'],
      [date('Y') . '-' . date('m')],
    ];
  }

  /**
   * Data provider of invalid dates.
   * Since our validator simply checks that the date is in the future, any past month/year combo should fail. The current date should also fail.
   * @return array
   */
  public function providerInvalidCardExpirationDate() {
    $year = date('Y') - 3;

    return [
      [$year . '-' . '01'],
      [$year + 1 . '-' . '09'],
      [$year + 2 . '-' . '04'],
      [$year + 2 . '-' . '13'],
      [$year + 2 . '-' . '-2'],
      [$year + 2 . '-' . '0'],
      [$year + 1 . '-' . '24'],
      [$year - 5 . '-' . '13'],
      [$year - 5 . '-' . '32'],
      [date('Y') . '-' . (date('m') - 1)]
    ];
  }
}
