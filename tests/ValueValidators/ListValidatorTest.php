<?php

namespace ValueValidators\Tests;

use PHPUnit_Framework_TestCase;
use ValueValidators\Error;
use ValueValidators\ListValidator;

/**
 * @covers ValueValidators\ListValidator
 *
 * @group ValueValidators
 * @group DataValueExtensions
 *
 * @licence GNU GPL v2+
 * @author Thiemo Mättig
 */
class ListValidatorTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider invalidRangeProvider
	 */
	public function testInvalidRange( $range ) {
		$validator = new ListValidator();
		$validator->setOptions( array( 'elementcount' => $range ) );
		$this->setExpectedException( 'Exception' );
		$validator->validate( array() );
	}

	public function invalidRangeProvider() {
		return array(
			array( null ),
			array( 0 ),
			array( '' ),
			array( array() ),
			array( array( 0 ) ),
			array( array( 0, 0, 0 ) ),
		);
	}

	/**
	 * @dataProvider valueProvider
	 */
	public function testValidate( $value, array $options, $expectedErrors ) {
		$validator = new ListValidator();
		$validator->setOptions( $options );
		$result = $validator->validate( $value );

		if ( !is_array( $expectedErrors ) ) {
			$expectedErrors = array( $expectedErrors );
		}

		$this->assertEquals( $expectedErrors, $result->getErrors() );
	}

	public function valueProvider() {
		return array(
			array(
				'value' => null,
				'options' => array(),
				'expectedErrors' => Error::newError( 'Not an array' )
			),
			array(
				'value' => 0,
				'options' => array(),
				'expectedErrors' => Error::newError( 'Not an array' )
			),
			array(
				'value' => '',
				'options' => array(),
				'expectedErrors' => Error::newError( 'Not an array' )
			),
			array(
				'value' => array(),
				'options' => array(),
				'expectedErrors' => array()
			),
			array(
				'value' => array( 1 ),
				'options' => array(),
				'expectedErrors' => array()
			),

			// Lower bound only
			array(
				'value' => array(),
				'options' => array( 'minelements' => null ),
				'expectedErrors' => array()
			),
			array(
				'value' => array(),
				'options' => array( 'minelements' => 0 ),
				'expectedErrors' => array()
			),
			array(
				'value' => array(),
				'options' => array( 'minelements' => 1 ),
				'expectedErrors' => Error::newError( 'Value exceeding lower bound', 'length' )
			),
			array(
				'value' => array( 1 ),
				'options' => array( 'minelements' => 1 ),
				'expectedErrors' => array()
			),

			// Upper bound only
			array(
				'value' => array(),
				'options' => array( 'maxelements' => null ),
				'expectedErrors' => array()
			),
			array(
				'value' => array(),
				'options' => array( 'maxelements' => 0 ),
				'expectedErrors' => array()
			),
			array(
				'value' => array( 1 ),
				'options' => array( 'maxelements' => 0 ),
				'expectedErrors' => Error::newError( 'Value exceeding upper bound', 'length' )
			),
			array(
				'value' => array( 1 ),
				'options' => array( 'maxelements' => 1 ),
				'expectedErrors' => array()
			),

			// Lower and upper bound
			array(
				'value' => array(),
				'options' => array( 'elementcount' => array( 0, 0 ) ),
				'expectedErrors' => array()
			),
			array(
				'value' => array( 1 ),
				'options' => array( 'elementcount' => array( 2, 2 ) ),
				'expectedErrors' => Error::newError( 'Value exceeding lower bound', 'length' )
			),
			array(
				'value' => array( 1, 2 ),
				'options' => array( 'elementcount' => array( 2, 2 ) ),
				'expectedErrors' => array()
			),
			array(
				'value' => array( 1 ),
				'options' => array( 'elementcount' => array( 0, 0 ) ),
				'expectedErrors' => Error::newError( 'Value exceeding upper bound', 'length' )
			),
			array(
				'value' => array(),
				'options' => array( 'elementcount' => array( 0, 0 ) ),
				'expectedErrors' => array()
			),
			array(
				'value' => array(),
				'options' => array( 'elementcount' => array( 2, 0 ) ),
				'expectedErrors' => Error::newError( 'Value exceeding lower bound', 'length' )
			),
			array(
				'value' => array( 1, 2 ),
				'options' => array( 'elementcount' => array( 2, 0 ) ),
				'expectedErrors' => Error::newError( 'Value exceeding upper bound', 'length' )
			),
			array(
				'value' => array( 1 ),
				'options' => array( 'elementcount' => array( 2, 0 ) ),
				'expectedErrors' => array(
					Error::newError( 'Value exceeding upper bound', 'length' ),
					Error::newError( 'Value exceeding lower bound', 'length' ),
				)
			),
			array(
				'value' => array( 1 ),
				'options' => array( 'minelements' => 2, 'maxelements' => 0 ),
				'expectedErrors' => array(
					Error::newError( 'Value exceeding upper bound', 'length' ),
					Error::newError( 'Value exceeding lower bound', 'length' ),
				)
			),

			// Conflicting options
			array(
				'value' => array(),
				'options' => array( 'elementcount' => array( 1, 1 ), 'minelements' => null ),
				'expectedErrors' => array()
			),
			array(
				'value' => array(),
				'options' => array( 'elementcount' => array( 1, 1 ), 'minelements' => false ),
				'expectedErrors' => array()
			),
			array(
				'value' => array(),
				'options' => array( 'elementcount' => array( 1, 1 ), 'minelements' => 0 ),
				'expectedErrors' => array()
			),
			array(
				'value' => array(),
				'options' => array( 'minelements' => 0, 'elementcount' => array( 1, 1 ) ),
				'expectedErrors' => array()
			),
			array(
				'value' => array( 1 ),
				'options' => array( 'elementcount' => array( 0, 0 ), 'maxelements' => false ),
				'expectedErrors' => array()
			),
			array(
				'value' => array( 1 ),
				'options' => array( 'elementcount' => array( 0, 0 ), 'maxelements' => 1 ),
				'expectedErrors' => array()
			),
			array(
				'value' => array( 1 ),
				'options' => array( 'maxelements' => 1, 'elementcount' => array( 0, 0 ) ),
				'expectedErrors' => array()
			),
		);
	}

}
