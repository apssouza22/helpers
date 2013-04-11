<?php

namespace helpers;

require_once dirname(__FILE__) . '/../Loader.php';
$loader = new \helpers\Loader(array('../../'));
$loader->register();

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-03-08 at 20:36:04.
 */
class StringTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var String
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new String;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		
	}

	/**
	 * @covers helpers\String::getExtensao
	 * @todo   Implement testGetExtensao().
	 */
	public function testGetExtensao()
	{
		$this->assertEquals('jpg', $this->object->getExtensao('foto.jpg'));
	}

	/**
	 * @covers helpers\String::cutString
	 * @todo   Implement testCutString().
	 */
	public function testCutString()
	{
		$this->assertEquals('Teste unit�rio...',  $this->object->cutString('Teste unit�rio � lindo', 14,
															'...'));
		
		$this->assertEquals('Teste unit�rio',  $this->object->cutString('Teste unit�rio � lindo', 14));
	}



	/**
	 * @covers helpers\String::removeAccent
	 * @todo   Implement testRemoveAccent().
	 */
	public function testRemoveAccent()
	{
		// Remove the following lines when you implement this test.
		$this->assertNotContains('�', $this->object->removeAccent('�lex � lindo'));
	}

	/**
	 * @covers helpers\String::getUrlFriendly
	 * @todo   Implement testGetUrlFriendly().
	 */
	public function testGetUrlFriendly()
	{
		$this->assertNotContains('�', $this->object->getUrlFriendly('�lex � lindo'));
		$this->assertNotContains('/', $this->object->getUrlFriendly('�lex �/ lindo'));
		$this->assertNotContains('?', $this->object->getUrlFriendly('�lex � lindo?'));
	}

}