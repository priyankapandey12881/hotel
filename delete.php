<?php
require_once('vendor/autoload.php');
use App\Database\DatabaseConnection;
use App\Services\HotelService;
$hotels = HotelService::getAllHotels();

if (isset($_GET['delete']))
{
    $hotelId = $_GET['delete'];
    $hotels = HotelService::deleteHotel($hotelId);
    header("Location: index.php");
    exit();
}
?>
