<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 2/19/2018
 * Time: 8:47 PM
 */
function create_connection(){
    $conn = new MySqli("localhost", "shivam", "hesoyam26", "ctf");
    if($conn->connect_error){
        echo("Failed to connect to sql server");
        log_error($conn->connect_error);
        return null;
    }
    return $conn;
}
