<?php

namespace SMB\SeleniumDownloader\Tests;

/**
 * Helper
 */
trait Helper
{
    /**
     * 
     * @param array $setMethods
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getInteractorMock(array $setMethods=[
        'out',
        'determinePlatform',
        'determineOutputDir',
        'confirmNecessityOfAsset',
        'determiningVersionOfAsset',
        'quit',
    ])
    {
        return $this->getMockBuilder('SMB\SeleniumDownloader\Proxy\Interactor')
             ->setMethods($setMethods)
             ->getMock();
    }

    /**
     * 
     * @param array $setMethods
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getOptionMock(array $setMethods=[
        'isSpecified',
        'isSpecifiedHelp',
        'createHelpMessage',
        'get',
    ])
    {
        return $this->getMockBuilder('SMB\SeleniumDownloader\Argument\Option')
             ->setMethods($setMethods)
             ->getMock();
    }
}