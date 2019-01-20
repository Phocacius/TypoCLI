<?php

class Templater {

    public static function get($templateFile, $params) {
        $template = file_get_contents(__DIR__.'/templates/'.$templateFile);
        foreach(array_keys($params) as $param) {
            $template = str_replace("{{".$param."}}", $params[$param], $template);
        }
        return $template;
    }

    public static function save($templateFile, $destination, $params) {
        $content = self::get($templateFile, $params);
        file_put_contents($destination, $content);
    }

    public static function append($templateFile, $destination, $params) {
        $content = self::get($templateFile, $params);
        file_put_contents($destination, $content, FILE_APPEND);
    }

    public static function saveIfNotExists($templateFile, $destination, $params) {
        if(!is_file($destination)) {
            self::save($templateFile, $destination, $params);
        }
    }

    public static function from_camel_case($input) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}