<?php
class Validation
{
    public static function validateEmpty($value) {
        return empty($value);
    }

    public static function validateEmail($value){
        if(filter_var($value, FILTER_VALIDATE_EMAIL)){
            return true;
        }else{
            return false;
        }
    }


    public static function validateUserName($username) {
        return preg_match('/^[a-zA-Z0-9]+$/', $username) === 1;
    }

    public static function validatePhone($phone) {
        return preg_match('/^[0-9]+$/', $phone) === 11;
    }

    public static function validatePassword($password) {
        return strlen($password) >= 8 && strlen($password) <= 25;
    }
}
