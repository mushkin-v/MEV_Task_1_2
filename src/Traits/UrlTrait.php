<?php

namespace Traits;

/**
 * Class UrlTrait.
 */
trait UrlTrait
{
    /**
     * Корректность ссылки (URL)
     * доп. функция для удаления опасных сиволов.
     *
     * @param $str
     *
     * @return mixed
     */
    public function pregtrim($str)
    {
        return preg_replace("/[^\x20-\xFF]/", '', (string) $str);
    }

    /**
     * проверяет URL и возвращает:
     *  +1, если URL пуст
     *   if (checkurl($url)==1) echo "пусто".
     *
     *  -1, если URL не пуст, но с ошибками
     *   if (checkurl($url)==-1) echo "ошибка"
     *
     *  строку (новый URL), если URL найден и отпарсен
     *   if (checkurl($url)==0) echo "все ок"
     *
     *  либо if (strlen(checkurl($url))>1) echo "все ок"
     *
     * Если протокола не было в URL, он будет добавлен ("http://")
     *
     * @param $url
     *
     * @return int|mixed|string
     */
    public function check_url($url)
    {
        $url = trim($this->pregtrim($url));
        // если пусто - выход
        if (strlen($url) == 0) {
            return 1;
        }

        if (!preg_match(
            '~^(?:(?:https?|ftp|telnet)://(?:[a-z0-9_-]{1,32}'.
            "(?::[a-z0-9_-]{1,32})?@)?)?(?:(?:[a-z0-9-]{1,128}\.)+(?:com|net|".
            'org|mil|edu|arpa|gov|biz|info|aero|inc|name|[a-z]{2})|(?!0)(?:(?'.
            "!0[^.]|255)[0-9]{1,3}\.){3}(?!0|255)[0-9]{1,3})(?:/[a-z0-9.,_@%&".
            "?+=\~/-]*)?(?:#[^ '\"&<>]*)?$~i",
            $url,
            $ok
            )
        ) {
            return -1;
        }
        if (!strstr($url, '://')) {
            $url = 'http://'.$url;
        }
        $url = preg_replace('~^[a-z]+~ie', "strtolower('\\0')", $url);

        return $url;
    }

    /**
     * Проверяет существование ссылки (URL).
     *
     * @param $url
     *
     * @return bool
     */
    public function test_url($url)
    {
        $headers = get_headers($url);
        $count_headers = count($headers);
        for ($i = 0; $i < $count_headers; ++$i) {
            if (strpos($headers[$i], '200') == true) {
                return true;
            }
        }

        return false;
    }
}
