<?php
namespace SMB\SeleniumDownloader\Argument;

interface Optionable
{
    /**
     * Returns true if option is specified.
	 * 
     * @return boolean
     */
    public function isSpecified();

    /**
     * If true it will output a help message.
	 * 
     * @return boolean
     */
    public function isSpecifiedHelp();

    /**
     * Create help message.
     * 
     * @return string
     */
    public function createHelpMessage();

    /**
     * Get optional arguments.
     * 
     * e.g)
     * <code>
     * return [<br>
     *     'p' => 'w',      // Select platform [m]ac or [w]indows or [l]inux.<br>
     *     'd' => '.',      // Enter the output directory path.<br>
     *     's' => '3.8.1',  // Enter the version of selenium-standalone-server. (e.g 3.8.1, 3.7(3.7.0)<br>
     *     'c' => '2.43',   // Enter the version of ChromeDriver. (e.g 2.43<br>
     *     'g' => '0.23.0', // Enter the version of GeckoDriver. (e.g 0.23(0.23.0), 0.20.1<br>
     *     'i' => '3.14.0', // Enter the version of IEDriverServer. (e.g 3.14(3.14.0)<br>
     *     'b' => '32',     // Enter the number of OS bits (32 or 64).<br>
     * ];
     * </code>
	 * 
     * @return array
     */
    public function get();
}