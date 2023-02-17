<?php
require_once '_db.php';

$start = $_GET['start'];
$end = $_GET['end'];

$stmt = $db->prepare("SELECT RezerwacjaID, PokojID, DataOd, DataDo, Nazwisko, rezerwacje.Uwagi, Cena, Wplata FROM banjisht.rezerwacje, banjisht.klienci where rezerwacje.KlientID=klienci.KlientID AND NOT ((DataDo <= :start) OR (DataOd >= :end))");
$stmt->bindParam(':start', $start);
$stmt->bindParam(':end', $end);
$stmt->execute();
$result = $stmt->fetchAll();

class Event {}
$events = array();

date_default_timezone_set("UTC");
$now = new DateTime("now");
$today = $now->setTime(0, 0, 0);

foreach($result as $row) {
    $e = new Event();
    $e->id = $row['RezerwacjaID'];
    $e->text = $row['Nazwisko'];
    $e->start = $row['DataOd'];
    $e->end = $row['DataDo'];
    $e->resource = $row['PokojID'];
    $e->cost = $row['Cena'];
    $e->paid = $row['Wplata'];
    $e->bubbleHtml = "Reservation ID:".$e->id."<br /> Total cost: ".$e->cost."<br /> Paid: ".$e->paid;
    
    //$e->status = $row['status'];
    //$e->paid = intval($row['paid']);
    $events[] = $e;
}

header('Content-Type: application/json');
echo json_encode($events);
