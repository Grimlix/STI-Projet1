<?php

function strongPasswordVerify($password){
    $passwordLenght = 15;
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < $passwordLenght) {
        return false;
    }else{
        return true;
    }
}