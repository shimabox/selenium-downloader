<?php
namespace SMB\SeleniumDownloader\Cli;

use SMB\SeleniumDownloader\Argument\Optionable;
use SMB\SeleniumDownloader\Argument\OptionConfig;
use SMB\SeleniumDownloader\Cli\Abstraction;

class Option extends Abstraction
{
    /**
     *
     * @var Optionable
     */
    private $option;

    /**
     *
     * @var array
     */
    private $args;

    /**
     * 
     * @param Optionable $option
     * 
     * @return void
     */
    public function setOption(Optionable $option)
    {
        $this->option = $option;
    }

    /**
     * 
     * @return string
     */
    protected function determinePlatform()
    {
        $args = $this->option->get();

        if (
            isset($args[OptionConfig::PLATFORM])
            && in_array($args[OptionConfig::PLATFORM], [self::MAC, self::WINDOWS, self::LINUX], true)
        ) {
            return $args[OptionConfig::PLATFORM];
        }

        return '';
    }

    /**
     * 
     * @return string
     */
    protected function determineOutputDir()
    {
        $args = $this->option->get();
        if (! isset($args[OptionConfig::OUTPUT_DIR]) || $args[OptionConfig::OUTPUT_DIR] === '') {
            return $this->defaultOutputDir();
        }

        return $args[OptionConfig::OUTPUT_DIR];
    }

    /**
     * {@inheritdoc}
     */
    protected function before()
    {
        if ($this->option->isSpecifiedHelp()) {
            $this->interactor->out($this->option->createHelpMessage());
            $this->interactor->quit();
        }

        if ($this->getPlatform() === '') {
            $this->interactor->out('Please input platform(-' . OptionConfig::PLATFORM .' [m]ac or [w]indows or [l]inux).');
            $this->interactor->quit();
        }

        if ($this->getOutputDir() === '') {
            $this->interactor->out('Please input an existing writable directory path(-' . OptionConfig::OUTPUT_DIR . ').');
            $this->interactor->quit();
        }
    }

    /**
     * 
     * @return void
     */
    protected function _execute()
    {
        $this->args = $this->option->get();

        $resultOfSeleniumDownload = $this->_downloadSelenium();
        if (! $resultOfSeleniumDownload) {
            $this->interactor->out('Please input the "major-ver.minor-ver(.patch-ver)" format and the existing version of selenium(-' . OptionConfig::SELENIUM_VER . ').');
            $this->interactor->quit();
        }

        $resultOfChromeDriverDownload = $this->_downloadChromeDriver();
        if (! $resultOfChromeDriverDownload) {
            $this->interactor->out('Please input the "major-ver.minor-ver(.build-ver.patch-ver)" format and existing version of ChromeDriver(-' . OptionConfig::CHROME_DRIVER_VER . ').');
            $this->interactor->quit();
        }

        $resultOfGeckoDriverDownload = $this->_downloadGeckoDriver();
        if (! $resultOfGeckoDriverDownload) {
            $this->interactor->out('Please input the "major-ver.minor-ver(.patch-ver)" format and existing version of GeckoDriver(-' . OptionConfig::GECKO_DRIVER_VER . ').');
            $this->interactor->quit();
        }

        $resultOfIEDriverDownload = $this->_downloadIEDriver();
        if (! $resultOfIEDriverDownload) {
            $this->interactor->out('Please input the "major-ver.minor-ver(.patch-ver)" format and existing version of IEDriver(-' . OptionConfig::IE_DRIVER_VER . ').');
            $this->interactor->quit();
        }
    }

    /**
     * 
     * @return boolean
     */
    private function _downloadSelenium()
    {
        if (! isset($this->args[OptionConfig::SELENIUM_VER]) || $this->args[OptionConfig::SELENIUM_VER] === '') {
            return true;
        }

        return $this->downloadSelenium($this->args[OptionConfig::SELENIUM_VER]);
    }

    /**
     * 
     * @return boolean
     */
    private function _downloadChromeDriver()
    {
        if (! isset($this->args[OptionConfig::CHROME_DRIVER_VER]) || $this->args[OptionConfig::CHROME_DRIVER_VER] === '') {
            return true;
        }

        return $this->downloadChromeDriver($this->args[OptionConfig::CHROME_DRIVER_VER]);
    }

    /**
     * 
     * @return boolean
     */
    private function _downloadGeckoDriver()
    {
        if (! isset($this->args[OptionConfig::GECKO_DRIVER_VER]) || $this->args[OptionConfig::GECKO_DRIVER_VER] === '') {
            return true;
        }

        return $this->downloadGeckoDriver($this->args[OptionConfig::GECKO_DRIVER_VER]);
    }

    /**
     * 
     * @return boolean
     */
    private function _downloadIEDriver()
    {
        if (! isset($this->args[OptionConfig::IE_DRIVER_VER]) || $this->args[OptionConfig::IE_DRIVER_VER] === '') {
            return true;
        }

        $bit = '32';
        if (
            isset($this->args[OptionConfig::OS_BIT_VER])
            && ($this->args[OptionConfig::OS_BIT_VER] === '32' || $this->args[OptionConfig::OS_BIT_VER] === '64')
        ) {
            $bit = $this->args[OptionConfig::OS_BIT_VER];
        }

        return $this->downloadIEDriver($this->args[OptionConfig::IE_DRIVER_VER], $bit);
    }
}