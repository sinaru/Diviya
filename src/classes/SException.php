<?php
class SException extends Exception
{
    public function getExceptionStack()
    {
       $array = $this->getTrace();
       $html ='';

       foreach ($array as $value)
       {
           foreach ($value as $key=> $value)
           {
               $html.= $key .'----'.$value.'<br/>';
           }
       }
       return $html;
    }
}
