<?php

namespace ValueValidators\Tests;

use PHPUnit_Framework_TestCase;
use ValueValidators\Error;
use ValueValidators\TitleValidator;

/**
 * @covers ValueValidators\TitleValidator
 *
 * @group ValueValidators
 * @group DataValueExtensions
 *
 * @licence GNU GPL v2+
 * @author Thiemo Mättig
 */
class TitleValidatorTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider titleProvider
	 */
	public function testValidate( $value, $hasToExist, $expectedError ) {
		$validator = new TitleValidator();
		$validator->setOptions( array( 'hastoexist' => $hasToExist ) );
		$result = $validator->validate( $value );

		$this->assertEquals(
			$expectedError === null ? array() : array( $expectedError ),
			$result->getErrors()
		);
	}

	public function titleProvider() {
		$title = $this->getMockBuilder( 'Title' )
			->disableOriginalConstructor()
			->setMethods( array( 'exists' ) )
			->getMock();
		$title->expects( $this->any() )
			->method( 'exists' )
			->will( $this->returnValue( false ) );

		return array(
			array(
				'value' => null,
				'hasToExist' => false,
				'expectedErrors' => Error::newError( 'Not a title' )
			),
			array(
				'value' => $title,
				'hasToExist' => false,
				'expectedErrors' => null
			),
			array(
				'value' => $title,
				'hasToExist' => true,
				'expectedErrors' => Error::newError( 'Title does not exist' )
			),
		);
	}

}
