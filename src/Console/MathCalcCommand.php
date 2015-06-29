<?php

namespace Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Model\MathCalc;

/**
 * Class MathCalcCommand.
 */
class MathCalcCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('MathCalc')
            ->setDescription('Calculate mathematical expressions.')
            ->addArgument(
                'mathExp',
                InputArgument::REQUIRED,
                'Enter mathematical expression.'
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     * @throws \invalidArgumentException
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mathExp = $input->getArgument('mathExp');
        $output
            ->writeln(
                '<info>Your mathematical expression is:'.PHP_EOL.$mathExp.'</info>'.PHP_EOL
            )
        ;

        $PolNot = MathCalc::convertToPolishNotation($mathExp);

        $output
            ->writeln(
                '<info>Reverse polish notation for your mathematical expression is:'.PHP_EOL.$PolNot.'</info>'.PHP_EOL
            )
        ;

        $result = MathCalc::calc($PolNot);

        $output
            ->writeln(
                '<info>The result of calculation for your mathematical expression is '.$result.'.</info>'.PHP_EOL
            )
        ;
    }
}
