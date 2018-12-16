<?php
namespace SMB\SeleniumDownloader\Cli;

use SMB\SeleniumDownloader\Proxy\Interactable;

abstract class Abstraction
{
    /**
     * @var string
     */
    const MAC = 'm';

    /**
     * @var string
     */
    const WINDOWS = 'w';

    /**
     * @var string
     */
    const LINUX = 'l';

    /**
     * @var strig
     */
    const URL_OF_SELENIUM = 'https://selenium-release.storage.googleapis.com/%s/selenium-server-standalone-%s.jar';

    /** @var string */
    const CHROMEDRIVER_URL_OF_MAC = 'https://chromedriver.storage.googleapis.com/%s/chromedriver_mac64.zip';
    /** @var string */
    const CHROMEDRIVER_URL_OF_WINDOWS = 'https://chromedriver.storage.googleapis.com/%s/chromedriver_win32.zip';
    /** @var string */
    const CHROMEDRIVER_URL_OF_LINUX = 'https://chromedriver.storage.googleapis.com/%s/chromedriver_linux64.zip';

    /** @var string */
    const GECKODRIVER_URL_OF_MAC = 'https://github.com/mozilla/geckodriver/releases/download/v%s/geckodriver-v%s-macos.tar.gz';
    /** @var string */
    const GECKODRIVER_URL_OF_WINDOWS = 'https://github.com/mozilla/geckodriver/releases/download/v%s/geckodriver-v%s-win64.zip';
    /** @var string */
    const GECKODRIVER_URL_OF_LINUX = 'https://github.com/mozilla/geckodriver/releases/download/v%s/geckodriver-v%s-linux64.tar.gz';

    /** @var string */
    const IEDRIVER_URL_OF_32BIT = 'https://selenium-release.storage.googleapis.com/%s/IEDriverServer_Win32_%s.zip';
    /** @var string */
    const IEDRIVER_URL_OF_64BIT = 'https://selenium-release.storage.googleapis.com/%s/IEDriverServer_x64_%s.zip';

    /**
     *
     * @var Interactable
     */
    protected $interactor;

    /**
     * @var string
     */
    private $platform;

    /**
     *
     * @var string
     */
    private $outputDir = '';

    /**
     *
     * @param Interactable $interactor
     *
     * @return void
     */
    public function __construct(Interactable $interactor)
    {
        $this->interactor = $interactor;
    }

    /**
     *
     * @return string
     */
    abstract protected function determinePlatform();

    /**
     *
     * @return string
     */
    abstract protected function determineOutputDir();

    /**
     * Execute what you want to do before processing execution.<br>
     * e.g) Validation, etc
     *
     * @return void
     */
    abstract protected function before();

    /**
     *
     * @return void
     */
    abstract protected function _execute();

    /**
     *
     * @return void
     */
    public function execute()
    {
        $this->platform = $this->determinePlatform();

        $outputDir = realpath($this->determineOutputDir());
        if (
            $outputDir
            && is_dir($outputDir)
            && is_writable($outputDir)
        ) {
            $this->outputDir = $outputDir . DIRECTORY_SEPARATOR;
        }

        $this->before();

        $this->_execute();

        $this->interactor->out('Done.');
    }

    /**
     *
     * @return string
     */
    protected function defaultOutputDir()
    {
        $libDir = realpath(dirname(__FILE__) . '/../../');
        $dir = getenv('DEFAULT_OUTPUT_DIR') ? getenv('DEFAULT_OUTPUT_DIR') : $libDir;
        return realpath($dir) . DIRECTORY_SEPARATOR;
    }

    /**
     *
     * @return string
     */
    protected function getPlatform()
    {
        return $this->platform;
    }

    /**
     *
     * @return string
     */
    protected function getOutputDir()
    {
        return $this->outputDir;
    }

    /**
     *
     * @param string $ver
     *
     * @return boolean
     */
    protected function downloadSelenium($ver)
    {
        $_ver = $this->determineVersion($ver);

        if ($_ver === null) {
            return false;
        }

        $url = sprintf(self::URL_OF_SELENIUM, $_ver->minorVer, $_ver->patchVer);

        return $this->download($url);
    }

    /**
     *
     * @param string $ver
     *
     * @return boolean
     */
    protected function downloadChromeDriver($ver)
    {
        $_ver = $this->determineVersion($ver);

        if ($_ver === null) {
            return false;
        }

        $downloadUrl = '';
        switch ($this->platform) {
            case self::MAC :
                $downloadUrl = sprintf(self::CHROMEDRIVER_URL_OF_MAC, $_ver->minorVer);
                break;
            case self::WINDOWS :
                $downloadUrl = sprintf(self::CHROMEDRIVER_URL_OF_WINDOWS, $_ver->minorVer);
                break;
            case self::LINUX :
                $downloadUrl = sprintf(self::CHROMEDRIVER_URL_OF_LINUX, $_ver->minorVer);
                break;
        }

        $ret = $this->download($downloadUrl);
        if ($ret === false) {
            return false;
        }

        $filename = basename($downloadUrl);
        shell_exec('unzip -o ' . $this->outputDir . $filename . ' -d ' . $this->outputDir);
        unlink($this->outputDir . $filename);

        return true;
    }

    /**
     *
     * @param string $ver
     *
     * @return boolean
     */
    protected function downloadGeckoDriver($ver)
    {
        $_ver = $this->determineVersion($ver);

        if ($_ver === null) {
            return false;
        }

        $downloadUrl = '';
        switch ($this->platform) {
            case self::MAC :
                $downloadUrl = sprintf(self::GECKODRIVER_URL_OF_MAC, $_ver->patchVer, $_ver->patchVer);
                break;
            case self::WINDOWS :
                $downloadUrl = sprintf(self::GECKODRIVER_URL_OF_WINDOWS, $_ver->patchVer, $_ver->patchVer);
                break;
            case self::LINUX :
                $downloadUrl = sprintf(self::GECKODRIVER_URL_OF_LINUX, $_ver->patchVer, $_ver->patchVer);
                break;
        }

        $ret = $this->download($downloadUrl);
        if ($ret === false) {
            return false;
        }

        $filename = basename($downloadUrl);

        if ($this->platform === self::WINDOWS) {
            shell_exec('unzip -o ' . $this->outputDir . $filename . ' -d ' . $this->outputDir);
            unlink($this->outputDir . $filename);
            return true;
        }

        $phar = new \PharData($this->outputDir . $filename);
        $phar->extractTo($this->outputDir, null, true);
        unlink($this->outputDir . $filename);

        return true;
    }

    /**
     *
     * @param string $ver
     * @param string $bit
     *
     * @return boolean
     */
    protected function downloadIEDriver($ver, $bit)
    {
        $_ver = $this->determineVersion($ver);

        if ($_ver === null) {
            return false;
        }

        $downloadUrl = '';
        switch ($bit) {
            case '32' :
                $downloadUrl = sprintf(self::IEDRIVER_URL_OF_32BIT, $_ver->minorVer, $_ver->patchVer);
                break;
            case '64' :
                $downloadUrl = sprintf(self::IEDRIVER_URL_OF_64BIT, $_ver->minorVer, $_ver->patchVer);
                break;
        }

        $ret = $this->download($downloadUrl);
        if ($ret === false) {
            return false;
        }

        $filename = basename($downloadUrl);
        shell_exec('unzip -o ' . $this->outputDir . $filename . ' -d ' . $this->outputDir);
        unlink($this->outputDir . $filename);

        return true;
    }

    /**
     *
     * @param string $ver
     *
     * @return \stdClass|null
     */
    protected function determineVersion($ver)
    {
        $_m = [];
        preg_match('/^([0-9]|[1-9][0-9]{1,})(\.([0-9]|[1-9][0-9]{1,}))(\.(.*))?$/', $ver, $_m);

        if (
            count($_m) === 0
            || !isset($_m[1])
            || !isset($_m[3])
        ) {
            return null;
        }

        $major = $_m[1];
        $minor = $_m[3];
        $patch = isset($_m[5]) ? $_m[5] : '0';

        $minorVer = $major . '.' . $minor;
        $patchVer = $minorVer . '.' . $patch;

        $ret = new \stdClass();
        $ret->majorVer = $major;
        $ret->minorVer = $minorVer;
        $ret->patchVer = $patchVer;

        return $ret;
    }

    /**
     *
     * @param string $url
     *
     * @return boolean
     */
    private function download($url)
    {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR    => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 10,
        ));

        $ret     = curl_exec($ch);
        $error   = curl_error($ch);
        $errorNo = curl_errno($ch);

        curl_close($ch);

        if($errorNo !== CURLE_OK) {
            $this->interactor->out('Download error [url]: ' . $url);
            $this->interactor->out('Download error [curl error]: ' . $error);
            $this->interactor->out('Download error [curl error no]: ' . $errorNo);
            return false;
        }

        $file = fopen($this->outputDir . basename($url), "w+");
        fputs($file, $ret);
        fclose($file);

        return true;
    }
}
