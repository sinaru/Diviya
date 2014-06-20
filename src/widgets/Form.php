<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Form
 *
 * @author Sinaru
 */
class Form
{
    private $legend ='';
    private $method;
    private $_params;

    public $model;
    public function __construct($model, $method='post', $params = array())
    {
        $this->method = $method;
        $this->model = $model;
        $this->_params = $params;
    }

    private function getValue($attribute)
    {
        $value = '';

        if(isset($this->model->$attribute))
                $value = $this->model->$attribute;

        return $value;
    }
    
    public function setLegend($name)
    {
        $this->legend = $name;
    }

    public function beginWidget()
    {
        $settings = '';
        foreach ($this->_params as $key => $value)
        {
            $settings.= $key.'="'.$value.'" ';
        }
        echo '<form method="'.$this->method.'" action="'.Diviya::url()->currentUrl().'" '.$settings.'>';
        echo "\n<fieldset>";
        if(!empty($this->legend))
                echo "<legend>$this->legend</legend>";
    }

    public function errors()
    {
        echo '<p>';
        $errors = $this->model->attributeErrors;

        echo'<ul>';
        foreach ($errors as $attrErrs)
        {
            foreach ($attrErrs as $err)
            {
                echo '<li>'.$err.'</li>';
            }
        }
        echo '</ul>';
        echo '</p>';
    }
    
    public function label($attribute)
    {
        $attributeLabels = $this->model->attributeLabels();
        if(isset($attributeLabels[$attribute]))
            $label = $attributeLabels[$attribute];
        else
            $label = $attribute;

        return '<label for="'.$this->modelClass().'['.$attribute.']">'.$label."</label>\n";
    }
    
    public function textField($attribute)
    {
        return '<input type="text" class="text" name="'.$this->modelClass().'['.$attribute.']"
               value="'.$this->getValue($attribute).'">';
    }

    public function fileField($attribute)
    {
        return '<input type="file" name="'.$this->modelClass().'['.$attribute.']">';
    }

    public function textArea($attribute, $rows=20, $cols=10)
    {
        return '<textarea rows="'.$rows.'" cols="'.$cols.'" name="'.$this->modelClass().'['.$attribute.']">'.$this->getValue($attribute).'</textarea>';
    }
    
    private function modelClass()
    {
        return get_class($this->model);
    }
    
    public function submit($name='')
    {
        if(!$name)
            $name = 'Submit';
        
        return '<input type="submit" value="'.$name.'">';
    }
    
    public function endWidget()
    {
        echo '</fieldset>';
        echo '</form>';
    }
}

?>
