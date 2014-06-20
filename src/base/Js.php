<?php

/**
 * Description of Js
 *
 * @author sinaru
 */
class Js
{

    public $file;
    public $type;

    function link()
    {
        return '<script src="' . Diviya::app()->rootUrl() . '/' . $this->file . '" type="' . $this->type . '"></script>';
    }
}
?>
