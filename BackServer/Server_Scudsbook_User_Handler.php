<?php
/**
 * Created by PhpStorm.
 * User: ye1.chen
 * Date: 3/1/16
 * Time: 2:17 PM
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_type_user = 'user_request';
    $_type_location = 'location_update';
    $_type_location_query = 'location_query';
    $_user_admin_key = "user_admin_key";

    include_once('../Database/Database_Connection.php');
    $databaseConnection = new Database_Connection();
    $databaseConnection->createDatabase();
    $_key_type = $_POST['_key_type'];
    if($_key_type == 'user_request') {
        $_user_name = $_POST['_user_name'];
        $_password = $_POST['_password'];
        $_key = $_POST['_key_scudsbook'];
        $databaseConnection->logInFromMobile($_key,$_user_name,$_password,"","","","","","","","","");
    } else if($_key_type == $_user_admin_key) {
        $_key = $_POST['_key_scudsbook'];
        $_user_name = $_POST['_user_name'];
        $databaseConnection->queryAmdin($_key, $_user_name);
    } else if($_key_type == $_type_location) {
        $_key = $_POST['_key_scudsbook'];
        $_user_name = $_POST['_user_name'];
        $_lat = $_POST['_location_lat'];
        $_lan = $_POST['_location_lan'];
        $databaseConnection->userLocationUpdate($_key, $_user_name, $_lat, $_lan);
    } else if($_key_type == $_type_location_query) {
        $_key = $_POST['_key_scudsbook'];
        $_user_name = $_POST['_user_name'];
        $databaseConnection->queryAddress($_key, $_user_name);
    } else {
        echo "error!";
    }
}