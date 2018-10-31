<?php
namespace SMB\SeleniumDownloader\Cli;

use SMB\SeleniumDownloader\Argument\Optionable;
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
            isset($args[Optionable::PLATFORM])
            && in_array($args[Optionable::PLATFORM], [self::MAC, self::WINDOWS, self::LINUX], true)
        ) {
            return $args[Optionable::PLATFORM];
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
        if (! isset($args[Optionable::OUTPUT_DIR]) || $args[Optionable::OUTPUT_DIR] === '') {
            return $this->defaultOutputDir();
        }

        return $args[Optionable::OUTPUT_DIR];
    }

    /**
     * {@inheritdoc}
     */
    protected function before()
    {
        if ($this->option->isSpecifiedHelp()) {
            $this->interactor->out($this->option->createHelpMessage());
            exit();
        }

        if ($this->getPlatform() === '') {
            $this->interactor->out('Please input platform(-' . Optionable::PLATFORM .' [m]ac or [w]indows or [l]inux).');
            exit();
        }

        if ($this->getOutputDir() === '') {
            $this->interactor->out('Please input an existing writable directory path(-' . Optionable::OUTPUT_DIR . ').');
            exit();
        }
    }

    /**
     * 
     * @return void
     */
    protected function _execute()
    {
        $this->args = $this->option->get();

        $resultOfSeleniumPreparation = $this->preparaOfSelenium();
        if (! $resultOfSeleniumPreparation) {
            $this->interactor->out('Please input the "major-ver.minor-ver (.patch-ver)" format and the existing version of selenium(-' . Optionable::SELENIUM_VER . ').');
            exit();
        }

        $resultOfChromeDriverPreparation = $this->prepareOfChromeDriver();
        if (! $resultOfChromeDriverPreparation) {
            $this->interactor->out('Please input the "major-ver.minor-ver" format and existing version of ChromeDriver(-' . Optionable::CHROME_DRIVER_VER . ').');
            exit();
        }

        $resultOfGeckoDriverPreparation = $this->prepareOfGeckoDriver();
        if (! $resultOfGeckoDriverPreparation) {
            $this->interactor->out('Please input the "major-ver.minor-ver(.patch-ver)" format and existing version of GeckoDriver(-' . Optionable::GECKO_DRIVER_VER . ').');
            exit();
        }

        $resultOfIEDriverPreparation = $this->prepareOfIEDriver();
        if (! $resultOfIEDriverPreparation) {
            $this->interactor->out('Please input the "major-ver.minor-ver(.patch-ver)" format and existing version of IEDriver(-' . Optionable::IE_DRIVER_VER . ').');
            exit();
        }
    }

    /**
     * 
     * @return boolean
     */
    private function preparaOfSelenium()
    {
        if (! isset($this->args[Optionable::SELENIUM_VER]) || $this->args[Optionable::SELENIUM_VER] === '') {
            return true;
        }

        return $this->downloadSelenium($this->args[Optionable::SELENIUM_VER]);
    }

    /**
     * 
     * @return boolean
     */
    private function prepareOfChromeDriver()
    {
        if (! isset($this->args[Optionable::CHROME_DRIVER_VER]) || $this->args[Optionable::CHROME_DRIVER_VER] === '') {
            return true;
        }

        return $this->downloadChromeDriver($this->args[Optionable::CHROME_DRIVER_VER]);
    }

    /**
     * 
     * @return boolean
     */
    private function prepareOfGeckoDriver()
    {
        if (! isset($this->args[Optionable::GECKO_DRIVER_VER]) || $this->args[Optionable::GECKO_DRIVER_VER] === '') {
            return true;
        }

        return $this->downloadGeckoDriver($this->args[Optionable::GECKO_DRIVER_VER]);
    }

    /**
     * 
     * @return boolean
     */
    private function prepareOfIEDriver()
    {
        if (! isset($this->args[Optionable::IE_DRIVER_VER]) || $this->args[Optionable::IE_DRIVER_VER] === '') {
            return true;
        }

        $bit = '32';
        if (
            isset($this->args[Optionable::OS_BIT_VER])
            && ($this->args[Optionable::OS_BIT_VER] === '32' || $this->args[Optionable::OS_BIT_VER] === '64')
        ) {
            $bit = $this->args[Optionable::OS_BIT_VER];
        }

        return $this->downloadIEDriver($this->args[Optionable::IE_DRIVER_VER], $bit);
    }
}