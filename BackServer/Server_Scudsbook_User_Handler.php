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
    $_type_order_info_update = "order_info_update";
    $_type_manager_order_info_query = "manager_order_info_query";
    $_type_manager_order_info_list_query = "manager_order_info_list_query";
    $_type_deliver_order_info_query = "deliver_order_info_query";
    $_type_deliver_order_info_list_query = "deliver_order_info_list_query";
    $_type_set_deliver_by_deliver = "set_deliver_by_deliver";
    $_type_user_list_query = "user_list_query";

    include_once('../Database/Database_Connection.php');
    $databaseConnection = new Database_Connection();
    $databaseConnection->createDatabase();
    $_key_type = $_POST['_key_type'];
    if ($_key_type == 'user_request') {
        $_user_name = $_POST['_user_name'];
        $_password = $_POST['_password'];
        $_key = $_POST['_key_scudsbook'];
        $databaseConnection->logInFromMobile($_key, $_user_name, $_password, "", "", "", "", "", "", "", "", "");
    } else if ($_key_type == $_user_admin_key) {
        $_key = $_POST['_key_scudsbook'];
        $_user_name = $_POST['_user_name'];
        $databaseConnection->queryAmdin($_key, $_user_name);
    } else if ($_key_type == $_type_location) {
        $_key = $_POST['_key_scudsbook'];
        $_user_name = $_POST['_user_name'];
        $_lat = $_POST['_location_lat'];
        $_lan = $_POST['_location_lan'];
        $databaseConnection->userLocationUpdate($_key, $_user_name, $_lat, $_lan);
    } else if ($_key_type == $_type_location_query) {
        $_key = $_POST['_key_scudsbook'];
        $_user_name = $_POST['_user_name'];
        $databaseConnection->queryAddress($_key, $_user_name);
    } else if ($_key_type == $_type_order_info_update) {
        $_key = $_POST['_key_scudsbook'];
        $_user_name = $_POST['_user_name'];
        $order_id = $_POST['key_order_info_id'];
        $order_customer_name = $_POST['key_order_info_customer_name'];
        $order_customer_phone = $_POST['key_order_info_customer_phone'];
        $order_distance = $_POST['key_order_info_distance'];
        $order_address = $_POST['key_order_info_address'];
        $order_city = $_POST['key_order_info_city'];
        $order_state = $_POST['key_order_info_state'];
        $order_zip = $_POST['key_order_info_zip'];
        $order_product_cost = $_POST['key_order_info_product_cost'];
        $order_deliver_fee = $_POST['key_order_info_deliver_fee'];
        $order_tip = $_POST['key_order_info_tip'];
        $order_total = $_POST['key_order_info_total'];
        $order_deliver_by = $_POST['key_order_info_deliver_by'];
        $order_summary = $_POST['key_order_info_summary'];
        $order_time = $_POST['key_order_info_time'];
        $databaseConnection->orderInfoUpdate($_key, $_user_name, $order_id, $order_customer_name, $order_customer_phone,
            $order_distance, $order_address, $order_city, $order_state, $order_zip, $order_product_cost, $order_deliver_fee,
            $order_tip, $order_total, $order_deliver_by, $order_summary, $order_time);
    } else if ($_key_type == $_type_manager_order_info_query) {
        $_key = $_POST['_key_scudsbook'];
        $order_id = $_POST['key_order_info_id'];
        $_user_name = $_POST['_user_name'];
        $databaseConnection->queryOrderInfo($_key, $order_id, $_user_name);
    } else if ($_key_type == $_type_manager_order_info_list_query) {
        $_key = $_POST['_key_scudsbook'];
        $_user_name = $_POST['_user_name'];
        $databaseConnection->queryOrderInfoList($_key, $_user_name);
    } else if ($_key_type == $_type_deliver_order_info_query) {
        $_key = $_POST['_key_scudsbook'];
        $order_id = $_POST['key_order_info_id'];
        $_user_name = $_POST['_user_name'];
        $databaseConnection->queryOrderInfoDeliver($_key, $order_id, $_user_name);
    } else if ($_key_type == $_type_deliver_order_info_list_query) {
        $_key = $_POST['_key_scudsbook'];
        $_user_name = $_POST['_user_name'];
        $databaseConnection->queryOrderInfoListDeliver($_key, $_user_name);
    } else if ($_key_type == $_type_set_deliver_by_deliver) {
        $_key = $_POST['_key_scudsbook'];
        $_user_name = $_POST['_user_name'];
        $deliver_by = $_POST['_deliver_by'];
        $order_id = $_POST['key_order_info_id'];
        $databaseConnection->orderInfoUpdateDeliver($_key, $_user_name, $order_id, $deliver_by);
    } else if ($_key_type == $_type_user_list_query) {
        $_key = $_POST['_key_scudsbook'];
        $databaseConnection->queryUserList($_key);
    } else {
        echo "error!";
    }
}