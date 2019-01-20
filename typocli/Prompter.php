<?php

class Prompter {
    private static $instance;

    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Prompter();
        }
        return self::$instance;
    }


    private $args;
    public $command;

    public function __construct() {
        parse_str(implode('&', array_slice($GLOBALS['argv'], 1)), $this->args);
        $vals = array_keys($this->args);
        $this->command = count($vals) > 0 ? $vals[0] : "";
    }

    public function prompt($message) {
        echo $message."\n";
        $ext = trim(fgets(STDIN));
        return $ext;
    }

    public function promptIfNoArgument($argumentName, $helpString) {
        if(array_key_exists($argumentName, $this->args)) {
            return $this->args[$argumentName];
        } else {
            echo $helpString."\n";
            $ext = trim(fgets(STDIN));
            return $ext;
        }
    }

    public function yesNoPrompt($message) {
        echo $message." (y/n):  ";
        $string = strtolower(trim(fgets(STDIN)));
        return in_array($string, ["y", "j", "yes", "ja", "1"]) ? true : false;
    }
}
