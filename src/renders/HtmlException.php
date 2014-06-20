<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HtmlException
 *
 * @author sinaru
 */
class HtmlException extends SException
{
    private $heading;
    private $showStack;

    public function  __construct($message,$heading = '', $showStack = false)
    {
        $this->heading = $heading;
        $this->showStack = $showStack;
        parent::__construct($message);
    }
    public function render()
    {
        $html = '';
        if($this->heading)
                $html = "<h2>$this->heading</h2>";
        $html .= "<p>$this->message</p>";
        $html .= '<br/><br/>';

        if($this->showStack)
        $html .= '<h3>Exception Stack</h3>'. $this->getExceptionStack();
        return $html;
    }
}
?>
