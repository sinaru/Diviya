<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Divya
 *
 * @author sinaru
 */
class Diviya {
    
    static $_app = null;
    
    static function app()
    {
        if(empty(self::$_app))
                self::$_app = new Application ();
        
        return self::$_app;
    }
    
    static function url()
    {
        return self::app()->urlMan();
    }

    static function about()
    {
        return "Diviya Framework is developed for PHP driven websites to use
            as a tool to quickly develop the website. It is following
            MVC strucutre to ease the development process.";
    }
}
?>
