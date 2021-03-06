<?php

/**
 * Created by PhpStorm.
 * User: ye1.chen
 * Date: 1/7/16
 * Time: 11:22 AM
 */
class Database_Connection
{
    public $_database_name = 'scudsbo2_db';
    public $conn;
    public $conn_state;

    private $_security_key = "1z9kdjhekjndfhjahfuerhianfkjfnakheuroaihn";
    public $_error_security_fail = "error_security_fail";
    public $_error_mobile_data = "error_mobile_data";
    public $_result_account_exist = "result_account_exist";
    public $_result_account_login = "result_account_login";

    /**
     * Set up database connection
     */
    public function databaseConnect()
    {
        $servername = "localhost";
        $username = "scudsbo2_chenye";
        $password = "chenye";

        // Create connection
        $this->conn = new mysqli($servername, $username, $password);

        // Check connection
        if ($this->conn->connect_error) {
            $this->conn_state = false;
            die("Connection failed: " . $this->conn->connect_error);
            exit;
        } else {
            $this->conn_state = true;
        }

        $er = mysqli_select_db($this->conn, $this->_database_name);
        if (!$er) {
            $query = "CREATE DATABASE IF NOT EXISTS $this->_database_name";
            if (!mysqli_query($this->conn, "$query")) {
                print "Error- Could not create database";
                exit;
            }
        }
    }

    /**
     * create database table
     */
    public function createDatabase()
    {
        $this->databaseConnect();
        if ($this->conn_state === true) {
            $scudsbook_user_information = mysqli_query($this->conn, "describe scudsbook_user_information");
            if (!$scudsbook_user_information) {
                mysqli_query($this->conn, "create table scudsbook_user_information(userName varchar(100) primary key NOT NULL,
                                password varchar(50), password_re varchar(50), address_street varchar(100), address_ref varchar(50), address_city varchar(50),
                                address_state char(50), address_zip varchar(30), address_country char(50), user_boa char(30), user_phone char(30))");
            }
            $scudsbook_store_info = mysqli_query($this->conn, "describe scudsbook_store_info");
            if (!$scudsbook_store_info) {
                mysqli_query($this->conn, "create table scudsbook_store_info(store_name varchar(100) primary key NOT NULL, store_rating varchar(10), store_category varchar(10),
                                store_location_lat varchar(20) NOT NULL, store_location_lan varchar(20) ,address_street varchar(100), address_ref varchar(50),
                                address_city varchar(50),address_state char(50), address_zip varchar(30), address_country char(50))");
            }
            $scudsbook_loc = mysqli_query($this->conn, "describe user_location_info");
            if (!$scudsbook_loc) {
                mysqli_query($this->conn, "create table user_location_info(userName varchar(100) primary key NOT NULL, user_lat VARCHAR (50), user_lan VARCHAR (50))");
            }
            $scudsbook_admin = mysqli_query($this->conn, "describe admin_user");
            if (!$scudsbook_admin) {
                mysqli_query($this->conn, "create table admin_user(userName varchar(100) primary key NOT NULL)");
            }
            $scudsbook_order_info = mysqli_query($this->conn, "describe scudsbook_order_info");
            if (!$scudsbook_order_info) {
                mysqli_query($this->conn, "create table scudsbook_order_info(order_id VARCHAR (20) primary key NOT NULL, order_customer_name VARCHAR(50), order_customer_phone VARCHAR (20),
                    order_distance VARCHAR (20), order_address VARCHAR (100), order_city VARCHAR (50), order_state VARCHAR (50), order_zip VARCHAR (20), order_product_cost VARCHAR (20), order_deliver_fee VARCHAR (20),
                    order_tip VARCHAR (20), order_total VARCHAR (20), order_deliver_by VARCHAR (50), order_summary VARCHAR (500), order_time VARCHAR (30), order_submitted_by VARCHAR (30))");
            }
            mysqli_close($this->conn);
            $this->conn_state=false;
        }
    }

    public function addUserToEmailList($userEmail)
    {

    }

    /**
     * Added new user information
     * @param $_userName
     * @param $_password
     * @param $_password_re
     * @param $_address_street
     * @param $_address_ref
     * @param $_address_city
     * @param $_address_state
     * @param $_address_zip
     * @param $_address_country
     * @param $_user_boa
     * @param $_user_phone
     */
    public function addFullUserInfo($_userName, $_password, $_password_re,
                                    $_address_street, $_address_ref, $_address_city, $_address_state, $_address_zip, $_address_country, $_user_boa, $_user_phone)
    {
        $this->databaseConnect();
        if ($_userName == NULL) {
            echo "<script>window.alert(\"You must have a user name, try again!\"),location.href=\"..\userAdd.html\";</script>";
            exit;
        }
        $sql = "SELECT * FROM scudsbook_user_information WHERE userName='$_userName'";
        $result = mysqli_query($this->conn, $sql);
        $rows = mysqli_num_rows($result);
        if ($rows) {
            echo "<script>window.alert(\"User name " . $_userName . " already exist, try again!\"),location.href=\"..\userAdd.html\";</script>";
            exit;
        } else if (($_password == NULL) || ($_password_re == NULL)) {
            echo "<script>window.alert(\"You must have a password!\"),location.href=\"..\userAdd.html\";</script>";
            exit;
        } else if ($_password != $_password_re) {
            echo "<script>window.alert(\"password and confirm not same, try again!\"),location.href=\"..\userAdd.html\";</script>";
            exit;
        } else {
            $sql = "INSERT INTO scudsbook_user_information(userName, password,password_re, address_street, address_ref, address_city, address_state, address_zip, address_country,
                 user_boa, user_phone) VALUES('$_userName', '$_password','$_password_re', '$_address_street', '$_address_ref', '$_address_city', '$_address_state',
                 '$_address_zip', '$_address_country', '$_user_boa', '$_user_phone');";
            $result = mysqli_query($this->conn, $sql);
            echo "<script>window.alert(\"User Added!\"),location.href=\"userLogin.html\";</script>";
        }
        mysqli_close($this->conn);
        $this->conn_state=false;
    }

    /**
     * Added new user information
     * @param $_userName
     * @param $_password
     * @param $_password_re
     * @param $_address_street
     * @param $_address_ref
     * @param $_address_city
     * @param $_address_state
     * @param $_address_zip
     * @param $_address_country
     * @param $_user_boa
     * @param $_user_phone
     */
    public function logInFromMobile($key, $_userName, $_password, $_password_re,
                                    $_address_street, $_address_ref, $_address_city, $_address_state, $_address_zip, $_address_country, $_user_boa, $_user_phone)
    {
        if($key != $this->_security_key) {
            echo $this->_error_security_fail;
            exit;
        }
        $this->databaseConnect();
        if ($_userName == NULL||$_password == NULL) {
            echo $this->_error_mobile_data;
            exit;
        }
        $sql = "SELECT * FROM scudsbook_user_information WHERE userName='$_userName'";
        $result = mysqli_query($this->conn, $sql);
        $rows = mysqli_num_rows($result);
        if ($rows) {
            echo $this->_result_account_exist.",".$result->fetch_assoc()['password'];
            if ($_userName == 'admin@scudsbook.com') {
                $sql = "INSERT INTO admin_user(userName) VALUES('$_userName');";
                $result = mysqli_query($this->conn, $sql);
            }
            exit;
        } else {
            $sql = "INSERT INTO scudsbook_user_information(userName, password,password_re, address_street, address_ref, address_city, address_state, address_zip, address_country,
                 user_boa, user_phone) VALUES('$_userName', '$_password','$_password_re', '$_address_street', '$_address_ref', '$_address_city', '$_address_state',
                 '$_address_zip', '$_address_country', '$_user_boa', '$_user_phone');";
            $result = mysqli_query($this->conn, $sql);
            if ($_userName == 'admin@scudsbook.com') {
                $sql = "INSERT INTO admin_user(userName) VALUES('$_userName');";
                $result = mysqli_query($this->conn, $sql);
            }
            echo $this->_result_account_login;
        }
        mysqli_close($this->conn);
        $this->conn_state=false;
    }

    /**
     * @param $key
     * @param $_userName
     * @param $_lat
     * @param $_lan
     */
    public function userLocationUpdate($key, $_userName, $_lat, $_lan) {
        if($key != $this->_security_key) {
            echo $this->_error_security_fail;
            exit;
        }
        $this->databaseConnect();
        if ($_userName == NULL) {
            echo $this->_error_mobile_data;
            exit;
        }
        $sql = "SELECT * FROM user_location_info WHERE userName='$_userName'";
        $result = mysqli_query($this->conn, $sql);
        $rows = mysqli_num_rows($result);
        if ($rows) {
            $sql = "UPDATE user_location_info SET user_lat='$_lat', user_lan='$_lan' where userName='$_userName'";
            $result = mysqli_query($this->conn, $sql);
            echo "user location updated!";
        } else {
            $sql = "INSERT INTO user_location_info(userName, user_lat,user_lan) VALUES('$_userName', '$_lat','$_lan');";
            $result = mysqli_query($this->conn, $sql);
            //echo $this->_result_account_login;
            echo "user location updated!";
        }
        mysqli_close($this->conn);
        $this->conn_state=false;
    }

    public function orderInfoUpdate($key, $_userName, $order_id, $order_customer_name, $order_customer_phone,
                    $order_distance, $order_address, $order_city, $order_state, $order_zip, $order_product_cost, $order_deliver_fee,
                    $order_tip, $order_total, $order_deliver_by, $order_summary, $order_time) {
        if($key != $this->_security_key) {
            echo $this->_error_security_fail;
            exit;
        }
        $this->databaseConnect();
        if ($_userName == NULL) {
            echo $this->_error_mobile_data;
            exit;
        }
        $sql = "SELECT * FROM scudsbook_order_info WHERE order_id='$order_id'";
        $result = mysqli_query($this->conn, $sql);
        $rows = mysqli_num_rows($result);
        if ($rows) {
            $sql = "UPDATE scudsbook_order_info SET order_customer_name='$order_customer_name', order_customer_phone='$order_customer_phone', order_distance='$order_distance',
                    order_address='$order_address', order_city='$order_city', order_state='$order_state', order_zip='$order_zip', order_product_cost='$order_product_cost',
                    order_deliver_fee='$order_deliver_fee', order_tip='$order_tip', order_total='$order_total', order_deliver_by='$order_deliver_by', order_summary='$order_summary', order_time='$order_time', order_submitted_by='$_userName' where order_submitted_by='$_userName' AND order_id='$order_id'";
            $result = mysqli_query($this->conn, $sql);
            echo "order info updated!";
        } else {
            $sql = "INSERT INTO scudsbook_order_info(order_id,order_customer_name,order_customer_phone,order_distance,order_address,order_city,order_state,order_zip,order_product_cost,
                  order_deliver_fee,order_tip,order_total,order_deliver_by,order_summary,order_time,order_submitted_by) VALUES('$order_id', '$order_customer_name','$order_customer_phone','$order_distance',
                  '$order_address','$order_city','$order_state','$order_zip','$order_product_cost','$order_deliver_fee','$order_tip','$order_total','$order_deliver_by','$order_summary','$order_time','$_userName');";
            $result = mysqli_query($this->conn, $sql);
            echo "order info updated!";
        }
        mysqli_close($this->conn);
        $this->conn_state=false;
    }

    public function orderInfoUpdateDeliver ($key, $_userName, $order_id, $deliver_by) {
        if($key != $this->_security_key) {
            echo $this->_error_security_fail;
            exit;
        }
        $this->databaseConnect();
        if ($_userName == NULL) {
            echo $this->_error_mobile_data;
            exit;
        }
        $sql = "SELECT * FROM scudsbook_order_info WHERE order_submitted_by='$_userName' AND order_id='$order_id'";
        $result = mysqli_query($this->conn, $sql);
        $rows = mysqli_num_rows($result);
        if ($rows) {
            $info = $result->fetch_assoc();
            if ($info['order_deliver_by'] == 'not_set') {
                $sql = "UPDATE scudsbook_order_info SET order_deliver_by='$deliver_by' where order_submitted_by='$_userName' AND order_id='$order_id'";
                $result = mysqli_query($this->conn, $sql);
                echo "order info updated!";
            } else {
                echo "deliver set already!";
            }
        }
    }

    /**
     * @param $key
     */
    public function queryUserList($key){
        if($key != $this->_security_key) {
            echo $this->_error_security_fail;
            exit;
        }
        $this->databaseConnect();
        $sql = "select * from scudsbook_user_information";
        $result = mysqli_query($this->conn, $sql);
        $rows = mysqli_num_rows($result);
        $list = '';
        if ($rows) {
            while($info = $result->fetch_assoc()) {
                $list = $list.$info['userName'].';';
            }
            echo $list;
        } else {
            echo "error:no_user";
        }
    }

    /**
     * @param $key
     * @param $_userName
     */
    public function queryAmdin($key, $_userName){
        if($key != $this->_security_key) {
            echo $this->_error_security_fail;
            exit;
        }
        $this->databaseConnect();
        $sql = "select * from admin_user WHERE userName='$_userName'";
        $result = mysqli_query($this->conn, $sql);
        $rows = mysqli_num_rows($result);
        if ($rows) {
            echo "admin_key";
        } else {
            echo "non_admin_key";
        }
    }

    /**
     * @param $key
     * @param $_userName
     */
    public function queryAddress($key, $_userName){
        if($key != $this->_security_key) {
            echo $this->_error_security_fail;
            exit;
        }
        $this->databaseConnect();
        $sql = "select * from user_location_info WHERE userName='$_userName'";
        $result = mysqli_query($this->conn, $sql);
        $rows = mysqli_num_rows($result);
        if ($rows) {
            $loc = $result->fetch_assoc();
            echo $loc['user_lat'].','.$loc['user_lan'];
        } else {
            echo "error:no_user";
        }
    }

    /**
     * @param $key
     * @param $order_id
     */
    public function queryOrderInfo($key, $order_id, $_userName){
        if($key != $this->_security_key) {
            echo $this->_error_security_fail;
            exit;
        }
        $this->databaseConnect();
        $sql = "select * from scudsbook_order_info WHERE order_submitted_by='$_userName' AND order_id='$order_id'";
        $result = mysqli_query($this->conn, $sql);
        $rows = mysqli_num_rows($result);
        if ($rows) {
            $info = $result->fetch_assoc();
            echo $info['order_id'].';'.$info['order_customer_name'].';'.$info['order_customer_phone'].';'.$info['order_distance'].';'.$info['order_address'].';'.$info['order_city']
                .';'.$info['order_state'].';'.$info['order_zip'].';'.$info['order_product_cost'].';'.$info['order_deliver_fee'].';'.$info['order_tip'].';'.$info['order_total'].';'.$info['order_deliver_by']
                .';'.$info['order_summary'].';'.$info['order_time'].';'.$info['order_submitted_by'];
        } else {
            echo "error:no_user";
        }
    }

    public function queryOrderInfoDeliver($key, $order_id, $_userName){
        if($key != $this->_security_key) {
            echo $this->_error_security_fail;
            exit;
        }
        $this->databaseConnect();
        $sql = "select * from scudsbook_order_info WHERE order_submitted_by='$_userName' AND order_id='$order_id'";
        $result = mysqli_query($this->conn, $sql);
        $rows = mysqli_num_rows($result);
        if ($rows) {
            $info = $result->fetch_assoc();
            echo $info['order_id'].';'.$info['order_customer_name'].';'.$info['order_customer_phone'].';'.$info['order_distance'].';'.$info['order_address'].';'.$info['order_city']
                .';'.$info['order_state'].';'.$info['order_zip'].';'.$info['order_product_cost'].';'.$info['order_deliver_fee'].';'.$info['order_tip'].';'.$info['order_total'].';'.$info['order_deliver_by']
                .';'.$info['order_summary'].';'.$info['order_time'].';'.$info['order_submitted_by'];
        } else {
            echo "error:no_user";
        }
    }

    /**
     * @param $key
     * @param $order_id
     */
    public function queryOrderInfoList($key, $_userName){
        if($key != $this->_security_key) {
            echo $this->_error_security_fail;
            exit;
        }
        $this->databaseConnect();
        $sql = "select * from scudsbook_order_info WHERE order_submitted_by='$_userName'";
        $result = mysqli_query($this->conn, $sql);
        $rows = mysqli_num_rows($result);
        $list = '';
        if ($rows) {
            while($info = $result->fetch_assoc()) {
                $list = $list.$info['order_id'].';'.$info['order_customer_name'].';'.$info['order_customer_phone'].';'.$info['order_distance'].';'.$info['order_address'].';'.$info['order_city']
                    .';'.$info['order_state'].';'.$info['order_zip'].';'.$info['order_product_cost'].';'.$info['order_deliver_fee'].';'.$info['order_tip'].';'.$info['order_total'].';'.$info['order_deliver_by']
                    .';'.$info['order_summary'].';'.$info['order_time'].';'.$info['order_submitted_by'].'}';
            }
            echo $list;
        } else {
            echo "error:no_user";
        }
    }

    public function queryOrderInfoListDeliver ($key, $_userName){
        if($key != $this->_security_key) {
            echo $this->_error_security_fail;
            exit;
        }
        $this->databaseConnect();
        $sql = "select * from scudsbook_order_info WHERE order_deliver_by='$_userName'";
        $sql2 = "select * from scudsbook_order_info WHERE order_deliver_by='not_set'";
        $result = mysqli_query($this->conn, $sql);
        $result2 = mysqli_query($this->conn, $sql2);
        $rows = mysqli_num_rows($result);
        $list = '';
        if ($rows) {
            while($info = $result->fetch_assoc()) {
                $list = $list.$info['order_id'].';'.$info['order_customer_name'].';'.$info['order_customer_phone'].';'.$info['order_distance'].';'.$info['order_address'].';'.$info['order_city']
                    .';'.$info['order_state'].';'.$info['order_zip'].';'.$info['order_product_cost'].';'.$info['order_deliver_fee'].';'.$info['order_tip'].';'.$info['order_total'].';'.$info['order_deliver_by']
                    .';'.$info['order_summary'].';'.$info['order_time'].';'.$info['order_submitted_by'].'}';
            }
        }
        $rows2 = mysqli_num_rows($result2);
        if ($rows2) {
            while($info = $result2->fetch_assoc()) {
                $list = $list.$info['order_id'].';'.$info['order_customer_name'].';'.$info['order_customer_phone'].';'.$info['order_distance'].';'.$info['order_address'].';'.$info['order_city']
                    .';'.$info['order_state'].';'.$info['order_zip'].';'.$info['order_product_cost'].';'.$info['order_deliver_fee'].';'.$info['order_tip'].';'.$info['order_total'].';'.$info['order_deliver_by']
                    .';'.$info['order_summary'].';'.$info['order_time'].';'.$info['order_submitted_by'].'}';
            }
        }
        echo $list;
    }

    /**
     * add Store information table
     * @param $_store_name
     * @param $_store_rating
     * @param $_store_location_lat
     * @param $_store_location_lan
     * @param $_default_address_street
     * @param $_default_address_ref
     * @param $_default_address_city
     * @param $_default_address_state
     * @param $_default_address_zip
     * @param $_default_address_country
     */
    public function addStoreInfo($_store_name, $_store_rating, $_store_category,
                                 $_store_location_lat, $_store_location_lan, $_default_address_street, $_default_address_ref, $_default_address_city, $_default_address_state,
                                 $_default_address_zip, $_default_address_country)
    {
        $this->databaseConnect();
        if ($_store_name == NULL) {
            echo "<script>window.alert(\"You must have a store name, try again!\"),location.href=\"..\userAdd.html\";</script>";
            exit;
        }
        $sql = "SELECT * FROM scudsbook_store_info WHERE store_name='$_store_name'";
        $result = mysqli_query($this->conn, $sql);
        $rows = mysqli_num_rows($result);
        if ($rows) {
            echo "Store name ". $_store_name . " already exist, try again!";
            exit;
        } else {
            $sql = "INSERT INTO scudsbook_store_info (store_name, store_rating, store_category,
                                store_location_lat, store_location_lan,address_street, address_ref,
                                address_city,address_state, address_zip, address_country) VALUES ('$_store_name', '$_store_rating','$_store_category','$_store_location_lat', '$_store_location_lan',
                '$_default_address_street', '$_default_address_ref', '$_default_address_city', '$_default_address_state', '$_default_address_zip', '$_default_address_country');";
            $result = mysqli_query($this->conn, $sql);

            if($result)
                echo "New Store information Added!";
        }
        mysqli_close($this->conn);
        $this->conn_state=false;
    }

    /**
     * update store rating
     *
     * @param $_store_name
     * @param $_store_rating
     */
    public function updateStoreInfo_rating($_store_name, $_store_rating)
    {
        $this->databaseConnect();
        if ($_store_name == NULL) {
            echo "<script>window.alert(\"You must have a store name, try again!\"),location.href=\"..\userAdd.html\";</script>";
            exit;
        }
        $sql = "UPDATE scudsbook_store_info SET store_rating='$_store_rating' where store_name='$_store_name'";
        $result = mysqli_query($this->conn, $sql);
        echo "Store rating updated!";
        mysqli_close($this->conn);
        $this->conn_state=false;
    }

    /**
     * update store category
     * @param $_store_name
     * @param $_store_category
     */
    public function updateStoreInfo_category($_store_name, $_store_category)
    {
        $this->databaseConnect();
        if ($_store_name == NULL) {
            echo "<script>window.alert(\"You must have a store name, try again!\"),location.href=\"..\userAdd.html\";</script>";
            exit;
        }
        $sql = "UPDATE scudsbook_store_info SET store_category='$_store_category' where store_name='$_store_name'";
        $result = mysqli_query($this->conn, $sql);
        echo "Store category updated!";
        mysqli_close($this->conn);
        $this->conn_state=false;
    }

    /**
     * update store location
     * @param $_store_name
     * @param $_store_location_lat
     * @param $_store_location_lan
     * @param $_default_address_street
     * @param $_default_address_ref
     * @param $_default_address_city
     * @param $_default_address_state
     * @param $_default_address_zip
     * @param $_default_address_country
     */
    public function updateStoreInfo_location($_store_name,$_store_location_lat, $_store_location_lan, $_default_address_street, $_default_address_ref, $_default_address_city, $_default_address_state,
                                             $_default_address_zip, $_default_address_country)
    {
        $this->databaseConnect();
        if ($_store_name == NULL) {
            echo "<script>window.alert(\"You must have a store name, try again!\"),location.href=\"..\userAdd.html\";</script>";
            exit;
        }
        $sql = "UPDATE scudsbook_store_info SET store_location_lat='$_store_location_lat', store_location_lan='$_store_location_lan',
            default_address_street='$_default_address_street', default_address_ref='$_default_address_ref', default_address_city='$_default_address_city',
            default_address_state='$_default_address_state', default_address_zip='$_default_address_zip', default_address_country='$_default_address_country' where store_name='$_store_name'";
        $result = mysqli_query($this->conn, $sql);
        echo "Store address updated!";
        mysqli_close($this->conn);
        $this->conn_state=false;
    }

    /**
     * Delet Saved Store information
     * @param $_store_name
     */
    public function deletStoreInfo($_store_name)
    {
        $this->databaseConnect();
        if ($_store_name == NULL) {
            echo "<script>window.alert(\"You must have a store name, try again!\"),location.href=\"..\userAdd.html\";</script>";
            exit;
        }
        $sql = "DELETE FROM scudsbook_store_info WHERE store_name='$_store_name'";
        $result = mysqli_query($this->conn, $sql);
        if(!$result) {
            echo "The Store does not exist!";
        } else {
            echo "The Store ".$_store_name." is deleted already!";
        }
        mysqli_close($this->conn);
        $this->conn_state=false;
        //TODO:delete not using data info
    }
}