<?php

/**
 * Description of SuperfishMenu
 * This class is designed to abstract the SuperfishMenu for the framework
 * 'url'    : the url of the link
 * 'menu'   : the text that should appear on the menu box
 * 'params' : the array of parameters that should appear in the url
 * 
 *
 * @author sinaru
 */
class SuperfishMenu extends Widget
{

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
        $html = '<ul class="sf-menu">';
        foreach ($this->urls as $url)
        {
            if (!isset($url['url']))
                $html .= '<li><a >' . $url['menu'] . '</a>' . $this->renderSubmenu($url) . "</li>\n";
            else
            {
                $menu = array();
                $menu[] = $url['url'];
                if (isset($url['params']))
                {
                    $menu[] = array();
                    $menu[1] = $url['params'];
                }

                $html .= '<li><a href="' . Diviya::app()->url($menu) .
                        '">' . $url['menu'] . "</a></li>\n";
            }
        }
        $html .= '</ul>';

        echo $html;
    }

    private function renderSubmenu(&$submenu)
    {
        $html = '<ul>';
        foreach ($submenu as $menuItem)
        {
            if (is_array($menuItem))
            {
                if (isset($menuItem['url']))
                {
                    $menu = array();
                    $menu[] = $menuItem['url'];
                    if (isset($menuItem['params']))
                    {
                        $menu[] = array();
                        $menu[1] = $menuItem['params'];
                    }

                    $html .= '<li><a href="' . Diviya::app()->url($menu) .
                            '">' . $menuItem['menu'] . "</a></li>\n";
                }
                else
                    $html .= '<li><a >' . $menuItem['menu'] . '</a>' . $this->renderSubmenu($menuItem) . "</li>\n";
            }
        }

        $html.='</ul>';
        return $html;
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
