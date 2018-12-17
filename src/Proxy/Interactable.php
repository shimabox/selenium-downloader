<?php
namespace SMB\SeleniumDownloader\Proxy;

interface Interactable
{
    /**
     * 
     * @param string $str
     * 
     * @return mixed
     */
    public function out($str);

    /**
     * 
     * @param string $initialsOfMac     'm'
     * @param string $initialsOfWindows 'w'
     * @param string $initialsOfLinux   'l'
     * 
     * @return string
     */
    public function determinePlatform($initialsOfMac, $initialsOfWindows, $initialsOfLinux);

    /**
     * 
     * @param string $defaultOutputDir
     * 
     * @return string
     */
    public function determineOutputDir($defaultOutputDir);

    /**
     * 
     * @param string $prompt
     * @param string $default e.g) 'N'
     * @param array $acceptList e.g) ['N', 'n', 'y']
     * 
     * @return string Any one of $acceptList
     */
    public function confirmNecessityOfAsset($prompt, $default, array $acceptList);

    /**
     * 
     * @param string $prompt
     * @param string $default
     * 
     * @return string
     */
    public function determiningVersionOfAsset($prompt, $default);

    /**
     * @return void
     */
    public function quit();
}