<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Error404
 *
 * @author sinaru
 */
class Error404 extends HtmlException
{
    public function __construct()
    {

        $homepage = Diviya::app()->rootUrl();
        $appName = Diviya::app()->name();
        Diviya::app()->setPageTitle('Page Not Found: '.$appName);

        $message = "<h3><b>Sorry, the page you requested either doesn't exist or isn't
            available right now!</b></h3> Please check the URL for proper spelling
            and capitalization. If you're having trouble locating
            a destination on $appName, try visiting the <a href='$homepage'>homepage</a>.";

        parent::__construct($message);
    }
}
?>
