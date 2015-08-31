<?php


namespace Nitixx\Models;

class Map extends FullNameEnum
{
    /**
     * Any map
     */
    const ANY = "any";

    /**
     * Summoner Rift
     */
    const SR  = "SR";

    /**
     * Howling Abyss
     */
    const HA  = "HA";

    /**
     * Twisted Treeline
     */
    const TT  = "TT";

    /**
     * Crystal Scar
     */
    const CS  = "CS";

    /**
     * Give the Map corresponding to the Map
     * @param $text
     *
     * @return Map
     */
    public static function getMap($text)
    {
        switch($text) {
            case Map::ANY:
                return new Map(Map::ANY);
            case Map::SR :
                return new Map(Map::SR);
            case Map::HA :
                return new Map(Map::HA);
            case Map::TT :
                return new Map(Map::TT);
            case Map::CS :
                return new Map(Map::CS);
            default:
                return new Map(Map::ANY);
        }
    }

    /**
     * Give the name of the map
     *
     * @param Map $map
     *
     * @return string
     */
    public static function getFullName(Map $map)
    {
        switch($map) {
            case Map::SR :
                return "Summoner Rift";
            case Map::HA :
                return "Howling Abyss";
            case Map::TT :
                return "Twisted Treeline";
            case Map::CS :
                return "Crystal Scar";
            default:
                return "All Maps ( Who need a map? )";
        }
    }


    /**
     * @inheritDoc
     */
    static function getByName($text)
    {
        return self::getMap($text);
    }
}