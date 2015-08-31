<?php

namespace Nitixx\Models;


abstract class FullNameEnum extends \SplEnum
{

    public function getAllWithFullName()
    {
        $array = [];
        foreach($this->getConstList() as $const){
            $array[$const] = static::getFullName(static::getByName($const));
        }
        return $array;
    }
}