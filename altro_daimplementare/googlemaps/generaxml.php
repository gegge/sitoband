<?php

$dati = $_REQUEST['dati'];

function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

// Opens a connection to a MySQL server
$conn = new mysqli('toilwp.transferoil.lan', 'marco', 'Passw0rd', 'transferoilTest');

// Select all the rows in the markers table
if($dati == "none")
    $query = $conn->prepare("SELECT * FROM mappa_distributori WHERE 1");
else
{
    $arr = explode("_", $dati);
    $currentLat = $arr[0];
    $currentLng = $arr[1];
    $raggio = $arr[2]/1000;
    //echo "currentLat: $currentLat <br> currentLng: $currentLng <br> Raggio: $raggio <br>";
    $deltalat = $raggio / 111;
    $deltalng = $deltalat / abs(cos(deg2rad($currentLat)));
    //echo "deltalat: $deltalat <br> deltalon: $deltalng <br>";
    $maxlat = $currentLat + $deltalat;
    $minlat = $currentLat - $deltalat;
    $maxlng = $currentLng + $deltalng;
    //echo "maxlat: $maxlat <br> minlat: $minlat <br> maxlng: $maxlng <br>";
    if($maxlng > 180)
        $maxlng = $maxlng - 360;
    $minlng = $currentLng - $deltalng;
    //echo "minlng prima calcolo minlng = currentLng - deltalng: $minlng <br> ";
    if($minlng < -180)
        $minlng = $minlng + 360;
    //echo "minlng: $minlng <br>";
    $query = $conn->prepare("SELECT * FROM mappa_distributori WHERE ((lat BETWEEN ? AND ?) AND (lng BETWEEN ? AND ?));");
    //echo "SELECT * FROM mappa_distributori WHERE (lat BETWEEN $minlat AND $maxlat) AND (lng BETWEEN $minlng AND $maxlng);";
    
    $query->bind_param("ssss", $minlat, $maxlat, $minlng, $maxlng);
}
$query->execute();
$query->bind_result($id, $name, $address, $indirizzo, $citta, $zip, $stato, $nazione, $latitude, $longitude, $type, $website, $fax, $tel, $email);
header("Content-type: text/xml; charset=utf-8");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($query->fetch()){
  // ADD TO XML DOCUMENT NODE
  echo '<marker ';
  echo 'name="' . parseToXML($name) . '" ';
  echo 'address="' . parseToXML($indirizzo.", ".$zip.", ".$nazione."($stato)") . '" ';
  echo 'lat="' . $latitude . '" ';
  echo 'lng="' . $longitude . '" ';
  echo 'type="' . $type . '" ';
  echo 'website="' . $website . '" ';
  echo 'fax="' . $fax . '" ';
  echo 'tel="' . $tel . '" ';
  echo 'email="' . $email . '" ';
  echo '/>';
}

// End XML file
echo '</markers>';


?>