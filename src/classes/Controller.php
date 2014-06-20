<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author sinaru
 */
class Controller extends BaseController
{

    public $title = '';

    function hasAccess($action)
    {
        $rules = $this->accessRules();

        foreach ($rules as $rule)
        {
            if ($rule['action'] == $action)
            {
                if (isset($rule['allow']) && $rule['allow'] == 'public')
                    return true;

                else if (isset($rule['allow']) && $rule['allow'] == 'private' &&
                        Diviya::app()->user->isLoggedIn())
                    return true;

                else
                    return false;
            }
        }

        return true;
    }

    function redirect($array)
    {
        Diviya::app()->redirect($array);
    }

    function hasAction($action)
    {
        return method_exists($this, 'action' . $action);
    }

    function accessRules()
    {
        return array();
    }

    function actionIndex()
    {
        return;
    }

    function beforeAction()
    {

    }

    function afterAction()
    {

    }

    function render($view, $vars = array())
    {
        $calledController = str_replace('Controller', '', lcfirst(get_called_class()));
        $fullViewPath = VIEW_PATH . DS . $calledController . DS . $view . '.php';

        foreach ($vars as $key => $val)
            $$key = $val;

        ob_start();
        include $fullViewPath;
        Diviya::app()->addhtmlContent(ob_get_clean());
    }

}
?>
