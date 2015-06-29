<?php

namespace Console;

use Model\ImageCrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Traits\UrlTrait;

/**
 * Class ImageCrawlerCommand.
 */
class ImageCrawlerCommand extends Command
{
    use UrlTrait;

    protected function configure()
    {
        $this
            ->setName('ImageCrawler')
            ->setDescription('Parse and download all images from URL.')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'Enter URL for parsing.'
            )
            ->addArgument(
                'dirPath',
                InputArgument::OPTIONAL,
                'Enter directory name where you want to download images.'
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \invalidArgumentException
     * @throws \logicException
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');

        $url = $this->check_url($url);

        if (abs($url) === 1 or !$this->test_url($url)) {
            $output->writeln('<error>Error: Wrong ULR!</error>');

            return;
        }

        $dirPath = $input->getArgument('dirPath');

        $output->writeln('<info>Preparing to parse ULR...</info>');
        $progress = new ProgressBar($output, 3);
        $progress->setFormat('<info> %current%/%max% [%bar%] %percent:3s%% </info>');
        $progress->start();
        $progress->setProgress(1);
        $output->writeln(PHP_EOL.'<info>Starting to parse ULR...</info>');

        $crawler = new ImageCrawler($url);
        if ($dirPath) {
            $crawler->setFolder($dirPath);
        }
        $progress->setProgress(2);

        $output->writeln(PHP_EOL.'<info>Handling parsed information...</info>');
        $crawler->domCrawler();

        $progress->setProgress(3);
        $progress->finish();

        $output
            ->writeln(
                PHP_EOL
                .'<info>Parsing of images from '.$url.' was successful.'.PHP_EOL
                .'Parsed images were saved to '
                .$crawler->getFolder().PHP_EOL
                .'Thank you.</info>'
            )
        ;
    }
}
