<?php

namespace QInterface\Libs;

use Config;

abstract class Eloquent extends \Illuminate\Database\Eloquent\Model
{
    /**
     * Returns the name of the table of the model
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->table;
    }

    /**
     * Return the database name of the connection the model's using
     *
     * @return string
     */
    public function getDatabaseName()
    {
        return Config::get('database.connections.' . $this->connection . '.database');
    }

    /**
     * Returns the total entries of a model that should be fetched per page
     *
     * @return int
     */
    public static function perPage()
    {
        return (new static)->getPerPage();
    }
}
