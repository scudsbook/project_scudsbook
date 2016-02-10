<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Scudsbook</title>

    <style>
        body{
            font-family:arial;
            font-size:.8em;
        }

        input[type=text]{
            padding:0.5em;
            width:20em;
        }

        input[type=submit]{
            padding:0.4em;
        }

        #gmap_canvas{
            width:100%;
            height:70em;
        }
    </style>

</head>
<body>

<?php
if($_POST){

    include_once ('../Database/Database_Connection.php');
    $databaseConnection = new Database_Connection();
    $databaseConnection->createDatabase();
    // get latitude, longitude and formatted address
    $data_arr = geocode($_POST['searchbox']);

    // if able to geocode the address
    if($data_arr){

        $latitude = $data_arr[0];
        $longitude = $data_arr[1];
        $formatted_address = $data_arr[2];

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
                map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
                marker = new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
                });
                infowindow = new google.maps.InfoWindow({
                    content: "<?php echo $formatted_address; ?>"
                });
                google.maps.event.addListener(marker, "click", function () {
                    infowindow.open(map, marker);
                });
                infowindow.open(map, marker);
            }
            google.maps.event.addDomListener(window, 'load', init_map);
        </script>

        <?php

        // if unable to geocode the address
    }else{
        echo "No map found.";
    }
}
?>



</body>
</html>