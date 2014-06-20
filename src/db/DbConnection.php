<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DbConnection
 *
 * @author sinaru
 */
class DbConnection {

    private $settings;

    function __construct() {
        $this->settings = Diviya::app()->databaseSettings();
    }

    static function connection() {
        $settings = Diviya::App()->databaseSettings();
        if (!$settings)
            throw new HtmlException('Application::databaseSettings() function has returned an empty value.
                                   Make sure you have specified a \'db\' value in your config file');

        $server = $settings['server'];
        $user = $settings['user'];
        $password = $settings['password'];

        $connection = mysql_connect($server, $user, $password);
        if (!$connection)
            throw new HtmlException('MySQL Connection failed: ' . mysql_error ());

        self::selectDatabase($connection);
        return $connection;
    }

    private static function selectDatabase($connection) {
        $settings = Diviya::App()->databaseSettings();
        if (!$settings)
            throw new HtmlException('Application::databaseSettings() function has returned an empty value.
                                   Make sure you have specified a \'db\' value in your config file');

        if (!mysql_select_db($settings['database'], $connection))
        throw new HtmlException("Database selection failed: " . mysql_error ());
    }

}
?>
