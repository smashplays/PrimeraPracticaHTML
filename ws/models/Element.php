<?php

include './models/Element.php';

class Element implements IToJson{
    private $name;
    private $description;
    private $serial;
    private $status;
    private $priority;

    public function __consruct($name, $description, $serial, $status, $priority)
    {
        $this->name = $name;
        $this->description = $description;
        $this->serial = $serial;
        $this->status = $status;
        $this->priority = $priority;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getSerial(){
        return $this->serial;
    }

    public function setSerial($serial){
        $this->serial = $serial;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function getPriority(){
        return $this->priority;
    }

    public function setPriority($priority){
        $this->priority = $priority;
    }

    public function toJson($object)
    {
        $json = json_encode($object);
        $file = 'element.json';
        file_put_contents($file, $json, FILE_APPEND);
        echo $json;
    }
}