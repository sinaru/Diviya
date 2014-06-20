<?php

/**
 * Description of DbObject
 *
 * @author Sinaru
 */
class DbObject
{
    const BELONGS_TO = 1;
    const HAS_MANY = 2;

    private $id;
    public $attributeErrors = array();

    private function setRelation($name, $relation)
    {
        $class = $relation[1];
        $column = $relation[2];

        switch ($relation[0])
        {
            case self::BELONGS_TO:
                $array = $class::findBySql("select * from " . $class::$tableName . " where $column = " . $this->getId());
                $this->$name = isset($array[0]) ? $array[0] : null;
                break;

            case self::HAS_MANY:
                $this->$name = $class::findBySql("select * from " . $class::$tableName . " where $column = " . $this->getId());
                return $this->$name;
                break;

            default:
                break;
        }
    }

    private function insert()
    {
        $sql = "insert into " . static::$tableName . "(";
        $fieldsString = '';

        $class = get_called_class();

        //insert field names to the string
        foreach ($class::$dbFields as $field)
        {
            if (isset($this->$field))
                $fieldsString .= "`$field`, ";
        }
        //remove the last 2 characters of the string {, }
        $fieldsString = substr($fieldsString, 0, strlen($fieldsString) - 2);
        $fieldsString .= ') values (';

        //insert field names to the query
        $sql .= $fieldsString;
        $fieldsString = '';

        //insert field values to the string
        foreach ($class::$dbFields as $field)
        {
            if (isset($this->$field))
                $fieldsString .= "'" . $this->$field . "', ";
        }

        //remove the last 2 characters of the string {, }
        $fieldsString = substr($fieldsString, 0, strlen($fieldsString) - 2);
        $fieldsString .= ');';

        //insert field values and end part of query
        $sql .=$fieldsString;

        $db = new Database();
        return $db->query($sql);
    }

    private function resetAttributeErrors()
    {
        $this->attributeErrors = array();
        foreach ($this::$dbFields as $field)
            $this->attributeErrors[$field] = array();
    }

    private function checkRule($ruleArray)
    {
        $attrList = explode(',', $ruleArray[0]);

        //trim front and trailling spaces from each attribute
        for ($i = 0; $i < count($attrList); $i++)
            $attrList[$i] = trim($attrList[$i]);

        foreach ($attrList as $attr)
        {
            if (!in_array($attr, $this::$dbFields))
                throw new HtmlException('The field ' . $attr . ' in the rules()
                        array is not valid in ' . get_class($this) . ' class.', 'Invalid Field', true);
        }

        $labels = $this->attributeLabels();

        //check 'rule'
        switch ($ruleArray[1])
        {
            case 'required':
                foreach ($attrList as $attr)
                    if (!isset($this->$attr) || empty($this->$attr))
                        $this->attributeErrors[$attr][] = $labels[$attr] . ' can\'t be empty.';
                break;

            case 'length':
                if (isset($ruleArray['max']))
                {
                    foreach ($attrList as $attr)
                    {
                        if (strlen($this->$attr) > $ruleArray['max'])
                            $this->attributeErrors[$attr][] = $labels[$attr] .
                                    ' can\'t be longer than ' . $ruleArray['max'] . ' character(s).';
                    }
                }
                if (isset($ruleArray['min']))
                {
                    foreach ($attrList as $attr)
                    {
                        if (strlen($this->$attr) < $ruleArray['min'])
                            $this->attributeErrors[$attr][] = $labels[$attr] .
                                    ' can\'t be less than ' . $ruleArray['min'] . ' character(s).';
                    }
                }
                break;

            case 'nonum':
                foreach ($attrList as $attr)
                {
                    if (preg_match('*[0-9]*', $this->$attr))
                        $this->attributeErrors[$attr][] = $labels[$attr] .
                                ' can\'t include numbers.';
                }

                break;

            default:
                break;
        }
    }

    private function update()
    {
        $sql = "update " . static::$tableName . " set ";

        $fieldSet = '';

        $class = get_called_class();

        //insert field names and values of them to the string
        foreach ($class::$dbFields as $field)
        {
            if (isset($this->$field))
                $fieldSet .= "`$field` = '" . $this->$field . "', ";
        }
        //remove the last 2 characters of the string {, }
        $fieldSet = substr($fieldSet, 0, strlen($fieldSet) - 2);

        $sql .= $fieldSet;

        $sql .= " where `id` = " . $this->getId() . ";";

        $db = new Database();
        return $db->query($sql);
    }

    public function __get($name)
    {
        $relations = $this->relations();
        if (isset($relations[$name]))
        {
            $this->setRelation($name, $relations[$name]);
            return $this->$name;
        }
    }

    public static function findAll()
    {
        return self::findBySql("select * from " . static::$tableName);
    }

    public static function findById($id=0)
    {
        $resultArray = self::findBySql("select * from " . static::$tableName . " where
                     id = $id limit 1");
        return!empty($resultArray) ? array_shift($resultArray) : false;
    }

    public static function findBySql($sql='')
    {
        $db = new Database();
        $result_set = $db->query($sql);
        $objectArray = array();
        while ($row = $db->fetchArray($result_set)) {
            $objectArray[] = self::instantiate($row);
        }
        return $objectArray;
    }

    /**
     * This function is most commonly used for when a form is submitted for
     * DbObject. By following the convention, the values of the form can be
     * easily to the object.
     * @param array $attributeArray The array that contains the key value pairs
     * that should be inserted into the object as variables and thier values.
     */
    public function fetchData($attributeArray)
    {
        foreach ($attributeArray as $key => $value)
        {
            $this->$key = $value;
        }
    }

    public static function instantiate($record)
    {
        $class = get_called_class();
        $thisClass = get_class();

        if (!is_subclass_of($class, $thisClass))
            die($class . ' is not a sub class of ' . $thisClass);

        $object = new $class;

        foreach ($record as $attribute => $value)
        {
            if (in_array($attribute, $class::$dbFields))
                $object->$attribute = $value;
        }
        $object->setId($record['id']);
        return $object;
    }

    public function relations()
    {
        return array();
    }

    public function rules()
    {
        return array();
    }

    public function validate()
    {
        $this->resetAttributeErrors();

        $rulesArray = $this->rules();
        foreach ($rulesArray as $ruleArray)
        {
            $this->checkRule($ruleArray);
        }


        foreach ($this->attributeErrors as $errorArray)
        {
            if (count($errorArray) > 0)
                return false;
        }

        return true;
    }

    public function beforeSave()
    {
        return true;
    }

    public function afterSave()
    {
        return true;
    }

    public function save()
    {
        if (!$this->beforeSave())
            return false;

        if (!$this->validate())
            return false;

        if (isset($this->id))
        {
            if ($this->update())
                return $this->afterSave();
        }

        else if ($this->insert())
        {
            $db = new Database();
            $this->id = $db->lastInsertId();
            return $this->afterSave();
        }
        else
            return false;
    }

    /**
     *
     * @param <type> $id : The INT value of a primary key of a table. You should
     * avoid trying use this function unless you really know what you are doing.
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

}
