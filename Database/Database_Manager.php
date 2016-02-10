<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Store Info</title>
</head>

<body>

<div id="table1">

    <form action="" method=post id=addstore>
        <table cellspacing=1 cellpadding=0 width=40% align=center border=0>
            <tbody>
            <tr bgcolor=#cccccc>
                <td><strong>Store Registration</strong></td>
                <td><strong>Input The Store Information</strong></td>
            </tr>
            <tr>
                <td align=center>Store Name</td>
                <td align=center><input style="width:100%;" type=text name=_store_name id=_store_name></td>
            </tr>
            <tr bgcolor=#cccccc>
                <td align=center>Store Rating</td>
                <td align="center"><input style="width:100%;" type=text name=_store_rating id=_store_rating></td>
            </tr>
            <tr>
                <td align=center>Store Category</td>
                <td align="center"><input style="width:100%;" type=text name=_store_category id=_store_category></td>
            </tr>
            <tr bgcolor=#cccccc>
                <td align=center>Store Address Street</td>
                <td align="center"><input style="width:100%;" type=text name=_default_address_street
                                          id=_default_address_street></td>
            </tr>
            <tr>
                <td align=center>Store Address City</td>
                <td align="center"><input style="width:100%;" type=text name=_default_address_city
                                          id=_default_address_city></td>
            </tr>
            <tr bgcolor=#cccccc>
                <td align=center>Store Address State</td>
                <td align="center"><input style="width:100%;" type=text name=_default_address_state
                                          id=_default_address_state></td>
            </tr>
            <tr>
                <td align=center>Store Address Zip</td>
                <td align="center"><input style="width:100%;" type=text name=_default_address_zip
                                          id=_default_address_zip></td>
            </tr>
            <tr bgcolor=#cccccc>
                <td align=center>Store Address Country</td>
                <td align="center"><input style="width:100%;" type=text name=_default_address_country
                                          id=_default_address_country></td>
            </tr>
            <tr>
                <td>
                    <input type=reset name=reset value=clear>
                    <input type=submit name=submit value=submit>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <br>
    <br>
    <?php
        showStoreInfo();
    ?>

</div>

<?php
if ($_POST) {

    $_store_name = $_POST['_store_name'];
    $_store_rating = $_POST['_store_rating'];
    $_store_category = $_POST['_store_category'];
    $_default_address_street = $_POST['_default_address_street'];
    $_default_address_city = $_POST['_default_address_city'];
    $_default_address_state = $_POST['_default_address_state'];
    $_default_address_zip = $_POST['_default_address_zip'];
    $_default_address_country = $_POST['_default_address_country'];

    include_once('../Database/Database_Connection.php');
    $databaseConnection = new Database_Connection();
    $databaseConnection->createDatabase();
    // get latitude, longitude and formatted address
    $data_arr = geocode($_default_address_street . ',' . $_default_address_city . ',' . $_default_address_state . ',' . $_default_address_zip . ',' . $_default_address_country);

    // if able to geocode the address
    if ($data_arr) {

        $latitude = $data_arr[0];
        $longitude = $data_arr[1];
        $formatted_address = $data_arr[2];

        echo "$_default_address_country";
        $databaseConnection->addStoreInfo($_store_name, $_store_rating, $_store_category, $latitude, $longitude, $_default_address_street, '', $_default_address_city, $_default_address_state,
            $_default_address_zip, $_default_address_country);
        echo"window.location.reload()"
    } else {
        echo "The address does not exist, please try again!";
    }
}

?>

<?php

// function to geocode address, it will return false if unable to geocode address
function geocode($address)
{

    // url encode the address
    $address = urlencode($address);

    // google map geocode api url
    $url = "http://maps.googleapis.com/maps/api/geocode/json?address={$address}";

    // get the json response
    $resp_json = file_get_contents($url);

    // decode the json
    $resp = json_decode($resp_json, true);

    // response status will be 'OK', if able to geocode given address
    if ($resp['status'] == 'OK') {

        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];

        // verify if data is complete
        if ($lati && $longi && $formatted_address) {

            // put the data in the array
            $data_arr = array();

            array_push(
                $data_arr,
                $lati,
                $longi,
                $formatted_address
            );

            return $data_arr;

        } else {
            return false;
        }

    } else {
        return false;
    }
}

function showStoreInfo()
{
    include_once('../Database/Store_Information.php');
    $store_info = new Store_Information();
    $store_info->queryAllStoreInfo();
    echo "<table id='tb_store_info_dba' border='1' align=center><tr><th>Store Name</th><th>Store Rating</th><th>Store Category</th><th>Store lat</th><th>Store lan</th>
          <th>Store Address Street</th><th>Store Address Street Ref</th><th>Store Address City</th><th>Store Address State</th><th>Store Address Zip</th>
          <th>Store Address Country</th></tr>";
    // output data of each row
    for($x=1;$x<=count($store_info->_store_name);$x++) {
        echo "<tr><td>".$store_info->_store_name[$x]."</td><td>".
            $store_info->_store_rating[$x]."</td><td>".
            $store_info->_store_category[$x]."</td><td>".
            $store_info->_store_location_lat[$x]."</td><td>".
            $store_info->_store_location_lan[$x]."</td><td>".
            $store_info->_default_address_street[$x]."</td><td>".
            $store_info->_default_address_street_ref[$x]."</td><td>".
            $store_info->_default_address_city[$x]."</td><td>".
            $store_info->_default_address_state[$x]."</td><td>".
            $store_info->_default_address_zip[$x]."</td><td>".
            $store_info->_default_address_country[$x]."</td></tr>";
    }
    echo "</table>";
}
?>
</body>
</html>
