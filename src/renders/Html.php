<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Html
 *
 * @author Sinaru
 */
class Html
{

    public static function link($name, $array)
    {
        echo '<a href="' .Diviya::app()->url($array). '">' . $name . '</a>';
    }

}
