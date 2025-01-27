<?php
function dbConnect(){
$user = 'root';
$pass = '';
$host = 'localhost';
$dbname = 'palladium';
$charset = 'utf8';
$db = "mysql:host=$host;dbname=$dbname;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try{
return new PDO($db, $user, $pass, $opt);
}catch(\PDOException $e){
throw new \Exception($e->getMessage (), (int)$e->getCode());
}
}
?>