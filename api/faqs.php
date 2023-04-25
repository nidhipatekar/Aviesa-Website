<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include './config/function.php';

check_method("POST");

if (!isset($_POST['submit'])) sendData(false, "Methoda not found");
$submit = sql_prevent($conn, xss_prevent($_POST['submit']));
switch ($submit) {
    case 'enquiry_form':
        $error = [];
        if (!isset($_POST['name']) || empty($_POST['name'])) $error['name'] = "Name should not be blank!";
        else if(strlen($_POST['name']) > 50) $error['name'] = "Name should be less then 50 character! ";

        if (!isset($_POST['email']) || empty($_POST['email'])) $error['email'] = "Email should not be blank!";
        else if(valid_email($_POST['email']) != 1) $error['email'] = valid_email($_POST['email']);

        if (!isset($_POST['phone']) || empty($_POST['phone'])) $error['phone'] = "Phone no should not be blank!";
        else if(valid_phone($_POST['phone']) != 1) $error['phone'] = valid_phone($_POST['phone']);

        if (!isset($_POST['address']) || empty($_POST['address'])) $error['address'] = "Address should not be blank!";
        else if(strlen($_POST['address']) > 100) $error['address'] = "Address should be less then 100 character! ";
        if (!isset($_POST['message']) || empty($_POST['message'])) $error['message'] = "Message should not be blank!";

        if(count($error) > 0) sendData(false, $error);

        $name = sql_prevent($conn, xss_prevent($_POST['name']));
        $email = sql_prevent($conn, xss_prevent($_POST['email']));
        $phone = sql_prevent($conn, xss_prevent($_POST['phone']));
        $address = sql_prevent($conn, xss_prevent($_POST['address']));
        $message = sql_prevent($conn, xss_prevent($_POST['message']));

        $query = "INSERT INTO enquiry (name, phone, email, address, message, created_at) VALUES ('$name', '$phone', '$email', '$address', '$message', current_timestamp())";

        if(query_create($conn, $query)){
            $Recipients = ["from"=> $email, "from_name" => $name, "address"=> "admin@".HOST_NAME, "address_name" => "admin"];
            $Content = ["subject" => $name.' send a enquiry request'];
            $Content['body']    = `
                <p><b>name : </b> $name</p>
                <p><b>phone : </b> <a href="tel:$phone">$phone</a></p>
                <p><b>email : </b> <a href="mailto:$email"></a>$email</p>
                <p><b>address : </b> $address</p>
                <h4><b>Enquiry</b></h4>
                <p>$message</p>
            `;

            // if(send_mail($Recipients, $Content) == 1){
            //     sendData(false, "Not able to send your request");
            // }
            sendData(true, "Enquiry request send successfully");
        }
        else sendData(false, "Not able to send your request");
        break;

    default:
        sendData(false, "Method not found");
        break;
}