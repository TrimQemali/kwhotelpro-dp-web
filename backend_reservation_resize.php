<?php
require_once '_db.php';

$json = file_get_contents('php://input');
$params = json_decode($json);

class Result {}

$stmt = $db->prepare("UPDATE rezerwacje SET DataOd = :start, DataDo = :end WHERE RezerwacjaID = :id");
$stmt->bindParam(':start', $params->newStart);
$stmt->bindParam(':end', $params->newEnd);
$stmt->bindParam(':id', $params->id);
$stmt->execute();


$response = new Result();
$response->result = 'OK';
$response->message = 'Update successful';

header('Content-Type: application/json');
echo json_encode($response);
