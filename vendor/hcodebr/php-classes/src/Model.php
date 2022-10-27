<?php

namespace Hcode;

class Model {

    private $values = [];

    public function __call($name,$args)
    {
        $method = substr($name,0,3);//get the name of the method that is being called (either get or set)

        $fieldName = substr($name,3,strlen($name));

        switch ($method) {
            case 'get':
                return (isset($this->values[$fieldName])) ? $this->values[$fieldName] : NULL  ;
                break;

            case 'set':
                $this->values[$fieldName] = $args[0];
                break;
            
            default:
                # code...
                break;
        }

    }

    public function setData($data = array())
    {
        foreach ($data as $key => $value) {
            $this->{"set" . $key}($value);//dynamically creating the methods names and calling them
        }
    }

    public function getValues()
    {
        return $this->values;
    }


}

?>