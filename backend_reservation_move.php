<?php
require_once '_db.php';

$json = file_get_contents('php://input');
$params = json_decode($json);

class Result {}

$stmt = $db->prepare("SELECT RezerwacjaID, PokojID, DataOd, DataDo, Nazwisko, rezerwacje.Uwagi FROM banjisht.rezerwacje, banjisht.klienci where rezerwacje.KlientID=klienci.KlientID AND NOT ((DataDo <= :start) OR (DataOD >= :end)) AND rezerwacje.RezerwacjaID <> :id AND rezerwacje.PokojID = :resource");
$stmt->bindParam(':start', $params->newStart);
$stmt->bindParam(':end', $params->newEnd);
$stmt->bindParam(':id', $params->id);
$stmt->bindParam(':resource', $params->newResource);
$stmt->execute();
$overlaps = $stmt->rowCount() > 0;

if ($overlaps) {
    $response = new Result();
    $response->result = 'Error';
    $response->message = 'This reservation overlaps with an existing reservation.';

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
$stmt = $db->prepare("UPDATE rezerwacje SET DataOd = :start, DataDo = :end, PokojID = :resource WHERE RezerwacjaID = :id");
$stmt->bindParam(':start', $params->newStart);
$stmt->bindParam(':end', $params->newEnd);
$stmt->bindParam(':id', $params->id);
$stmt->bindParam(':resource', $params->newResource);
$stmt->execute();


$response = new Result();
$response->result = 'OK';
$response->message = 'Update successful';

header('Content-Type: application/json');
echo json_encode($response);
