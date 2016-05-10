<?php
/**
 * Created by PhpStorm.
 * User: ye1.chen
 * Date: 3/1/16
 * Time: 2:17 PM
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once('../Database/Database_Connection.php');
    $databaseConnection = new Database_Connection();
    $databaseConnection->createDatabase();
    $_user_name = $_POST['_user_name'];
    $_password = $_POST['_password'];
    $_key = $_POST['_key_scudsbook'];
    $databaseConnection->logInFromMobile($_key,$_user_name,$_password,"","","","","","","","","");
}