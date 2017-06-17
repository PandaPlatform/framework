<?php

namespace Panda\Helpers\tests;

use Panda\Helpers\UrlHelper;
use PHPUnit_Framework_TestCase;

/**
 * Class UrlHelperTest
 * @package Panda\Helpers\tests
 */
class UrlHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Panda\Helpers\UrlHelper::get
     */
    public function testGet()
    {
        // Plain url
        $this->assertEquals('http://sub.domain.com/path', UrlHelper::get('http://sub.domain.com/path'));
        $this->assertEquals('http://sub.domain.com/path', UrlHelper::get('sub.domain.com/path'));
        $this->assertEquals('https://sub.domain.com/path', UrlHelper::get('https://sub.domain.com/path'));

        // With parameters
        $this->assertEquals('http://sub.domain.com/path?paramname=paramvalue', UrlHelper::get('http://sub.domain.com/path', ['paramname' => 'paramvalue']));
        $this->assertEquals('http://sub.domain.com/path?paramname=paramvalue', UrlHelper::get('sub.domain.com/path', ['paramname' => 'paramvalue']));
        $this->assertEquals('https://sub.domain.com/path?paramname=paramvalue', UrlHelper::get('https://sub.domain.com/path', ['paramname' => 'paramvalue']));
        $this->assertEquals('https://sub.domain.com/path?paramname=param+value', UrlHelper::get('https://sub.domain.com/path', ['paramname' => 'param value']));
        $this->assertEquals('https://sub.domain.com/path?paramname=param%3Avalue', UrlHelper::get('https://sub.domain.com/path', ['paramname' => 'param:value']));

        // With host
        $this->assertEquals('http://sub.test.com/path', UrlHelper::get('http://sub.domain.com/path', [], 'sub.test.com'));
        $this->assertEquals('http://sub.test.com/path', UrlHelper::get('sub.domain.com/path', [], 'sub.test.com'));
        $this->assertEquals('https://sub.test.com/path', UrlHelper::get('https://sub.domain.com/path', [], 'sub.test.com'));

        // With protocol
        $this->assertEquals('http://sub.domain.com/path', UrlHelper::get('http://sub.domain.com/path', [], '', 'http'));
        $this->assertEquals('http://sub.domain.com/path', UrlHelper::get('sub.domain.com/path', [], '', 'http'));
        $this->assertEquals('http://sub.domain.com/path', UrlHelper::get('https://sub.domain.com/path', [], '', 'http'));
        $this->assertEquals('https://sub.domain.com/path', UrlHelper::get('http://sub.domain.com/path', [], '', 'https'));
        $this->assertEquals('https://sub.domain.com/path', UrlHelper::get('sub.domain.com/path', [], '', 'https'));
        $this->assertEquals('https://sub.domain.com/path', UrlHelper::get('https://sub.domain.com/path', [], '', 'https'));

        // With parameters, Without host
        $this->assertEquals('/path?paramname=paramvalue', UrlHelper::get('/path', ['paramname' => 'paramvalue']));
    }

    /**
     * @covers \Panda\Helpers\UrlHelper::getDomain
     */
    public function testGetDomain()
    {
        $this->assertEquals('domain1.com', UrlHelper::getDomain('http://sub.domain1.com/path?paramname=paramvalue'));
        $this->assertEquals('domain2.com', UrlHelper::getDomain('https://sub.domain2.com/path?paramname=paramvalue'));
        $this->assertEquals('domain3.com', UrlHelper::getDomain('http://sub.domain3.com/path'));
        $this->assertEquals('domain4.com', UrlHelper::getDomain('http://sub.domain4.com'));
    }

    /**
     * @covers \Panda\Helpers\UrlHelper::getSubDomain
     */
    public function testGetSubDomain()
    {
        $this->assertEquals('sub1', UrlHelper::getSubDomain('http://sub1.domain.com/path?paramname=paramvalue'));
        $this->assertEquals('sub2', UrlHelper::getSubDomain('https://sub2.domain.com/path?paramname=paramvalue'));
        $this->assertEquals('sub3', UrlHelper::getSubDomain('http://sub3.domain.com/path'));
        $this->assertEquals('sub4', UrlHelper::getSubDomain('http://sub4.domain.com'));
    }

    /**
     * @covers \Panda\Helpers\UrlHelper::info
     */
    public function testInfo()
    {
        // Full domain
        $info = UrlHelper::info('http://sub.domain.com/path?paramname=paramvalue');
        $this->assertEquals('http', $info['protocol']);
        $this->assertEquals('sub', $info['sub']);
        $this->assertEquals('domain.com', $info['domain']);
        $this->assertEquals('/path', $info['path']['plain']);
        $this->assertEquals('/path?paramname=paramvalue', $info['path']['with_parameters']);
        $this->assertEquals('paramvalue', $info['path']['parameters']['paramname']);

        // Full domain (http and https)
        $info = UrlHelper::info('http://sub.domain.com');
        $this->assertEquals('http', $info['protocol']);
        $info = UrlHelper::info('sub.domain.com');
        $this->assertEquals('http', $info['protocol']);
        $info = UrlHelper::info('https://sub.domain.com');
        $this->assertEquals('https', $info['protocol']);

        // Path
        $info = UrlHelper::info('http://sub.domain.com/path/to/file?name1=value1&name2=value2');
        $this->assertEquals('/path/to/file', $info['path']['plain']);
        $this->assertEquals('value1', $info['path']['parameters']['name1']);
        $this->assertEquals('value2', $info['path']['parameters']['name2']);

        // Parameters
        $info = UrlHelper::info('http://sub.domain.com/path/to/file?name1=value+with+space&name2=value%3Awith%3Adots');
        $this->assertEquals('/path/to/file', $info['path']['plain']);
        $this->assertEquals('value with space', $info['path']['parameters']['name1']);
        $this->assertEquals('value:with:dots', $info['path']['parameters']['name2']);
    }

    /**
     * @covers \Panda\Helpers\UrlHelper::isIP
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testIsIP()
    {
        $this->assertTrue(UrlHelper::isIP('1.1.1.1'));
        $this->assertTrue(UrlHelper::isIP('255.255.255.255'));
        $this->assertTrue(UrlHelper::isIP('255.1.0.255'));

        $this->assertFalse(UrlHelper::isIP('255.-1.0.255'));
        $this->assertFalse(UrlHelper::isIP('255.-1.0.-255'));

        $this->assertFalse(UrlHelper::isIP('a.b.c.d'));
        $this->assertFalse(UrlHelper::isIP('www.example.com'));
    }
}
