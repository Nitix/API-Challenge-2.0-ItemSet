<?php


namespace models;


class Map extends \SplEnum
{
    /**
     * Any map
     */
    const ANY = 0;

    /**
     * Summoner Rift
     */
    const SR  = 1;

    /**
     * Howling Abyss
     */
    const HA  = 2;

    /**
     * Twisted Treeline
     */
    const TT  = 3;

    /**
     * Crystal Scar
     */
    const CS  = 4;
}