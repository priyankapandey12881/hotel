<?php
require_once('vendor/autoload.php');

use App\Database\DatabaseConnection;
use App\Services\HotelService;

if (isset($_GET['id'])) {
    $hotelId = $_GET['id'];
    $hotels = HotelService::getHotel($hotelId);
} else {
    echo "Invalid hotel id.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include('includes/header.php') ?>
</head>
<body>
    <div class="container">
        <h2>Hotel Details</h2>
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <td><?php echo $hotels['name']; ?></td>
            </tr>
            <tr>
                <th>Type</th>
                <td><?php echo $hotels['type']; ?></td>
            </tr>
            <tr>
                <th>Facility</th>
                <td><?php echo $hotels['facility']; ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?php echo $hotels['description']; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo $hotels['status'] == 1 ? 'Active' : 'Not Active'; ?></td>
            </tr>
            <tr>
                <th>Images</th>
                <td>
                    <?php
                    $db = new DatabaseConnection();
                    $imageQuery = "SELECT * FROM images WHERE hotel_id = '$hotelId'";
                    $imageResult = mysqli_query($db->con, $imageQuery);
                    while ($image = mysqli_fetch_assoc($imageResult)) {
                        echo '<img src="' . $image['path'] . '" alt="Image" style="max-width: 200px; max-height: 200px; margin-right: 10px;">';
                    }
                    ?>
                </td>
            </tr>
        </table>
        <a href="index.php" class="btn btn-primary">Back to List</a>
    </div>
</body>
</html>
