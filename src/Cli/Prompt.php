<?php
namespace SMB\SeleniumDownloader\Cli;

use SMB\SeleniumDownloader\Cli\Abstraction;

class Prompt extends Abstraction
{
    /**
     * 
     * @return string
     */
    protected function determinePlatform()
    {
        return $this->interactor->determinePlatform(self::MAC, self::WINDOWS, self::LINUX);
    }

    /**
     * 
     * @return string
     */
    protected function determineOutputDir()
    {
        return $this->interactor->determineOutputDir($this->defaultOutputDir());
    }

    /**
     * {@inheritdoc}
     */
    protected function before()
    {
        if ($this->getOutputDir() === '') {
            $this->interactor->out('Please enter an existing writable directory path.');
            $this->interactor->quit();
        }
    }

    /**
     * 
     * @return void
     */
    protected function _execute()
    {
        $resultOfSeleniumDownload = $this->_downloadSelenium();
        if (! $resultOfSeleniumDownload) {
            $this->interactor->out('Please enter the "major-ver.minor-ver(.patch-ver)" format and existing version.');
            $this->interactor->quit();
        }

        $resultOfChromeDriverDownload = $this->_downloadChromeDriver();
        if (! $resultOfChromeDriverDownload) {
            $this->interactor->out('Please enter the "major-ver.minor-ver(.build-ver.patch-ver)" format and existing version.');
            $this->interactor->quit();
        }

        $resultOfGeckoDriverDownload = $this->_downloadGeckoDriver();
        if (! $resultOfGeckoDriverDownload) {
            $this->interactor->out('Please enter the "major-ver.minor-ver(.patch-ver)" format and existing version.');
            $this->interactor->quit();
        }

        $resultOfIEDriverDownload = $this->_downloadIEDriver();
        if (! $resultOfIEDriverDownload) {
            $this->interactor->out('Please enter the "major-ver.minor-ver(.patch-ver)" format and existing version.');
            $this->interactor->quit();
        }
    }

    /**
     * 
     * @return boolean
     */
    private function _downloadSelenium()
    {
        $response = $this->interactor->confirmNecessityOfAsset(
            'Do you need Selenium? [N]o, [y]es:',
            'N',
            ['N', 'n', 'y']
        );

        if ($response === 'N' || $response === 'n') {
            return true;
        }

        $ver = $this->determiningVersionOfSelenium();

        return $this->downloadSelenium($ver);
    }

    /**
     * 
     * @return string
     */
    private function determiningVersionOfSelenium()
    {
        return $this->interactor->determiningVersionOfAsset(
            'Please enter selenium-server-standalone version Default [' . getenv('DEFAULT_SELENIUM_VER') . ']:', 
            getenv('DEFAULT_SELENIUM_VER')
        );
    }

    /**
     * 
     * @return boolean
     */
    private function _downloadChromeDriver()
    {
        $response = $this->interactor->confirmNecessityOfAsset(
            'Do you need ChromeDriver? [N]o, [y]es:',
            'N',
            ['N', 'n', 'y']
        );

        if ($response === 'N' || $response === 'n') {
            return true;
        }

        $ver = $this->determiningVersionOfChromeDriver();

        return $this->downloadChromeDriver($ver);
    }

    /**
     * 
     * @return string
     */
    private function determiningVersionOfChromeDriver()
    {
        return $this->interactor->determiningVersionOfAsset(
            'Please enter ChromeDriver version Default [' . getenv('DEFAULT_CHROMEDRIVER_VER') . ']:', 
            getenv('DEFAULT_CHROMEDRIVER_VER')
        );
    }

    /**
     * 
     * @return boolean
     */
    private function _downloadGeckoDriver()
    {
        $response = $this->interactor->confirmNecessityOfAsset(
            'Do you need GeckoDriver? [N]o, [y]es:',
            'N',
            ['N', 'n', 'y']
        );

        if ($response === 'N' || $response === 'n') {
            return true;
        }

        $ver = $this->determiningVersionOfGeckoDriver();

        return $this->downloadGeckoDriver($ver);
    }

    /**
     * 
     * @return string
     */
    private function determiningVersionOfGeckoDriver()
    {
        return $this->interactor->determiningVersionOfAsset(
            'Please enter GeckoDriver version Default [' . getenv('DEFAULT_GECKODRIVER_VER') . ']:', 
            getenv('DEFAULT_GECKODRIVER_VER')
        );
    }

    /**
     * 
     * @return boolean
     */
    private function _downloadIEDriver()
    {
        $response = $this->interactor->confirmNecessityOfAsset(
            'Do you need IEDriver? [N]o, [y]es:',
            'N',
            ['N', 'n', 'y']
        );

        if ($response === 'N' || $response === 'n') {
            return true;
        }

        $ver = $this->determiningVersionOfIEDriver();
        $bit = $this->determiningOfOSBit();

        return $this->downloadIEDriver($ver, $bit);
    }

    /**
     * 
     * @return string
     */
    private function determiningVersionOfIEDriver()
    {
        return $this->interactor->determiningVersionOfAsset(
            'Please enter IEDriverServer version Default [' . getenv('DEFAULT_IEDRIVER_VER') . ']:', 
            getenv('DEFAULT_IEDRIVER_VER')
        );
    }

    /**
     * 
     * @return string
     */
    private function determiningOfOSBit()
    {
        return $this->interactor->confirmNecessityOfAsset(
            'Please enter OS bit version [32]bit, [64]bit, Default[32]:',
            '32',
            ['32', '64']
        );
    }
}