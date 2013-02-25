<?php

namespace helpers;

class ContainerDi {

    private static function getDb() {
        $db = new \PDO("mysql:host=localhost;dbname=phptdd", "root", "root");
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $db;
    }

    public static function getObject($name, $data = "") {
        if ($data)
            $objct = new $name(self::getDb(), $data);
        else
            $objct= new $name(self::getDb());
        return $objct;
    }
}