<?php

namespace helpers;

require_once dirname(__FILE__) . '/../Loader.php';
$loader = new \helpers\Loader(array('../../'));
$loader->register();


/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-03-08 at 21:14:50.
 */
class StreamTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @var Stream
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new Stream;
	}


	/**
	 * @covers helpers\Stream::download
	 * @todo   Implement testDownload().
	 */
	public function testDownload()
	{
		$this->object->download('./teste.txt', '../');
		$this->assertFileExists('../teste.txt');
		unlink('../teste.txt');
	}

	/**
	 * @covers helpers\Stream::getShortUrl
	 * @todo   Implement testGetShortUrl().
	 */
	public function testGetShortUrl()
	{
		//$this->assertLessThan(25, strlen($this->object->getShortUrl('http://apssouza.com.br/blog')));
	}

	/**
	 * @covers helpers\Stream::getContentUrlByCurl
	 * @todo   Implement testGetContentUrlByCurl().
	 */
	public function testGetContentUrlByCurl()
	{
		$this->assertContains('div', $this->object->getContentUrlByCurl('http://apssouza.com.br'));
	}

	/**
	 * @covers helpers\Stream::fileExist
	 * @todo   Implement testFileExist().
	 */
	public function testFileExist()
	{
		$this->assertTrue($this->object->fileExist('http://apssouza.com.br/img/ico_sobremim.png'));
	}

}
