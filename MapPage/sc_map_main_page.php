<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Scudsbook</title>

    <style>
        body {
            font-family: arial;
            font-size: .8em;
        }

        input[type=text] {
            padding: 0.5em;
            width: 20em;
        }

        input[type=submit] {
            padding: 0.4em;
        }

        #gmap_canvas {
            width: 100%;
            height: 70em;
        }
    </style>

</head>
<body>

<?php
if ($_POST) {
    // get latitude, longitude and formatted address
    $sc_zip = $_POST['searchbox'];

    include_once('../Database/Store_Information.php');
    $store_info = new Store_Information();
    $store_info->queryAllStoreInfo();
    $index = count($store_info->_store_name);
    $counter_php = 1;

    $latitude = $store_info->_store_location_lat[1];
    $longitude = $store_info->_store_location_lan[1];
    $formatted_address = $store_info->_default_address_street[1] . " " . $store_info->_default_address_city[1]
        . " " . $store_info->_default_address_state[1] . " " . $store_info->_default_address_zip[1];
    ?>

    <!-- google map will be shown here -->
    <div id="gmap_canvas">Loading map...</div>

    <!-- JavaScript to show google map -->
    <script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>
    <script type="text/javascript">
        function init_map() {
            var myOptions = {
                zoom: 14,
                center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var latitude = <?php echo json_encode($store_info->_store_location_lat); ?>;
            var longitude = <?php echo json_encode($store_info->_store_location_lan); ?>;
            var address_street = <?php echo json_encode($store_info->_default_address_street); ?>;
            max_address=<?php echo $index; ?>;

            map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
            for (i = 1; i <=max_address; i++) {
                marker = new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(latitude[i], longitude[i])
                });
                infowindow = new google.maps.InfoWindow({
                    content: address_street[i]
                });
                google.maps.event.addListener(marker, "click", function () {
                    infowindow.open(map, marker);
                });
                infowindow.open(map, marker);
                <?php $counter_php++; ?>
            }
        }
        google.maps.event.addDomListener(window, 'load', init_map);
    </script>

    <?php

}
?>


</body>
</html>