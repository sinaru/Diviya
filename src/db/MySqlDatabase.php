<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MySqlDatabase
 *
 * @author Sinaru
 */
class MySqlDatabase
{

    private $connection;
    private $lastQuery;

    function openConnection()
    {
        $this->connection = DbConnection::connection();     
    }

    public function closeConnection()
    {
        if(isset($this->connection))
        {
            mysql_close($this->connection);
            unset ($this->connection);
        }
    }

    public function query($query)
    {
        //$this->lastQuery = $query;
        $result = mysql_query($query,  $this->connection);
        $this->confirmQuery($result);
        return $result;
    }

    public function fetchArray($result)
    {
        return mysql_fetch_array($result);
    }

    public function numRows($result)
    {
        return mysql_num_rows($result);
    }

    public function lastInsertId()
    {
        return mysql_insert_id($this->connection);
    }

    private function confirmQuery($result)
    {
        if(!$result)
            throw new HtmlException('['.get_class (). "] - Database query failed: ".mysql_error ());
    }

    function __construct()
    {
        $this->openConnection();
    }

    public function escapeValue($value)
    {
        return mysql_real_escape_string($value);
    }

}

