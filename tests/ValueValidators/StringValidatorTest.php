<?php

namespace ValueValidators\Tests;

use PHPUnit_Framework_TestCase;
use ValueValidators\Error;
use ValueValidators\StringValidator;

/**
 * @covers ValueValidators\StringValidator
 *
 * @group ValueValidators
 * @group DataValueExtensions
 *
 * @licence GNU GPL v2+
 * @author Thiemo Mättig
 */
class StringValidatorTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider stringProvider
	 */
	public function testValidate( $value, array $options, $expectedError ) {
		$validator = new StringValidator();
		$validator->setOptions( $options );
		$result = $validator->validate( $value );

		$this->assertEquals(
			$expectedError === null ? array() : array( $expectedError ),
			$result->getErrors()
		);
	}

	public function stringProvider() {
		return array(
			array(
				'value' => null,
				'options' => array(),
				'expectedErrors' => Error::newError( 'Not a string' )
			),
			array(
				'value' => '',
				'options' => array(),
				'expectedErrors' => null
			),
			array(
				'value' => '',
				'options' => array( 'length' => 1 ),
				'expectedErrors' => Error::newError( 'Value exceeding lower bound', 'length' )
			),
			array(
				'value' => '1',
				'options' => array( 'length' => 1 ),
				'expectedErrors' => null
			),
			array(
				'value' => '1',
				'options' => array( 'length' => 0 ),
				'expectedErrors' => Error::newError( 'Value exceeding upper bound', 'length' )
			),
			array(
				'value' => '',
				'options' => array( 'length' => 0 ),
				'expectedErrors' => null
			),
			array(
				'value' => '',
				'options' => array( 'minlength' => 1 ),
				'expectedErrors' => Error::newError( 'Value exceeding lower bound', 'length' )
			),
			array(
				'value' => '1',
				'options' => array( 'minlength' => 1 ),
				'expectedErrors' => null
			),
			array(
				'value' => '1',
				'options' => array( 'maxlength' => 0 ),
				'expectedErrors' => Error::newError( 'Value exceeding upper bound', 'length' )
			),
			array(
				'value' => '',
				'options' => array( 'maxlength' => 0 ),
				'expectedErrors' => null
			),
			array(
				'value' => '1',
				'options' => array( 'regex' => '/^$/' ),
				'expectedErrors' => Error::newError( 'String does not match the regular expression /^$/' )
			),
			array(
				'value' => '',
				'options' => array( 'regex' => '/^$/' ),
				'expectedErrors' => null
			),
		);
	}

}
