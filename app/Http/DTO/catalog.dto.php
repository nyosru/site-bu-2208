<?php

class CatalogDto {

    private $property1;
    private $property2;
    // Добавьте дополнительные свойства по необходимости

    public function __construct($property1, $property2) {
        $this->property1 = $property1;
        $this->property2 = $property2;
        // Инициализируйте другие свойства, если они есть
    }

    // Геттеры
    public function getProperty1() {
        return $this->property1;
    }

    public function getProperty2() {
        return $this->property2;
    }

    // Сеттеры
    public function setProperty1($property1) {
        $this->property1 = $property1;
    }

    public function setProperty2($property2) {
        $this->property2 = $property2;
    }

    // Можете добавить другие методы, если они нужны
}
