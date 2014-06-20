<?php
class Session
{
    public function __construct()
    {
        session_start();
    }
    public function setValue($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function getValue($key)
    {
        if (isset($_SESSION[$key]))
            return $_SESSION[$key];
        else
           return null;
    }

    public function unsetValue($key)
    {
        unset ($_SESSION[$key]);
    }

}
?>
