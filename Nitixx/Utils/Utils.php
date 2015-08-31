<?php


namespace Nitixx\Utils;


class Utils
{
    const BASE_URL_DATA_DRAGON = '//ddragon.leagueoflegends.com/cdn/';

    /**
     * Return the image url from data dragon
     * @param string $version Version of data Dragon
     * @param string $group Group of the image (ex: spell)
     * @param string $name Name of the image
     * @return string Url of the image
     */
    public static function getDataDragonImage($version, $group, $name)
    {
        return self::BASE_URL_DATA_DRAGON . $version . '/img/' . $group . '/' .$name;
    }

    public static function create404error(){
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    }

    public static function getConstantsOf($class)
    {
        $rclass = new \ReflectionClass($class);
        return $rclass->getConstants ();
    }
}