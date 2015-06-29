<?php

namespace Tests;

require_once __DIR__.'/../../../vendor/autoload.php';

use Console\MathCalcCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class MathCalcCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new MathCalcCommand());

        $command = $application->find('MathCalc');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
                'command' => $command->getName(),
                'mathExp' => '(2+2)*7',
            ]
        );

        $this->assertRegExp('/The result of calculation for your mathematical expression is 28./', $commandTester->getDisplay());
    }
}
