<?php

class ExampleTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testHelloWorld()
	{
		$greeting = 'Hello, World.';

		$this->assertTrue($greeting === 'Hello, World.', $greeting);
	}

}
