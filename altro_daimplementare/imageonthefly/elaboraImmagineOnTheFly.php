<?php
//include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
//header('Content-Type: text/html; charset=utf-8');
//TODO: guardare se funziona con i link
include("./funzioni/imageresizerManager/imageresizer_class.php");
/*

if(isset($_REQUEST['pathImg']) && $_REQUEST['pathImg'] != "")
    $pathImg = $_REQUEST['pathImg'];
else
    exit();
    
if(isset($_REQUEST['width']) && $_REQUEST['width'] != "")
    $width = $_REQUEST['width'];
else
    $width = 0;
    
if(isset($_REQUEST['height']) && $_REQUEST['height'] != "")
    $height = $_REQUEST['height'];
else
    $height = 0;
*/
if(isset($_REQUEST['stringa']) && $_REQUEST['stringa'] != "")
    $stringa = $_REQUEST['stringa'];
else
    exit();
    
//echo "Stringa Ricevuta: $stringa <br>";
$stringa = explode(",", $stringa);
$pathImg = "X:/repositories/sito_band/".$stringa[0];
$pathImg = str_replace("|", "+", $pathImg);
$pathImg = str_replace("AND", "&", $pathImg);
$width = $stringa[1];
//echo "Width: $width <br>";
$height = $stringa[2];
//echo "Height: $height <br>";

$img = explode(".", $pathImg);
$image_extension = $img[(count($img)-1)];

$imgresMGR = new image_resizer($pathImg);
$imgresMGR->resizeImage($width, $height, "exact");
header("Content-Type: image/$image_extension");
$imgresMGR->saveImage($image_extension);

?>