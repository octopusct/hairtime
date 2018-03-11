<?php
header ('Content-Type: text/html; charset=utf-8');

echo 'Hello<BR>';
//get the form values
$username = $_POST['login_username'];
$userpass = $_POST['login_password'];

$params = array(
    'email' => $username,
    'password' => $userpass);
echo "user: ${username}<BR>pass: ${userpass}<BR>";
echo $params.'<BR>';
try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://hairtime.co.il/auth/singin');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, count($params));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

    //execute request
    $result = curl_exec($ch);

    // json decode the result
    $result = json_decode($result);
    echo $result->first_name."<BR>";
    echo $result->token."<BR>";
    //         if ($result == false || isset($result['success']) == false) {
//                throw new \Exception('Request was not correct.');
//            }
//
//            if ($result['success'] == false) {
//                throw  new \Exception($result['errormsg']);
//            }

}catch (Exception $ex){
    $result ['success'] = false;
    $result ['error'] = $ex->getMessage();
    return $result;
}

return $result;


echo '';
var_dump($user_data);
return $user_data;



?>