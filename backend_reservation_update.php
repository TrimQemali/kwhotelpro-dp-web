<?php
require_once '_db.php';

$json = file_get_contents('php://input');
$params = json_decode($json);

$stmt = $db->prepare("UPDATE banjisht.rezerwacje, banjisht.klienci  SET klienci.Nazwisko = :name, rezerwacje.DataOd = :start, rezerwacje.DataDo = :end, rezerwacje.PokojID = :room WHERE rezerwacje.KlientID=klienci.KlientID AND rezerwacje.RezerwacjaID = :id");

$stmt->bindParam(':id', $params->id);
$stmt->bindParam(':start', $params->start);
$stmt->bindParam(':end', $params->end);
$stmt->bindParam(':name', $params->text);
$stmt->bindParam(':room', $params->resource);
//$stmt->bindParam(':status', $params->status);
//$stmt->bindParam(':paid', $params->paid);
$stmt->execute();

class Result {}

$response = new Result();
$response->result = 'OK';
$response->message = 'Update successful';

header('Content-Type: application/json');
echo json_encode($response);
