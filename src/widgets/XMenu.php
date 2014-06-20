<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Menu
 *
 * @author sinaru
 */
class XMenu extends Widget{
    private $urls;
    public function __construct($settings)
    {
        global $app;
        echo '<link rel="stylesheet" type="text/css" href="' . CSS_URL . '/dropdown/dropdown.css" media="screen, projection" />';
        echo '<link rel="stylesheet" type="text/css" href="' . CSS_URL . 'dropdown/themes/water/default.css" media="screen, projection" />';
        foreach ($settings as $key => $value)
        {
            $this->$key = $value;
        }
        parent::__construct();
    }
    
    private function renderList()
    {
        foreach ($this->urls as $url)
        {
            $options = "";
            foreach ($url as $key => $value)
            {    
                if($key == 'path')
                    $pathval = $value;
                else if($key=='value')
                    $urlVal = $value;
               
                else
                {
                    $options .= '&'.$key.'='.$value;
                }

            }
            if(empty($pathval) || empty($urlVal))
                die('Menu item path or/and urlVal not defined:Menu --43');
            $url =  'r='.$pathval.$options;


            echo '<li><a href="./index.php?'.$url.'">'.$urlVal.'</a><li>'.newline();
        }
    }

    public function addUrl($url)
    {
        $this->urls[] = $url;
    }

    public function render()
    {
        echo '<ul id="nav" class="dropdown dropdown-horizontal">';
        $this->renderList();
        echo '</ul>';
        parent::render();
    }
}
?>
