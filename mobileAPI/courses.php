<?php 
require_once('db.php');
$query = 'SELECT * FROM courses;';
$stm = $db->prepare($query);
$stm->execute();
$row = $stm->fetchAll();
echo json_encode($row);
