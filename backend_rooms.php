<?php
require_once '_db.php';

$json = file_get_contents('php://input');
$params = json_decode($json);

$Lozka = isset($params->Lozka) ? $params->Lozka : '0';

$stmt = $db->prepare("SELECT PokojID, Symbol, Opis, Lozka, Active FROM pokoje WHERE Lozka = :Lozka OR :Lozka = '0' AND Active = '1' ORDER BY Symbol;");
$stmt->bindParam(':Lozka', $Lozka); 
$stmt->execute();
$rooms = $stmt->fetchAll();

class Room {}

$result = array();

foreach($rooms as $room) {
  $r = new Room();
  $r->id = $room['PokojID'];
  $r->name = $room['Symbol'];
  $r->capacity = intval($room['Lozka']);
  $r->status = $room['Opis'];
  $result[] = $r;
}

header('Content-Type: application/json');
echo json_encode($result);
