<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Css
 *
 * @author sinaru
 */
class Css
{

    public $file;
    public $mediaType;

    function link()
    {
        return '<link rel="stylesheet" href="' . Diviya::app()->rootUrl() .
                    '/' . $this->file . '" type="text/css" media="' . $this->mediaType . '">';
    }

}
?>
