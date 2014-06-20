<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Item
 *
 * @author sinaru
 */
class ItemList
{
    private $modelList;
    private $attributes;
    public function __construct($data)
    {
        $this->modelList = $data['list'];
        $this->attributes = $data['attributes'];
    }
}
?>
