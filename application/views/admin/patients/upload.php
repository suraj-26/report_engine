<?php 
$code=$_POST['code'];

$xml=simplexml_load_string(base64_decode($code));
    $array_xml=array();
    $uid= $xml['uid'];
$name= $xml['name'];
$gender= $xml['gender'];
$year_of_birth= $xml['yob'];
$location= $xml['loc'];
$vtc= $xml['vtc'];
$post= $xml['po'];
$dist= $xml['dist'];
$subdist= $xml['subdist'];
$pc= $xml['pc'];
$dob= $xml['dob'];
$array_xml=array(
'uid'=>$xml['uid'],
'name'=>$xml['name'],
'gender'=>$xml['gender'],
'year_of_birth'=>$xml['yob'],
'location'=>$xml['loc'],
'vtc'=>$xml['vtc'],
'post'=>$xml['po'],
'dist'=>$xml['dist'],
'subdist'=>$xml['subdist'],
'pc'=>$xml['pc'],
'dob'=>$xml['dob'],
	);

   $response['xml_arr']=$array_xml;
   echo json_encode($response);