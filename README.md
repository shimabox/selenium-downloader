# selenium-downloader
selenium-server-standalone, ChromeDriver, geckodriver and IEDriverServer downloader.

[![License](https://poser.pugx.org/shimabox/selenium-downloader/license)](https://packagist.org/packages/shimabox/selenium-downloader)
[![Build Status](https://travis-ci.org/shimabox/selenium-downloader.svg?branch=master)](https://travis-ci.org/shimabox/selenium-downloader)

## Features

- Can download selenium related assets interactively or with option(not interactive).
  - https://selenium-release.storage.googleapis.com/index.html
  - http://chromedriver.chromium.org/downloads
  - https://github.com/mozilla/geckodriver/releases
- Selectable from Mac, Windows, Linux.

## Demo

### Interactively.

![demo-interaction](https://github.com/shimabox/assets/raw/master/selenium-downloader/demo-interaction.gif)

### With option (not interactive).

![demo-option](https://github.com/shimabox/assets/raw/master/selenium-downloader/demo-option.gif)

## Requirements

- PHP 5.5+ or newer

## Installation

Via Composer

```
$ composer require shimabox/selenium-downloader
```

Please refer to the link below.  
[##instant-download](https://github.com/shimabox/selenium-downloader#instant-download "shimabox/selenium-downloader: selenium-server-standalone, ChromeDriver, geckodriver and IEDriverServer downloader.")

Develop

```
$ git clone https://github.com/shimabox/selenium-downloader
$ cd selenium-downloader
$ composer install
```

## Setting(.env)

If you need to change the default settings, copy the `.env.default` file, create an `.env` file, and modify the `.env` file.  
The default setting looks at `.env.default` file.

```
$ cp .env.dafault .env
$ vim .env

// The default of output directory path(Output to "selenium-downloader/xxx" if not set).
DEFAULT_OUTPUT_DIR=
// The default version of selenium-server-standalone.
// Why 3.8.1? Because there are cases where it will not work unless pass-through mode can be set to false(-enablePassThrough false).
DEFAULT_SELENIUM_VER='3.8.1'
// The default version of ChromeDriver.
DEFAULT_CHROMEDRIVER_VER='2.43'
// The default version of geckodriver.
DEFAULT_GECKODRIVER_VER='0.23.0'
// The default version of IEDriverServer.
DEFAULT_IEDRIVER_VER='3.14.0'
```

If `DEFAULT_OUTPUT_DIR` is not set, the downloaded asset is output to `selenium-downloader/xxx`.

## Usage

### In the case of interactive mode.

```
# Run without option.
$ php selenium_downloader.php
```

e.g)

```
# "m" or "w" or "l" is mandatory.
Please select platform. [m]ac, [w]indows, [l]inux: w
# Specify the directory path to output.
# If not specified, it is output to the path specified by .env.dafault|.env(DEFAULT_OUTPUT_DIR).
# If there is still no value, it is output to "selenium-downloader/xxx".
Please enter the output directory
Default[/default/output/path]:
# Default "No".
Do you need Selenium? [N]o, [y]es: y
# Default "3.8.1".
# Why 3.8.1? Because there are cases where it will not work unless pass-through mode can be set to false(-enablePassThrough false).
Please enter selenium-server-standalone version Default [3.8.1]: 3.8.1
# Default "No".
Do you need ChromeDriver? [N]o, [y]es: y
# Default "2.43".
Please enter ChromeDriver version Default [2.43]:
# Default "No".
Do you need GeckoDriver? [N]o, [y]es: y
# Default "0.23.0".
Please enter GeckoDriver version Default [0.23.0]:
# Default "No".
Do you need IEDriver? [N]o, [y]es: y
# Default "3.14.0".
Please enter IEDriver version Default [3.14.0]:
# Default "32" (Because key input is earlier than 64bit version).
Please enter OS bit version [32]bit, [64]bit, Default[32]:
Done.
```

### When specifying options (not interactive).

Supports the following options.

|Format|Description|
|:---|:---|
|-h,--help|Display help message and exit.|
|-p|Select platform [m]ac or [w]indows or [l]inux.<br>Required except that "-h, --help" is specified.|
|-d|Specify the directory path to output.<br>If not specified, it is output to the path specified by .env.dafault\|.env(DEFAULT_OUTPUT_DIR).<br>If there is still no value, it is output to "selenium-downloader/xxx".|
|-s|The version of selenium-standalone-server.<br>(e.g 3.8.1, 3.7(3.7.0)<br>(Recommend version 3.8.1)|
|-c|The version of ChromeDriver.<br>(e.g 2.43|
|-g|The version of GeckoDriver.<br>(e.g 0.23(0.23.0), 0.20.1|
|-i|The version of IEDriverServer.<br>(e.g 3.14(3.14.0)|
|-b|The number of OS bits (32 or 64).<br>Default is "32" (Because key input is earlier than 64bit version)<br>Valid only when IEDriverServer is specified.|

Help message.

```
$ php selenium_downloader.php -h
Usage:
  -h,--help
    Display help message and exit.
  -p platform (required)
    Select platform [m]ac or [w]indows or [l]inux.
    Required except that "--help, -h" is specified.
  -d output_dir_path
    Enter the output directory path.
    If not specified, it is output to the path specified by .env.dafault|.env(DEFAULT_OUTPUT_DIR).
    If there is still no value, it is output to "selenium-downloader/xxx".
  -s selenium-standalone-server_ver
    Enter the version of selenium-standalone-server. (e.g 3.8.1, 3.7(3.7.0)
    (Recommend version 3.8.1)
  -c ChromeDriver_ver
    Enter the version of ChromeDriver. (e.g 2.43
  -g geckodriver_ver
    Enter the version of GeckoDriver. (e.g 0.23(0.23.0), 0.20.1
  -i IEDriverServer_ver
    Enter the version of IEDriverServer. (e.g 3.14(3.14.0)
  -b bit_of_os
    Enter the number of OS bits (32 or 64).
    Default is "32" (Because key input is earlier than 64bit version).
    Valid only when IEDriverServer is specified.

e.g) 1 Basic.
$ php selenium_downloader.php -p m -s 3.8.1 -c 2.43 -g 0.23
e.g) 2 When specifying the output directory.
$ php selenium_downloader.php -p m -d /your/path/to -s 3.8.1
e.g) 3 When downloading the 64 bit version of the IEDriverServer.
$ php selenium_downloader.php -p w -i 3.14.0 -b 64
e.g) 4 When downloading only geckodriver.
$ php selenium_downloader.php -p m -g 0.23
or
$ php selenium_downloader.php -p m -s "" -c "" -g 0.23
```

## Instant download

For example, create `instant_selenium.php`.

```php
<?php
require_once 'vendor/autoload.php';

use SMB\SeleniumDownloader\Downloader;
// Interface
use SMB\SeleniumDownloader\Argument\Optionable;

// Prepare a class that implements the optionable interface.
class InstantSelenium implements Optionable
{
    /**
     * Returns true if option is specified.
     *
     * @return boolean
     */
    public function isSpecified()
    {
        return true;
    }

    /**
     * If true it will output a help message.
     *
     * @return boolean
     */
    public function isSpecifiedHelp()
    {
        return false;
    }

    /**
     * Create help message.
     *
     * @return string
     */
    public function createHelpMessage()
    {
        return '';
    }

    /**
     * Get optional arguments.
     *
     * e.g)
     * <code>
     * return [
     *     'p' => 'w',      // Select platform [m]ac or [w]indows or [l]inux.
     *     'd' => '.',      // Enter the output directory path.
     *     's' => '3.8.1',  // Enter the version of selenium-standalone-server. (e.g 3.8.1, 3.7(3.7.0)
     *     'c' => '2.43',   // Enter the version of ChromeDriver. (e.g 2.43
     *     'g' => '0.23.0', // Enter the version of GeckoDriver. (e.g 0.23(0.23.0), 0.20.1
     *     'i' => '3.14.0', // Enter the version of IEDriverServer. (e.g 3.14(3.14.0)
     *     'b' => '32',     // Enter the number of OS bits (32 or 64).
     * ];
     * </code>
     *
     * @return array
     */
    public function get()
    {
        return [
            'p' => 'm', // Select mac.
            's' => '3.8.1',
            'c' => '2.43',
            'g' => '0.23.0',
        ];
    }
}

$downloader = new Downloader(new InstantSelenium());
$downloader->execute();
```

#### Execute

```
$ php instant_selenium.php
```

![demo-instant](https://github.com/shimabox/assets/raw/master/selenium-downloader/demo-instant.gif)

## Note

If there is one with the same name, it will be overwritten.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
