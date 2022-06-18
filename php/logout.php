<?php


function logoutUser(){
    session_start();
    if(isset($_SESSION)){
        session_destroy();
        header("Location: ../forms/login.html");
        exit;
    }
}
logoutUser();

?>