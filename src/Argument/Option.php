<?php
namespace SMB\SeleniumDownloader\Argument;

class Option implements Optionable
{
    /**
     *
     * @return boolean
     */
    public function isSpecified()
    {
        return count($this->get()) > 0;
    }

    /**
     *
     * @return boolean
     */
    public function isSpecifiedHelp()
    {
        $opt = $this->get();
        return isset($opt[OptionConfig::HELP]) || isset($opt[OptionConfig::HELP_LONG]);
    }

    /**
     * Create help message.
     *
     * @return string
     */
    public function createHelpMessage()
    {
        $m  = 'Usage:' . PHP_EOL;
        $m .= '  -h,--help' . PHP_EOL;
        $m .= '    Display help message and exit.' . PHP_EOL;
        $m .= '  -p platform (required)' . PHP_EOL;
        $m .= '    Select platform [m]ac or [w]indows or [l]inux.' . PHP_EOL;
        $m .= '    Required except that "--help, -h" is specified.' . PHP_EOL;
        $m .= '  -d output_dir_path' . PHP_EOL;
        $m .= '    Enter the output directory path.' . PHP_EOL;
        $m .= '    If not specified, it is output to the path specified by .env.dafault|.env(DEFAULT_OUTPUT_DIR).' . PHP_EOL;
        $m .= '    If there is still no value, it is output to "selenium-downloader/xxx".' . PHP_EOL;
        $m .= '  -s selenium-standalone-server_ver' . PHP_EOL;
        $m .= '    Enter the version of selenium-standalone-server. (e.g 3.8.1, 3.7(3.7.0)' . PHP_EOL;
        $m .= '    (Recommend version 3.8.1)' . PHP_EOL;
        $m .= '  -c ChromeDriver_ver' . PHP_EOL;
        $m .= '    Enter the version of ChromeDriver. (e.g 2.43' . PHP_EOL;
        $m .= '  -g geckodriver_ver' . PHP_EOL;
        $m .= '    Enter the version of GeckoDriver. (e.g 0.23(0.23.0), 0.20.1' . PHP_EOL;
        $m .= '  -i IEDriverServer_ver' . PHP_EOL;
        $m .= '    Enter the version of IEDriverServer. (e.g 3.14(3.14.0)' . PHP_EOL;
        $m .= '  -b bit_of_os' . PHP_EOL;
        $m .= '    Enter the number of OS bits (32 or 64).' . PHP_EOL;
        $m .= '    Default is "32" (Because key input is earlier than 64bit version).' . PHP_EOL;
        $m .= '    Valid only when IEDriverServer is specified.' . PHP_EOL;
        $m .= PHP_EOL;
        $m .= 'e.g) 1 Basic.' . PHP_EOL;
        $m .= '$ php selenium_downloader.php -p m -s 3.8.1 -c 2.43 -g 0.23' . PHP_EOL;
        $m .= 'e.g) 2 When specifying the output directory.' . PHP_EOL;
        $m .= '$ php selenium_downloader.php -p m -d /your/path/to -s 3.8.1' . PHP_EOL;
        $m .= 'e.g) 3 When downloading the 64 bit version of the IEDriverServer.' . PHP_EOL;
        $m .= '$ php selenium_downloader.php -p w -i 3.14.0 -b 64' . PHP_EOL;
        $m .= 'e.g) 4 When downloading only geckodriver.' . PHP_EOL;
        $m .= '$ php selenium_downloader.php -p m -g 0.23' . PHP_EOL;
        $m .= 'or' . PHP_EOL;
        $m .= '$ php selenium_downloader.php -p m -s "" -c "" -g 0.23' . PHP_EOL;

        return $m;
    }

    /**
     *
     * @return array
     */
    public function get()
    {
        return $this->_getopt();
    }

    /**
     * Acquire command line option.
     *
     * @return array
     */
    private function _getopt()
    {
        $shortopts  = OptionConfig::HELP;  // help
        $shortopts .= OptionConfig::PLATFORM . ':';          // platform
        $shortopts .= OptionConfig::OUTPUT_DIR . ':';        // output dir.
        $shortopts .= OptionConfig::SELENIUM_VER . ':';      // Selenium-Standalone-Server Ver.
        $shortopts .= OptionConfig::CHROME_DRIVER_VER . ':'; // ChromeDriver Ver.
        $shortopts .= OptionConfig::GECKO_DRIVER_VER . ':';  // GeckoDriver Ver.
        $shortopts .= OptionConfig::IE_DRIVER_VER . ':';     // IEDriverServer Ver.
        $shortopts .= OptionConfig::OS_BIT_VER . ':';        // bit of OS (Default 32).

        $longopts = array(
            OptionConfig::HELP_LONG . '::', // help
        );

        return getopt($shortopts, $longopts);
    }
}
