<?php

namespace SMB\SeleniumDownloader\Tests\Cli;

use SMB\SeleniumDownloader\Cli\Prompt;

/**
 * Test of SMB\SeleniumDownloader\Cli\Prompt
 *
 * @group Cli
 * @group Prompt
 */
class PromptTest extends \PHPUnit_Framework_TestCase
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
     * delete OutputDir.
     */
    private static function deleteOutputDir()
    {
        exec('rm -rf ' . self::$outputDir);
    }

    /**
     * @test
     */
    public function it_can_download_with_the_default_version_in_Mac()
    {
        $interactorMock = $this->getInteractorMock();
        $interactorMock
            ->expects($this->once())
            ->method('determinePlatform')
            ->willReturn('m') // Platform is Mac.
            ;
        $interactorMock
            ->expects($this->once())
            ->method('determineOutputDir')
            ->willReturn(self::$outputDir)
            ;
        $interactorMock
            ->expects($this->exactly(4))
            ->method('confirmNecessityOfAsset')
            ->will($this->returnValueMap([
                ['Do you need Selenium? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need ChromeDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need GeckoDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need IEDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
            ]))
            ;
        $interactorMock
            ->expects($this->exactly(3))
            ->method('determiningVersionOfAsset')
            ->will($this->returnValueMap([
                ['Please enter selenium-server-standalone version Default [' . getenv('DEFAULT_SELENIUM_VER') . ']:', getenv('DEFAULT_SELENIUM_VER'), '3.8.1'],
                ['Please enter ChromeDriver version Default [' . getenv('DEFAULT_CHROMEDRIVER_VER') . ']:', getenv('DEFAULT_CHROMEDRIVER_VER'), '2.43'],
                ['Please enter GeckoDriver version Default [' . getenv('DEFAULT_GECKODRIVER_VER') . ']:', getenv('DEFAULT_GECKODRIVER_VER'), '0.23.0'],
            ]))
            ;

        $target = new Prompt($interactorMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/selenium-server-standalone-3.8.1.jar');
        $this->assertFileExists(self::$outputDir . '/chromedriver');
        $this->assertFileExists(self::$outputDir . '/geckodriver');

        $this->assertFileNotExists(self::$outputDir . '/IEDriverServer.exe');
    }

    /**
     * @test
     */
    public function it_can_be_downloaded_on_the_Mac_with_the_version_specified()
    {
        $interactorMock = $this->getInteractorMock();
        $interactorMock
            ->expects($this->once())
            ->method('determinePlatform')
            ->willReturn('m') // Platform is Mac.
            ;
        $interactorMock
            ->expects($this->once())
            ->method('determineOutputDir')
            ->willReturn(self::$outputDir)
            ;
        $interactorMock
            ->expects($this->exactly(4))
            ->method('confirmNecessityOfAsset')
            ->will($this->returnValueMap([
                ['Do you need Selenium? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need ChromeDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need GeckoDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need IEDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
            ]))
            ;
        $interactorMock
            ->expects($this->exactly(3))
            ->method('determiningVersionOfAsset')
            ->will($this->returnValueMap([
                ['Please enter selenium-server-standalone version Default [' . getenv('DEFAULT_SELENIUM_VER') . ']:', getenv('DEFAULT_SELENIUM_VER'), '3.8'],
                ['Please enter ChromeDriver version Default [' . getenv('DEFAULT_CHROMEDRIVER_VER') . ']:', getenv('DEFAULT_CHROMEDRIVER_VER'), '75.0.3770.8'],
                ['Please enter GeckoDriver version Default [' . getenv('DEFAULT_GECKODRIVER_VER') . ']:', getenv('DEFAULT_GECKODRIVER_VER'), '0.20.1'],
            ]))
            ;

        $target = new Prompt($interactorMock);
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
        $interactorMock = $this->getInteractorMock();
        $interactorMock
            ->expects($this->once())
            ->method('determinePlatform')
            ->willReturn('m') // Platform is Mac.
            ;
        $interactorMock
            ->expects($this->once())
            ->method('determineOutputDir')
            ->willReturn(self::$outputDir)
            ;
        $interactorMock
            ->expects($this->exactly(4))
            ->method('confirmNecessityOfAsset')
            ->will($this->returnValueMap([
                ['Do you need Selenium? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
                ['Do you need ChromeDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need GeckoDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
                ['Do you need IEDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
            ]))
            ;
        $interactorMock
            ->expects($this->exactly(1))
            ->method('determiningVersionOfAsset')
            ->will($this->returnValueMap([
                ['Please enter ChromeDriver version Default [' . getenv('DEFAULT_CHROMEDRIVER_VER') . ']:', getenv('DEFAULT_CHROMEDRIVER_VER'), '2.43'],
            ]))
            ;

        $target = new Prompt($interactorMock);
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
        $interactorMock = $this->getInteractorMock();
        $interactorMock
            ->expects($this->once())
            ->method('determinePlatform')
            ->willReturn('w') // Platform is Windows.
            ;
        $interactorMock
            ->expects($this->once())
            ->method('determineOutputDir')
            ->willReturn(self::$outputDir)
            ;
        $interactorMock
            ->expects($this->exactly(5))
            ->method('confirmNecessityOfAsset')
            ->will($this->returnValueMap([
                ['Do you need Selenium? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need ChromeDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need GeckoDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need IEDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Please enter OS bit version [32]bit, [64]bit, Default[32]:', '32', ['32', '64'], '32'],
            ]))
            ;
        $interactorMock
            ->expects($this->exactly(4))
            ->method('determiningVersionOfAsset')
            ->will($this->returnValueMap([
                ['Please enter selenium-server-standalone version Default [' . getenv('DEFAULT_SELENIUM_VER') . ']:', getenv('DEFAULT_SELENIUM_VER'), '3.8.1'],
                ['Please enter ChromeDriver version Default [' . getenv('DEFAULT_CHROMEDRIVER_VER') . ']:', getenv('DEFAULT_CHROMEDRIVER_VER'), '2.43'],
                ['Please enter GeckoDriver version Default [' . getenv('DEFAULT_GECKODRIVER_VER') . ']:', getenv('DEFAULT_GECKODRIVER_VER'), '0.23.0'],
                ['Please enter IEDriverServer version Default [' . getenv('DEFAULT_IEDRIVER_VER') . ']:', getenv('DEFAULT_IEDRIVER_VER'), '3.14.0'],
            ]))
            ;

        $target = new Prompt($interactorMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/selenium-server-standalone-3.8.1.jar');
        $this->assertFileExists(self::$outputDir . '/chromedriver.exe');
        $this->assertFileExists(self::$outputDir . '/geckodriver.exe');
        $this->assertFileExists(self::$outputDir . '/IEDriverServer.exe');
    }

    /**
     * @test
     */
    public function it_can_be_downloaded_on_the_Windows_with_the_version_specified()
    {
        $interactorMock = $this->getInteractorMock();
        $interactorMock
            ->expects($this->once())
            ->method('determinePlatform')
            ->willReturn('w') // Platform is Windows.
            ;
        $interactorMock
            ->expects($this->once())
            ->method('determineOutputDir')
            ->willReturn(self::$outputDir)
            ;
        $interactorMock
            ->expects($this->exactly(5))
            ->method('confirmNecessityOfAsset')
            ->will($this->returnValueMap([
                ['Do you need Selenium? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need ChromeDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need GeckoDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need IEDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Please enter OS bit version [32]bit, [64]bit, Default[32]:', '32', ['32', '64'], '32'],
            ]))
            ;
        $interactorMock
            ->expects($this->exactly(4))
            ->method('determiningVersionOfAsset')
            ->will($this->returnValueMap([
                ['Please enter selenium-server-standalone version Default [' . getenv('DEFAULT_SELENIUM_VER') . ']:', getenv('DEFAULT_SELENIUM_VER'), '3.8'],
                ['Please enter ChromeDriver version Default [' . getenv('DEFAULT_CHROMEDRIVER_VER') . ']:', getenv('DEFAULT_CHROMEDRIVER_VER'), '75.0.3770.8'],
                ['Please enter GeckoDriver version Default [' . getenv('DEFAULT_GECKODRIVER_VER') . ']:', getenv('DEFAULT_GECKODRIVER_VER'), '0.20.1'],
                ['Please enter IEDriverServer version Default [' . getenv('DEFAULT_IEDRIVER_VER') . ']:', getenv('DEFAULT_IEDRIVER_VER'), '3.141.0'],
            ]))
            ;

        $target = new Prompt($interactorMock);
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
        $interactorMock = $this->getInteractorMock();
        $interactorMock
            ->expects($this->once())
            ->method('determinePlatform')
            ->willReturn('w') // Platform is Windows.
            ;
        $interactorMock
            ->expects($this->once())
            ->method('determineOutputDir')
            ->willReturn(self::$outputDir)
            ;
        $interactorMock
            ->expects($this->exactly(5))
            ->method('confirmNecessityOfAsset')
            ->will($this->returnValueMap([
                ['Do you need Selenium? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
                ['Do you need ChromeDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
                ['Do you need GeckoDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
                ['Do you need IEDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Please enter OS bit version [32]bit, [64]bit, Default[32]:', '32', ['32', '64'], '64'],
            ]))
            ;
        $interactorMock
            ->expects($this->exactly(1))
            ->method('determiningVersionOfAsset')
            ->will($this->returnValueMap([
                ['Please enter IEDriverServer version Default [' . getenv('DEFAULT_IEDRIVER_VER') . ']:', getenv('DEFAULT_IEDRIVER_VER'), '3.14.0'],
            ]))
            ;

        $target = new Prompt($interactorMock);
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
        $interactorMock = $this->getInteractorMock();
        $interactorMock
            ->expects($this->once())
            ->method('determinePlatform')
            ->willReturn('l') // Platform is Linux.
            ;
        $interactorMock
            ->expects($this->once())
            ->method('determineOutputDir')
            ->willReturn(self::$outputDir)
            ;
        $interactorMock
            ->expects($this->exactly(4))
            ->method('confirmNecessityOfAsset')
            ->will($this->returnValueMap([
                ['Do you need Selenium? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need ChromeDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need GeckoDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need IEDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
            ]))
            ;
        $interactorMock
            ->expects($this->exactly(3))
            ->method('determiningVersionOfAsset')
            ->will($this->returnValueMap([
                ['Please enter selenium-server-standalone version Default [' . getenv('DEFAULT_SELENIUM_VER') . ']:', getenv('DEFAULT_SELENIUM_VER'), '3.8.1'],
                ['Please enter ChromeDriver version Default [' . getenv('DEFAULT_CHROMEDRIVER_VER') . ']:', getenv('DEFAULT_CHROMEDRIVER_VER'), '2.43'],
                ['Please enter GeckoDriver version Default [' . getenv('DEFAULT_GECKODRIVER_VER') . ']:', getenv('DEFAULT_GECKODRIVER_VER'), '0.23.0'],
            ]))
            ;

        $target = new Prompt($interactorMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/selenium-server-standalone-3.8.1.jar');
        $this->assertFileExists(self::$outputDir . '/chromedriver');
        $this->assertFileExists(self::$outputDir . '/geckodriver');

        $this->assertFileNotExists(self::$outputDir . '/IEDriverServer.exe');
    }

    /**
     * @test
     */
    public function it_can_be_downloaded_on_the_Linux_with_the_version_specified()
    {
        $interactorMock = $this->getInteractorMock();
        $interactorMock
            ->expects($this->once())
            ->method('determinePlatform')
            ->willReturn('l') // Platform is Linux.
            ;
        $interactorMock
            ->expects($this->once())
            ->method('determineOutputDir')
            ->willReturn(self::$outputDir)
            ;
        $interactorMock
            ->expects($this->exactly(4))
            ->method('confirmNecessityOfAsset')
            ->will($this->returnValueMap([
                ['Do you need Selenium? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need ChromeDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need GeckoDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need IEDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
            ]))
            ;
        $interactorMock
            ->expects($this->exactly(3))
            ->method('determiningVersionOfAsset')
            ->will($this->returnValueMap([
                ['Please enter selenium-server-standalone version Default [' . getenv('DEFAULT_SELENIUM_VER') . ']:', getenv('DEFAULT_SELENIUM_VER'), '3.8'],
                ['Please enter ChromeDriver version Default [' . getenv('DEFAULT_CHROMEDRIVER_VER') . ']:', getenv('DEFAULT_CHROMEDRIVER_VER'), '75.0.3770.8'],
                ['Please enter GeckoDriver version Default [' . getenv('DEFAULT_GECKODRIVER_VER') . ']:', getenv('DEFAULT_GECKODRIVER_VER'), '0.20.1'],
            ]))
            ;

        $target = new Prompt($interactorMock);
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
        $interactorMock = $this->getInteractorMock();
        $interactorMock
            ->expects($this->once())
            ->method('determinePlatform')
            ->willReturn('l') // Platform is Linux.
            ;
        $interactorMock
            ->expects($this->once())
            ->method('determineOutputDir')
            ->willReturn(self::$outputDir)
            ;
        $interactorMock
            ->expects($this->exactly(4))
            ->method('confirmNecessityOfAsset')
            ->will($this->returnValueMap([
                ['Do you need Selenium? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
                ['Do you need ChromeDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
                ['Do you need GeckoDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'y'],
                ['Do you need IEDriver? [N]o, [y]es:', 'N', ['N', 'n', 'y'], 'n'],
            ]))
            ;
        $interactorMock
            ->expects($this->exactly(1))
            ->method('determiningVersionOfAsset')
            ->will($this->returnValueMap([
                ['Please enter GeckoDriver version Default [' . getenv('DEFAULT_GECKODRIVER_VER') . ']:', getenv('DEFAULT_GECKODRIVER_VER'), '0.23.0'],
            ]))
            ;

        $target = new Prompt($interactorMock);
        $target->execute();

        $this->assertFileExists(self::$outputDir . '/geckodriver');

        $this->assertFileNotExists(self::$outputDir . '/selenium-server-standalone-3.8.1.jar');
        $this->assertFileNotExists(self::$outputDir . '/chromedriver');
        $this->assertFileNotExists(self::$outputDir . '/IEDriverServer.exe');
    }
}
