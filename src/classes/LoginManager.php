<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginManager
 *
 * @author sinaru
 */
class LoginManager extends DbObject
{
    static $tableName= 'login_records';
    private $correctLogin = false;
    protected static $dbFields= array('username', 'password');
    public  $username;
    //protected  $password;
    private $userId;
  
    public function find($username, $password)
    {
       $record = LoginManager::findBySql("select * from ".LoginManager::$tableName." where username ='$username' and password='$password'");
       if($record)
       {
           $this->userId = $record[0]->getId();
           $this->username = $record[0]->username;
           $this->correctLogin = true;
       }
    }

    public function userId()
    {
        return $this->userId;
    }

    function correctData()
    {
        return $this->correctLogin;
    }
}
?>
