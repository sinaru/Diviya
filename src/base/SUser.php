<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author sinaru
 */
class SUser
{

    private $userId;
    private $username;
    private $session;
    private $loggedIn;

    private function checkLogin()
    {

        $userId = $this->session->getValue('user_id');
        if ($userId)
        {
            $this->userId = $userId;
            $this->username = $this->session->getValue('username');
            $this->loggedIn = true;
        }
        else
        {
            unset($this->userId);
            $this->loggedIn = false;
        }
    }

    public function __construct($session)
    {
        $this->session = $session;
        $this->checkLogin();
    }

    public function getId()
    {
        return $this->userId;
    }

    public function setLogin($userId, $username)
    {
        $this->session->setValue('user_id',$userId);
        $this->session->setValue('username',$username);
        $this->userId = $userId;
        $this->username = $username;
        $this->loggedIn = true;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function logout()
    {
        $this->session->unsetValue('user_id');
        $this->session->unsetValue('username', $username);
    }

    public function isLoggedIn()
    {
        return $this->loggedIn;
    }

    public function isAdmin()
    {
        return ($this->username == 'admin');
    }
}
?>
