<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Widget
 *
 * @author sinaru
 */
abstract  class Widget
{

    private $rendered = false;

    public function __construct($autostart=false)
    {
        if ($autostart)
            $this->start();
    }

    function start()
    {
        if (!$this->rendered)
        {
            //echo "<div>";
            $this->rendered = true;
            $this->render();
        }
    }

    function end()
    {
        //echo '</div>';
    }

    public  function render()
    {
        $this->rendered = true;
    }

    function  __destruct()
    {
        if(!$this->rendered)
        {
            $this->start();
            $this->end();
        }
    }

}
?>
