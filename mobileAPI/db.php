<?php 

$dns = 'mysql:host=localhost;dbname=arabmedicine';
$user = 'anaseem';
$pass = 'Ahmednaseem123@';

try{
    $db = new PDO($dns, $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
    $error = $e->getMessage();
    echo $error;
}