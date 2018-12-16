<?php

namespace SMB\SeleniumDownloader\Tests;

use SMB\SeleniumDownloader\Downloader;

/**
 * Test of SMB\SeleniumDownloader\Downloader
 * 
 * @group Downloader
 */
class DownloaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_confirmed_that_isSpecified_of_Option_is_true()
    {
        $optionMock = $this->getMockBuilder('SMB\SeleniumDownloader\Argument\Option')
                           ->setMethods([
                               'get'
                           ])
                           ->getMock();

        $optionMock
            ->expects($this->once())
            ->method('get')
            ->willReturn([
              'p' => 'm',
              's' => '3.8.1',
            ])
            ;

        $target = new Downloader($optionMock);

        $reflectionClass = new \ReflectionClass(get_class($target));
        $property = $reflectionClass->getProperty('option');
        $property->setAccessible(true);

        $this->assertTrue($property->getValue($target)->isSpecified());
    }

    /**
     * @test
     */
    public function it_can_be_confirmed_that_isSpecified_of_Option_is_false()
    {
        $target = new Downloader();

        $reflectionClass = new \ReflectionClass(get_class($target));
        $property = $reflectionClass->getProperty('option');
        $property->setAccessible(true);

        $this->assertFalse($property->getValue($target)->isSpecified());
    }
}