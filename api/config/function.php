<?php

include 'connection.php';
include 'query.php';

session_start();

function check_server()
{
    if ($_SERVER['SERVER_NAME'] != constant("SERVER_NAME")) sendData(false, "Bad request");
    return true;
}

function check_method($method)
{
    check_server();
    if ($_SERVER['REQUEST_METHOD'] != $method)  sendData(false, "Method not found");
    return true;
}

function page_goBack()
{
    header("location:javascript://history.go(-1)");
}

function valid_phone($phone)
{
    if (!preg_match("/^[0-9]*$/", $phone)) return "Only numeric value is allowed";
    else if (strlen($phone) != 10) return "Phone must have 10 digits";
    return true;
}

function valid_email($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Please enter correct email";
    return true;
}

function get_time($time)
{
    return date(TIME_FORMATE_SHOW, strtotime($time));
}


function xss_prevent($value)
{
    return strip_tags(htmlspecialchars($value));
}

function sql_prevent($conn, $value)
{
    return mysqli_real_escape_string($conn, $value);
}

function encryption($value, $iv)
{
    return openssl_encrypt($value, constant("CIPHER"), constant("KEY"), constant("OPTIONS"), $iv);
}

function decryption($value, $iv)
{
    return openssl_decrypt($value, constant("CIPHER"), constant("KEY"), constant("OPTIONS"), $iv);
}

function send_mail($Recipients, $Content){

    require './vender/PHPMailer/src/Exception.php';
    require './vender/PHPMailer/src/PHPMailer.php';
    require './vender/PHPMailer/src/SMTP.php';
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.'.HOST_NAME;                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'user@example.com';                     //SMTP username
        $mail->Password   = 'secret';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($Recipients['from'], $Recipients['from_name']);
        $mail->addAddress($Recipients['address'], $Recipients['address_name']);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $Content['subject'];
        $mail->Body    = $Content['body'];

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}

function sendData($success, $data)
{
    if (gettype($data) == 'string') echo json_encode(array("success" => $success, "message" => $data));
    else echo json_encode(array("success" => $success, "data" => $data));
    die;
}
