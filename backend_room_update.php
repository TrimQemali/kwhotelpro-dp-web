<?php
require_once '_db.php';

$json = file_get_contents('php://input');
$params = json_decode($json);

$stmt = $db->prepare("UPDATE pokoje SET Symbol = :name,  Opis = :status WHERE PokojID = :id");
$stmt->bindParam(':id', $params->id);
$stmt->bindParam(':name', $params->name);
$stmt->bindParam(':status', $params->status);
$stmt->execute();

class Result {}

$response = new Result();
$response->result = 'OK';
$response->message = 'Update successful';

header('Content-Type: application/json');
echo json_encode($response);
