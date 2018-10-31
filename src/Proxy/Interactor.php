<?php
namespace SMB\SeleniumDownloader\Proxy;

use League\CLImate\CLImate as LeagueCLImate;

/**
 * Proxy of League\CLImate\CLImate
 */
class Interactor implements Interactable
{
    /**
     *
     * @var LeagueCLImate
     */
    private $climate;

    /**
     * 
     * @param LeagueCLImate $climate
     */
    public function __construct(LeagueCLImate $climate=null)
    {
        if ($climate !== null) {
            $this->climate = $climate;
            return;
        }

        $this->climate = new LeagueCLImate();
    }

    /**
     * 
     * @param string $str
     * 
     * @return mixed
     */
    public function out($str)
    {
        $this->climate->out($str);
    }

    /**
     * 
     * @param string $initialsOfMac     'm'
     * @param string $initialsOfWindows 'w'
     * @param string $initialsOfLinux   'l'
     * 
     * @return string
     */
    public function determinePlatform($initialsOfMac, $initialsOfWindows, $initialsOfLinux)
    {
        $input = $this->climate->input('Please select platform. [' . $initialsOfMac . ']ac, [' . $initialsOfWindows . ']indows, [' . $initialsOfLinux . ']inux:');
        $input->accept([$initialsOfMac, $initialsOfWindows, $initialsOfLinux]);
        $input->strict();

        return $input->prompt();
    }

    /**
     * 
     * @param string $defaultOutputDir
     * 
     * @return string
     */
    public function determineOutputDir($defaultOutputDir)
    {
        $this->out('Please enter the output directory');
        $input = $this->climate->input('Default[' . $defaultOutputDir . ']:');
        $input->defaultTo($defaultOutputDir);

        return $input->prompt();
    }

    /**
     * 
     * @param string $prompt
     * @param string $default e.g) 'N'
     * @param array $acceptList e.g) ['N', 'n', 'y']
     * 
     * @return string Any one of $acceptList
     */
    public function confirmNecessityOfAsset($prompt, $default, array $acceptList)
    {
        $input = $this->climate->input($prompt);
        $input->defaultTo($default);
        $input->accept(function($response) use ($acceptList) {
            return in_array($response, $acceptList, true);
        });

        return $input->prompt();
    }

    /**
     * 
     * @param string $prompt
     * @param string $default
     * 
     * @return string
     */
    public function determiningVersionOfAsset($prompt, $default)
    {
        $input = $this->climate->input($prompt);
        $input->defaultTo($default);
        return $input->prompt();
    }
}