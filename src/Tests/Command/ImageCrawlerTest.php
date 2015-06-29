<?php

namespace Tests;

require_once __DIR__.'/../../../vendor/autoload.php';

use Console\ImageCrawlerCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ImageCrawlerTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorExecute()
    {
        $application = new Application();
        $application->add(new ImageCrawlerCommand());

        $command = $application->find('ImageCrawler');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
                'command' => $command->getName(),
                'url' => 'error',
            ]
        );

        $this->assertRegExp('/Wrong ULR!/', $commandTester->getDisplay());
    }
}
