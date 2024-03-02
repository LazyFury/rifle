<?php

namespace Common\Utils;

class OpenApiParam{
    public $name;
    public $in;
    public $description;
    public bool $required;
    public $schema;
    public $example;
    public $deprecated;
    public $allowEmptyValue;
    public $allowReserved;
    public $explode;
    public $style;

    // serialize( )

    public function __construct($name, $in, $description, $required = true, $schema = null, $example = null, $deprecated = false, $allowEmptyValue = false, $allowReserved = false, $explode = true, $style = null)
    {
        $this->name = $name;
        $this->in = $in;
        $this->description = $description;
        $this->required = $required;
        $this->schema = $schema;
        $this->example = $example;
        $this->deprecated = $deprecated;
        $this->allowEmptyValue = $allowEmptyValue;
        $this->allowReserved = $allowReserved;
        $this->explode = $explode;
        $this->style = $style;
    }

    public function serialize(){
        return [
            "name" => $this->name,
            "in" => $this->in,
            "description" => $this->description,
            "required" => $this->required,
            "schema" => $this->schema,
            "example" => $this->example,
            "deprecated" => $this->deprecated,
            "allowEmptyValue" => $this->allowEmptyValue,
            "allowReserved" => $this->allowReserved,
            "explode" => $this->explode,
            "style" => $this->style,
        ];
    }

}
