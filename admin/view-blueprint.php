<?php

$file_path = '../documents/' . $_GET['file'];
$file_name = $_GET['file'];

// view pdf on new tab
header('Content-type: application/pdf');
header('Content-Disposition: inline; file="' . $file_name . '"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
@readfile($file_path);

//download pdf
// header("Content-Disposition: attachment; filename=" . urlencode($file_name));
// $fb = fopen($file_path, "r");
// while (!feof($fb)){
//     echo fread($fb, 8192);
//     flush();
// }
// fclose($fb);

?>