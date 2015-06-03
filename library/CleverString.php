<?php

class CleverString {

    private $_theString = "";
    private static $_allowedFunctions = array("strlen", "strtoupper", "strpos",
        "htmlentities", "strip_tags", "htmlspecialchars", "stripslashes");

    public function setString($stringVal) {
        $this->_theString = $stringVal;
    }

    public function getString() {
        return $this->_theString;
    }

    public function __call($methodName, $arguments) {
        
        if (in_array($methodName, CleverString::$_allowedFunctions)) {
            array_unshift( $arguments, $this->_theString );
            return call_user_func_array($methodName, $arguments);
        } else {
            die("<p>Method {$methodName} doesn't exist</p>");
        }
    }

}
