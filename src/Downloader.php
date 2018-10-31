<?php
namespace SMB\SeleniumDownloader;

use SMB\SeleniumDownloader\Argument\Option;
use SMB\SeleniumDownloader\Argument\Optionable;
use SMB\SeleniumDownloader\Cli\Option as OptionForCli;
use SMB\SeleniumDownloader\Cli\Prompt as PromptForCli;
use SMB\SeleniumDownloader\Proxy\Interactable;
use SMB\SeleniumDownloader\Proxy\Interactor;

/**
 * 
 */
class Downloader
{
    /**
     *
     * @var Optionable
     */
    private $option;

    /**
     * 
     * @var Interactable
     */
    private $interactor;

    /**
     * 
     * @param Optionable $option
     * @param Interactable $interactor
     * 
     * @return void
     */
    public function __construct(
        Optionable $option = null, 
        Interactable $interactor = null
    )
    {
        if (php_sapi_name() !== 'cli') {
            die('Please run at terminal!!');
        }

        if ($option !== null) {
            $this->option = $option;
        } else {
            $this->option = new Option();
        }

        if ($interactor !== null) {
            $this->interactor = $interactor;
        } else {
            $this->interactor = new Interactor();
        }
    }

    /**
     * 
     * @return void
     */
    public function execute()
    {
        if ($this->option->isSpecified()) {
            $this->byOption();
        } else {
            $this->byPrompt();
        }
    }

    /**
     * Download by option.
     * 
     * @return void
     */
    private function byOption()
    {
        $option = new OptionForCli($this->interactor);
        $option->setOption($this->option);
        $option->execute();
    }

    /**
     * Download interactively.
     * 
     * @return void
     */
    private function byPrompt()
    {
        $prompt = new PromptForCli($this->interactor);
        $prompt->execute();
    }
}