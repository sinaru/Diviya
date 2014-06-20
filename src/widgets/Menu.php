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
class Menu extends Widget{
    private $urls;
    public function __construct($settings)
    {
        foreach ($settings as $key => $value)
        {
            $this->$key = $value;
        }
        parent::__construct();
    }
    
    private function renderItems()
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


            echo '<a href="./index.php?'.$url.'">'.$urlVal.'</a>';
        }
    }

    public function addUrl($url)
    {
        $this->urls[] = $url;
    }

    public function render()
    {
        $this->renderItems();
        parent::render();
    }
}
?>
