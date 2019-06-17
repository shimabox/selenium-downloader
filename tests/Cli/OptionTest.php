<?php

namespace SMB\SeleniumDownloader\Tests\Cli;

use SMB\SeleniumDownloader\Cli\Option;

/**
 * Test of SMB\SeleniumDownloader\Cli\Option
 *
 * @group Cli
 * @group Option
 */
class OptionTest extends \PHPUnit_Framework_TestCase
{
    use \SMB\SeleniumDownloader\Tests\Helper;

    /**
     * @var string
     */
    private static $outputDir = 'tests/download';

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        self::deleteOutputDir();
        mkdir(self::$outputDir);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        self::deleteOutputDir();
    }

    /**
     * This method is called after the last test of this test class is run.
     */
    public static function tearDownAfterClass()
    {
        self::deleteOutputDir();
    }

    /**
     * Delete OutputDir.
     */
    private static function deleteOutputDir()
    {
        exec('rm -rf ' . self::$outputDir);
    }

    /**
     * @test
     */
    public function createHelpMessage_is_called_when_an_option_is_passed_h()
    {
        $interactorMock = $this->getInteractorMock(['out', 'quit']);

        $optionMock = $this->getOptionMock(['get', 'createHelpMessage']);
        $optionMock
            ->expects($this->once())
            ->method('createHelpMessage')
            ;
        $optionMock
            ->method('get')
            ->willReturn([
                'h' => false
            ])
            ;

        $target = new Option($interactorMock);
        $target->setOption($optionMock);
        $target->execute();
    }

    /**
     * @test
     */
    public function createHelpMessage_is_called_when_an_longoption_is_passed_help()
    {
        $interactorMock = $this->getInteractorMock(['out', 'quit']);

        $optionMock = $this->getOptionMock(['get', 'createHelpMessage']);
        $optionMock
            ->expects($this->once())
            ->method('createHelpMessage')
            ;
        $optionMock
            ->method('get')
            ->willReturn([
                'help' => false
            ])
            ;

        $target = new Option($interactorMock);
        $target->setOption($optionMock);
        $target->execute();
    }

    /**
     * @test
     */
    public function it_can_download_with_the_default_version_in_Mac()
    {
        $interactorMock = $this->getInteractorMock(['out', 'quit']);

        $optionMock = $this->getOptionMock(['get', 'createHelpMessage']);
        $optionMock
            ->expects($this->never())
            ->method('createHelpMessage')
            ;
        $optionMock
            ->expects($this->exactly(4))
            ->method('get')
            ->willReturn([
                'p' => 'm', // Platform is Mac.
                'd' => self::$outputDir,
                's' => getenv('DEFAULT_SELENIUM_VER'),
                'c' => getenv('DEFAULT_CHROMEDRIVER_VER'),
                'g' => getenv('DEFAULT_GECKODRIVER_VER'),
            ])
            ;

        $target = new Option($interactorMock);
        $target->setOption($optionMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/selenium-server-standalone-' . getenv('DEFAULT_SELENIUM_VER') . '.jar');
        $this->assertFileExists(self::$outputDir . '/chromedriver');
        $this->assertFileExists(self::$outputDir . '/geckodriver');

        $this->assertFileNotExists(self::$outputDir . '/IEDriverServer.exe');
    }

    /**
     * @test
     */
    public function it_can_be_downloaded_on_the_Mac_with_the_version_specified()
    {
        $interactorMock = $this->getInteractorMock(['out', 'quit']);

        $optionMock = $this->getOptionMock(['get', 'createHelpMessage']);
        $optionMock
            ->expects($this->never())
            ->method('createHelpMessage')
            ;
        $optionMock
            ->expects($this->exactly(4))
            ->method('get')
            ->willReturn([
                'p' => 'm', // Platform is Mac.
                'd' => self::$outputDir,
                's' => '3.8',
                'c' => '75.0.3770.8',
                'g' => '0.20.1',
            ])
            ;

        $target = new Option($interactorMock);
        $target->setOption($optionMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/selenium-server-standalone-3.8.0.jar');
        $this->assertFileExists(self::$outputDir . '/chromedriver');
        $this->assertFileExists(self::$outputDir . '/geckodriver');

        $this->assertFileNotExists(self::$outputDir . '/IEDriverServer.exe');
    }

    /**
     * @test
     */
    public function it_can_download_the_selected_driver_in_Mac()
    {
        $interactorMock = $this->getInteractorMock(['out', 'quit']);

        $optionMock = $this->getOptionMock(['get', 'createHelpMessage']);
        $optionMock
            ->expects($this->never())
            ->method('createHelpMessage')
            ;
        $optionMock
            ->expects($this->exactly(4))
            ->method('get')
            ->willReturn([
                'p' => 'm', // Platform is Mac.
                'd' => self::$outputDir,
                'c' => '2.43',
            ])
            ;

        $target = new Option($interactorMock);
        $target->setOption($optionMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/chromedriver');

        $this->assertFileNotExists(self::$outputDir . '/selenium-server-standalone-3.8.1.jar');
        $this->assertFileNotExists(self::$outputDir . '/geckodriver');
        $this->assertFileNotExists(self::$outputDir . '/IEDriverServer.exe');
    }

    /**
     * @test
     */
    public function it_can_download_with_the_default_version_in_Windows()
    {
        $interactorMock = $this->getInteractorMock(['out', 'quit']);

        $optionMock = $this->getOptionMock(['get', 'createHelpMessage']);
        $optionMock
            ->expects($this->never())
            ->method('createHelpMessage')
            ;
        $optionMock
            ->expects($this->exactly(4))
            ->method('get')
            ->willReturn([
                'p' => 'w', // Platform is Windows.
                'd' => self::$outputDir,
                's' => getenv('DEFAULT_SELENIUM_VER'),
                'c' => getenv('DEFAULT_CHROMEDRIVER_VER'),
                'g' => getenv('DEFAULT_GECKODRIVER_VER'),
                'i' => getenv('DEFAULT_IEDRIVER_VER'),
            ])
            ;

        $target = new Option($interactorMock);
        $target->setOption($optionMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/selenium-server-standalone-' . getenv('DEFAULT_SELENIUM_VER') . '.jar');
        $this->assertFileExists(self::$outputDir . '/chromedriver.exe');
        $this->assertFileExists(self::$outputDir . '/geckodriver.exe');
        $this->assertFileExists(self::$outputDir . '/IEDriverServer.exe');
    }

    /**
     * @test
     */
    public function it_can_be_downloaded_on_the_Windows_with_the_version_specified()
    {
        $interactorMock = $this->getInteractorMock(['out', 'quit']);

        $optionMock = $this->getOptionMock(['get', 'createHelpMessage']);
        $optionMock
            ->expects($this->never())
            ->method('createHelpMessage')
            ;
        $optionMock
            ->expects($this->exactly(4))
            ->method('get')
            ->willReturn([
                'p' => 'w', // Platform is Windows.
                'd' => self::$outputDir,
                's' => '3.8',
                'c' => '75.0.3770.8',
                'g' => '0.20.1',
                'i' => '3.141.0',
            ])
            ;

        $target = new Option($interactorMock);
        $target->setOption($optionMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/selenium-server-standalone-3.8.0.jar');
        $this->assertFileExists(self::$outputDir . '/chromedriver.exe');
        $this->assertFileExists(self::$outputDir . '/geckodriver.exe');
        $this->assertFileExists(self::$outputDir . '/IEDriverServer.exe');
    }

    /**
     * @test
     */
    public function it_can_download_64bit_version_of_IEDriverServer_in_Windows()
    {
        $interactorMock = $this->getInteractorMock(['out', 'quit']);

        $optionMock = $this->getOptionMock(['get', 'createHelpMessage']);
        $optionMock
            ->expects($this->never())
            ->method('createHelpMessage')
            ;
        $optionMock
            ->expects($this->exactly(4))
            ->method('get')
            ->willReturn([
                'p' => 'w', // Platform is Windows.
                'd' => self::$outputDir,
                'i' => '3.14.0',
                'b' => '64'
            ])
            ;

        $target = new Option($interactorMock);
        $target->setOption($optionMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/IEDriverServer.exe');

        $this->assertFileNotExists(self::$outputDir . '/selenium-server-standalone-3.8.1.jar');
        $this->assertFileNotExists(self::$outputDir . '/chromedriver.exe');
        $this->assertFileNotExists(self::$outputDir . '/geckodriver.exe');
    }

    /**
     * @test
     */
    public function it_can_download_with_the_default_version_in_Linux()
    {
        $interactorMock = $this->getInteractorMock(['out', 'quit']);

        $optionMock = $this->getOptionMock(['get', 'createHelpMessage']);
        $optionMock
            ->expects($this->never())
            ->method('createHelpMessage')
            ;
        $optionMock
            ->expects($this->exactly(4))
            ->method('get')
            ->willReturn([
                'p' => 'l', // Platform is Linux.
                'd' => self::$outputDir,
                's' => getenv('DEFAULT_SELENIUM_VER'),
                'c' => getenv('DEFAULT_CHROMEDRIVER_VER'),
                'g' => getenv('DEFAULT_GECKODRIVER_VER'),
            ])
            ;

        $target = new Option($interactorMock);
        $target->setOption($optionMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/selenium-server-standalone-' . getenv('DEFAULT_SELENIUM_VER') . '.jar');
        $this->assertFileExists(self::$outputDir . '/chromedriver');
        $this->assertFileExists(self::$outputDir . '/geckodriver');

        $this->assertFileNotExists(self::$outputDir . '/IEDriverServer.exe');
    }

    /**
     * @test
     */
    public function it_can_be_downloaded_on_the_Linux_with_the_version_specified()
    {
        $interactorMock = $this->getInteractorMock(['out', 'quit']);

        $optionMock = $this->getOptionMock(['get', 'createHelpMessage']);
        $optionMock
            ->expects($this->never())
            ->method('createHelpMessage')
            ;
        $optionMock
            ->expects($this->exactly(4))
            ->method('get')
            ->willReturn([
                'p' => 'l', // Platform is Linux.
                'd' => self::$outputDir,
                's' => '3.8',
                'c' => '75.0.3770.8',
                'g' => '0.20.1',
            ])
            ;

        $target = new Option($interactorMock);
        $target->setOption($optionMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/selenium-server-standalone-3.8.0.jar');
        $this->assertFileExists(self::$outputDir . '/chromedriver');
        $this->assertFileExists(self::$outputDir . '/geckodriver');

        $this->assertFileNotExists(self::$outputDir . '/IEDriverServer.exe');
    }

    /**
     * @test
     */
    public function it_can_download_the_selected_driver_in_Linux()
    {
        $interactorMock = $this->getInteractorMock(['out', 'quit']);

        $optionMock = $this->getOptionMock(['get', 'createHelpMessage']);
        $optionMock
            ->expects($this->never())
            ->method('createHelpMessage')
            ;
        $optionMock
            ->expects($this->exactly(4))
            ->method('get')
            ->willReturn([
                'p' => 'l', // Platform is Linux.
                'd' => self::$outputDir,
                'g' => '0.23.0',
            ])
            ;

        $target = new Option($interactorMock);
        $target->setOption($optionMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/geckodriver');

        $this->assertFileNotExists(self::$outputDir . '/selenium-server-standalone-3.8.1.jar');
        $this->assertFileNotExists(self::$outputDir . '/chromedriver');
        $this->assertFileNotExists(self::$outputDir . '/IEDriverServer.exe');
    }
}
