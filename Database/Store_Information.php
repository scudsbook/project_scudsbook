<?php

/**
 * Created by PhpStorm.
 * User: ye1.chen
 * Date: 2/9/16
 * Time: 2:33 PM
 */
class Store_Information
{
    public $_store_name;
    public $_store_rating;
    public $_store_category;
    public $_default_address_street;
    public $_default_address_street_ref;
    public $_default_address_city;
    public $_default_address_state;
    public $_default_address_zip;
    public $_default_address_country;
    public $_store_location_lat;
    public $_store_location_lan;

    public function queryAllStoreInfo()
    {   include_once('../Database/Database_Connection.php');
        $databaseConnection = new Database_Connection();
        $databaseConnection->createDatabase();

        $databaseConnection->databaseConnect();
        $sql = "select * from scudsbook_store_info";
        $result = mysqli_query($databaseConnection->conn, $sql);
        $i=1;
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $this->_store_name[$i] = $row['store_name'];
                $this->_store_rating[$i] = $row['store_rating'];
                $this->_store_category[$i] = $row['store_category'];
                $this->_store_location_lat[$i] = $row['store_location_lat'];
                $this->_store_location_lan[$i] = $row['store_location_lan'];
                $this->_default_address_street[$i] = $row['address_street'];
                $this->_default_address_street_ref[$i] = $row['address_ref'];
                $this->_default_address_city[$i] = $row['address_city'];
                $this->_default_address_state[$i] = $row['address_state'];
                $this->_default_address_zip[$i] = $row['address_zip'];
                $this->_default_address_country[$i] = $row['address_country'];
                $i++;
            }
        } else {
            echo "0 results";
        }
        mysqli_close($databaseConnection->conn);
        $databaseConnection->conn_state=false;
    }
}