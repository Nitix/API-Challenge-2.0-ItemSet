<?php


namespace Nitixx\Models;

class Mode extends FullNameEnum
{
    /**
     * Any mode
     */
    const ANY     = "any";

    /**
     * Classic mode
     */
    const CLASSIC = "CLASSIC";

    /**
     * Dominion
     */
    const ODIN    = "ODIN";

    /**
     * Aram
     */
    const ARAM    = "ARAM";

    /**
     * Ascension
     */
    const ASCENSION = "ASCENSION";

    /**
     * Give the mode corresponding to a text
     * @param $text
     *
     * @return Mode
     */
    public static function getMode($text) {
        switch($text) {
            case Mode::ANY:
                return new Mode(Mode::ANY);
            case Mode::CLASSIC:
                return new Mode(Mode::CLASSIC);
            case Mode::ARAM:
                return new Mode(Mode::ARAM);
            case Mode::ODIN:
                return new Mode(Mode::ODIN);
            case Mode::ASCENSION :
                return new Mode(Mode::ASCENSION);
            default:
                return new Mode(Mode::ANY);
        }
    }

    /**
     * Give the name of the mode
     *
     * @param Mode $mode
     * @return string
     */
    public static function getFullName(Mode $mode)
    {
        switch($mode) {
            case Mode::CLASSIC:
                return "Classic";
            case Mode::ARAM:
                return "ARAM";
            case Mode::ODIN:
                return "Dominion";
            case Mode::ASCENSION:
                return "Ascension";
            default:
                return "All modes";
        }
    }


    /**
     * @inheritDoc
     */
    static function getByName($text)
    {
        return self::getMode($text);
    }
}