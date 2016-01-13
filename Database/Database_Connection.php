<?php

/**
 * Created by PhpStorm.
 * User: ye1.chen
 * Date: 1/7/16
 * Time: 11:22 AM
 */
class Database_Connection
{
    public $_database_name = 'scudsbook';
    public $conn;
    public $conn_state;

    /**
     * Set up database connection
     */
    public function databaseConnect()
    {
        $servername = "localhost";
        $username = "chenye2016";
        $password = "chenye2016";

        // Create connection
        $this->conn = new mysqli($servername, $username, $password);

        // Check connection
        if ($this->conn->connect_error) {
            $this->conn_state = false;
            die("Connection failed: " . $this->conn->connect_error);
            exit;
        } else {
            $this->conn_state = true;
            echo "Connected successfully";
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
                mysqli_query($this->conn, "create table scudsbook_store_info(store_name varchar(100) primary key NOT NULL, store_rating varchar(10),
                                store_location_lat varchar(20) NOT NULL, store_location_lan varchar(20) ,address_street varchar(100), address_ref varchar(50),
                                address_city varchar(50),address_state char(50), address_zip varchar(30), address_country char(50))");
            }
            mysqli_close($this->conn);
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
            $sql = "INSERT INTO userInfo(userName, password,password_re, address_street, address_ref, address_city, address_state, address_zip, address_country,
                 user_boa, user_phone) VALUES('$_userName', '$_password','$_password_re', '$_address_street', '$_address_ref', '$_address_city', '$_address_state',
                 '$_address_zip', '$_address_country', '$_user_boa', '$_user_phone')";
            $result = mysqli_query($sql);
            echo "<script>window.alert(\"User Added!\"),location.href=\"userLogin.html\";</script>";
        }
    }

    public function addStoreInfo($_store_name, $_store_rating,
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
            echo "<script>window.alert(\"Store name " . $_store_name . " already exist, try again!\"),location.href=\"..\userAdd.html\";</script>";
            exit;
        } else {
            $sql = "INSERT INTO userInfo(store_name, store_rating, store_location_lat, store_location_lan, default_address_street, default_address_ref, default_address_city,
                default_address_state, default_address_zip, default_address_country) VALUES('$_store_name', '$_store_rating','$_store_location_lat', '$_store_location_lan',
                '$_default_address_street', '$_default_address_ref', '$_default_address_city', '$_default_address_state', '$_default_address_zip', '$_default_address_country')";
            $result = mysqli_query($sql);
            echo "<script>window.alert(\"User Added!\"),location.href=\"userLogin.html\";</script>";
        }
    }
}