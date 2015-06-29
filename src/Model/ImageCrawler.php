<?php

namespace Model;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ImageCrawler.
 */
class ImageCrawler
{
    /**
     * @var string
     */
    private $folder;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $html;

    /**
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->html = file_get_contents($this->url);
        $this->setFolder();
    }

    /**
     * @param string $folder
     */
    public function setFolder($folder = './web/images/')
    {
        if (!file_exists($folder)) {
            if (substr($folder, -1) !== '/') {
                $folder .= '/';
            }
            mkdir($folder);
            chmod($folder, 0777);
        }
        $this->folder = $folder;
    }

    /**
     * @return mixed
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Parse images function.
     */
    public function domCrawler()
    {
        $crawler = new Crawler($this->html);
        $result = $crawler
            ->filterXPath('//img')
            ->extract(array('src'));

        foreach ($result as $image) {
            if (preg_match('/\?/', $image)) {
                $image = strstr($image, '?', true);
            }
            $path = $this->folder.basename($image);

            //Variants of real url for image
            $image1 = $image;
            $image2 = preg_replace("/^\/\/?/", 'http://', $image);
            $image3 = preg_replace("/^\/\/?/", 'http://'.parse_url($this->url)['host'].'/', $image);
            $image4 = 'http://'.parse_url($this->url)['host'].'/'.$image;

            if ($content = @file_get_contents($image1)) {

                echo $this->save_parsed_file($path, $content, $image1);

            } elseif ($content = @file_get_contents($image2)) {

                echo $this->save_parsed_file($path, $content, $image2);

            } elseif ($content = @file_get_contents($image3)) {

                echo $this->save_parsed_file($path, $content, $image3);

            } elseif ($content = @file_get_contents($image4)) {

                echo $this->save_parsed_file($path, $content, $image4);

            } else {
                echo 'Error:Failed to save file '.$image.' to folder'.PHP_EOL;
            }
        }
    }

    /**
     * @param $path
     * @param $content
     * @param $image
     * @return string
     */
    public function save_parsed_file($path, $content, $image) {
        file_put_contents($path, $content);
        chmod($path, 0666);

        return $image.' - Ok!'.PHP_EOL;
    }
}
