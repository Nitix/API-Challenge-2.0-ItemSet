<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 28/08/2015
 * Time: 19:01
 */

namespace Nitixx\Models;


interface DBObjectInterface
{
    /**
     * Save the current object to the database
     * @throws \PDOException
     */
    function save();

    /**
     * Delete the current object to the database
     * This can also delete linked object
     * @throws \PDOException
     */
    function delete();
}