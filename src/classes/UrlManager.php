<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UrlManager
 *
 * @author sinaru
 */
class UrlManager
{

    private $controller;
    private $action;

    function getController()
    {
        if (!empty($_GET['r']))
        {
            $arr = array();
            $arr = explode('/', $_GET['r']);

            if (!empty($arr[0]))
                $this->controller = $arr[0];
        }
        else if (Diviya::app()->isTurnedOn('seo_friendly_url'))
        {
            $arr = array();

            $arr = explode('/', substr(Diviya::app()->currentUrl(), strlen($_SERVER['SCRIPT_NAME']) + 1));

            if (!empty($arr[0]))
                $this->controller = $arr[0];
            else
                $this->controller = 'site';

            for ($i = 2; $i < count($arr) - 1; $i++)
                $_GET[$arr[$i]] = $arr[$i + 1];
        }

        else
        {
            $this->controller = 'site';
        }

        return $this->controller;
    }

    function getAction()
    {
        if (!empty($_GET['r']))
        {
            $arr = array();

            $arr = explode('/', $_GET['r']);

            if (!empty($arr[1]))
                $this->action = $arr[1];
            else
                $this->action = 'index';
        }

        else if (Diviya::app()->isTurnedOn('seo_friendly_url'))
        {
            $arr = array();

            $arr = explode('/', substr(Diviya::app()->currentUrl(), strlen($_SERVER['SCRIPT_NAME']) + 1));

            if (!empty($arr[1]))
                $this->action = $arr[1];
            else
                $this->action = 'index';

            for ($i = 2; $i < count($arr) - 1; $i++)
                $_GET[$arr[$i]] = $arr[$i + 1];
        }
        else
        {
            $this->action = 'index';
        }
        return $this->action;
    }

    function generateUrl($array)
    {
        $finalUrl = '';

        if (Diviya::app()->isTurnedOn('seo_friendly_url'))
        {
            $options = "";
            $pathval = $array[0];
            if (isset($array[1]))
                foreach ($array[1] as $key => $value)
                {
                    $options .= '/' . $key . '/' . $value;
                }

            $url = '/index.php/' . $pathval . $options;


            $finalUrl = Diviya::app()->rootUrl() . $url;
        }
        else
        {
            $options = "";
            $pathval = $array[0];

            if (isset($array[1]))
                foreach ($array[1] as $key => $value)
                {
                    $options .= '&' . $key . '=' . $value;
                }

            $url = 'r=' . $pathval . $options;


            $finalUrl = Diviya::app()->rootUrl() . '/index.php?' . $url;
        }

        return $finalUrl;
    }

    function currentUrl()
    {
        return $_SERVER['REQUEST_URI'];
    }

}
?>
