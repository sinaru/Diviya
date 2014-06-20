<?php

/**
 *
 * @author Sinaru Gunawardena
 * @todo write a function for setting JavaScript files like used in css files.
 */
class Application
{

    private $contentLocations = array();
    private $vars = array();
    private $content;
    //variable to hold the body content of the page
    private $css = array();
    //array to hold Css objects
    private $_js = array();
    //array to hold Javascript objects
    private $layout;
    private $_urlManager;
    private $sitename;
    private $pageTitle;
    //array to hold the config data in the config file.
    public $config;
    public $session;
    public $user;

    function __construct()
    {
        $config = include CONFIG_PATH . 'main.php';
        if (is_array($config))
            $this->config = $config;
        else
            die('Config file is not an array');
        
        $this->initConfig();

        $this->pageTitle = '';

        $this->session = new Session();
        $this->user = new SUser($this->session);
        $this->_urlManager = new UrlManager();
    }

    //function to set the config array variable with applicaiton.
    private function initConfig()
    {
        if (isset($this->config['name']))
            $this->sitename($this->config['name']);

        if (isset($this->config['developer-mode']))
        {
            if ($this->config['developer-mode'] == 'on')
            {
                error_reporting(E_ALL);
                ini_set('display_errors', '1');
            }
            else
                ini_set('display_errors', '0');
        }

        else
            ini_set('display_errors', '0');
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    private function getLayout()
    {
        if ($this->layout)
            return VIEW_PATH . 'layout' . DS . $this->layout . '.php';
        else // return the default layout
            return VIEW_PATH . 'layout' . DS . 'main.php';
    }

    public function isTurnedOn($name)
    {
        if (empty($this->config[$name]))
            return false;
        else
            return true;
    }

    public function redirect($array)
    {
        $location = $this->url($array);
        if ($location != NULL)
        {
            header("Location: $location");
            exit;
        }
    }

    public function addHtmlContent($content)
    {
        $this->content .= $content;
    }

    public function run()
    {
        try
        {
            $urlManager = $this->urlMan();
            $controllerClass = ucfirst($urlManager->getController()) . 'Controller';
            $action = 'action' . ucfirst($urlManager->getAction());

			if(!file_exists(CONTROLLER_PATH.$controllerClass.'.php'))
				throw new Error404();
			$controller = new $controllerClass;

            if (!$controller->hasAction($urlManager->getAction()))
                throw new Error404();

            if (!$controller->hasAccess($urlManager->getAction()))
                throw new HtmlException(
                'You Dont have permission to perform this action'
                ,'No privileges'
                );

            $controller->beforeAction();
            //run controller's action
            $controller->$action();
            $controller->afterAction();

            //Check page title is set by Controller
            if (!empty($controller->title))
                $this->setPageTitle($controller->title);

//  New: processing of view files are sent to controller class. Application::addHtmlContent()
//  is used by it to register html output.
//            //Process content locations
//            $i = 0;
//            foreach ($this->contentLocations as $location)
//            {
//                if (!empty($this->vars[$i]))
//                    foreach ($this->vars[$i] as $key => $val)
//                        $$key = $val;
//
//                ob_start();
//                include $location;
//                $this->content .= ob_get_clean();
//
//                $i++;
//            }

            $content = $this->content;
        }
        catch (HtmlException $exc)
        {
            $content = $exc->render();
        }
        catch (SException $exc)
        {
            $content = $exc->getExceptionStack();
        }

        ob_start();
        include $this->getLayout();
        $content = ob_get_clean();
        echo $content;
    }

    public function appendToTitle($string, $withFollowingSpace=false)
    {
        if ($withFollowingSpace)
            $string = ' ' . $string;

        $this->pageTitle .= $string;
    }

    function setPageTitle($title)
    {
        $this->pageTitle = $title;
    }

    function setCss($cssFile, $mediaValue='screen')
    {
        $cssObj = new Css();
        $cssObj->file = CSS_URL . $cssFile . '.css';
        $cssObj->mediaType = $mediaValue;
        $this->css[] = $cssObj;
    }

    function addCss($cssFile, $type='screen')
    {
        $cssObj = new Css();
        $cssObj->file = CSS_URL . $cssFile . '.css';
        $cssObj->mediaType = $type;
        $this->css[] = $cssObj;
        return $cssObj;
    }

    function addJs($jsFile, $type='text/javascript')
    {
        $jsObj = new Js();
        $jsObj->file = JS_URL . $jsFile . '.js';
        $jsObj->type = $type;
        $this->_js[] = $jsObj;
        return $jsObj;
    }

    function cssToHtml()
    {
        $html = '';
        foreach ($this->css as $css)
            $html .= '<link rel="stylesheet" href="' . Diviya::app()->rootUrl() .
                    '/' . $css->file . '" type="text/css" media="' . $css->mediaType . '">';

        return $html;
    }

    function js2HtmlLinks()
    {
        $html = '';
        foreach ($this->_js as $js)
            $html .= '<script src="' . Diviya::app()->rootUrl() . '/' . $js->file .
                    '" type="' . $js->type . '"></script>';

        return $html;
    }

    function rootUrl()
    {
        return 'http://' . SITE_URL;
    }

    function pageTitle()
    {
        return $this->pageTitle;
    }

    /**
     *
     * @param string $view The view name that is used to render.
     * @param array $vars The passed variables that is used in the view.
     */
    function fetchContent($view, $vars=array())
    {
        $this->contentLocations[] = $view;
        $this->vars[] = $vars;
    }

    function siteName($name)
    {
        $this->sitename = $name;
    }

    function name()
    {
        if (!empty($this->sitename))
            return $this->sitename;

        else
            return 'Anonymous';
    }

    function databaseSettings()
    {
        if (isset($this->config['db']))
            return $this->config['db'];
        else
            return false;
    }

    function url($array)
    {
        return $this->urlMan()->generateUrl($array);
    }

    function urlMan()
    {
        return $this->_urlManager;
    }

    function currentUrl()
    {
        return $this->urlMan()->currentUrl();
    }

}

