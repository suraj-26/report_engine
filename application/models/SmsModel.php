<?php


require_once './vendor/autoload.php';
use GuzzleHttp\Client;

class SmsModel extends CI_Model
{

    public function sendSMS($number,$templateData,$template,$templateID=1){
        $username="bharatmishra1";
        $password ="bharat@100";
        $sender="GLDBRZ";
        $message="";
        $postData = array(
            'user' => "bharatmishra1",
            'password'=>"bharat@100",
            'mobile' =>$number,
            'sender' => $sender,
            'type' => '3'
        );
        switch ($templateID){
            case 1:
                $postData["template_id"]=$template;
                $postData['message'] = "".$templateData['name']." has been admitted in the ".$templateData['center']." and has been allotted ".$templateData['bed']." in ".$templateData['room']." -Gold Berries";
                break;
            case 2:
                $postData["template_id"]=$template;
                $postData['message'] = "".$templateData['name']." has been transferred to ".$templateData['center']." in ".$templateData['bed']." in ".$templateData['room']." -Gold Berries";
                break;
            case 3:
                $postData["template_id"]=$template;
                $postData['message'] = "Treatment has been started for ".$templateData['otp']." -Gold Berries";
                $postData['message'] = "OTP for Login Transaction on ".$templateData['company']." is ".$templateData['otp']." and valid till ".$templateData['time'].".Do not share this OTP to anyone for security reasons -Gold Berries";
                break;
        }

        $client = new Client();
        $response = $client->request('GET', 'http://api.bulksmsgateway.in/sendmessage.php', array(
            'query' =>$postData
        ));
        return $response->getBody();

    }
}
