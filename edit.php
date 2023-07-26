<?php
error_reporting(1);
require_once('vendor/autoload.php');
use App\Database\DatabaseConnection;
use App\Services\HotelService;

if (isset($_POST['submit'])) {
    $hotelId = $_POST['hotel_id'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $facility = implode(",", $_POST['facility']);
    $status = isset($_POST['status']) ? 1 : 0;
    $hotelService = new App\Services\HotelService();
    $hotelService::updateHotel($hotelId, $name, $type, $description,  $facility, $status, $images);

    $fileCount = count($_FILES['image']['name']);
    for ($i = 0; $i < $fileCount; $i++) {
        $fileName = $_FILES['image']['name'][$i];
        $tmpFilePath = $_FILES['image']['tmp_name'][$i];

        if ($fileName != "") {
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $currentDateTime = date('Y-m-d_H-i-s');
            $newFileName = $currentDateTime . '_' . $i . '.' . $extension;
            $uploadFolder = 'images/' . $newFileName;
            move_uploaded_file($tmpFilePath, $uploadFolder);

            $hotelService->insertImage($hotelId, $uploadFolder);
        }
    }

    header("Location: index.php");
    exit();
}

$hotelId = $_GET['edit'];
$hotelService = new HotelService();
$hotel = $hotelService->getHotel($hotelId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include('includes/header.php') ?>
</head>
<body>
<div class="container">
    <h2>Edit Hotel</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="hotel_id" value="<?php echo $hotel['id']; ?>">
        <table class="table">
            <tr>
                <td><label for="name">Name:</label></td>
                <td><input type="text" name="name" id="name" class="form-control" value="<?php echo $hotel['name']; ?>" required></td>
            </tr>
            <tr>
                <td><label>Hotel:</label></td>
                <td>
                    <select name="type" required>
                        <option value="">Type</option>
                        <option value="Sell"<?php if ($hotel['type'] === 'sell') echo ' selected'; ?>>Sell</option>
                        <option value="Rent"<?php if ($hotel['type'] === 'rent') echo ' selected'; ?>>Rent</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Facility:</label></td>
                <td>
                    <?php
                    $facilities = explode(",", $hotel['facility']);
                    ?>
                    <input type="checkbox" id="" name="facility[]" value="Playground"<?php if (in_array('Playground', $facilities)) echo ' checked'; ?>>
                    <label for="facility1">Playground</label><br>
                    <input type="checkbox" id="" name="facility[]" value="Parking"<?php if (in_array('Parking', $facilities)) echo ' checked'; ?>>
                    <label for="facility2">Parking</label><br>
                    <input type="checkbox" id="" name="facility[]" value="Swimmingpool"<?php if (in_array('Swimmingpool', $facilities)) echo ' checked'; ?>>
                    <label for="facility3">Swimmingpool</label><br><br>
                </td>
            </tr>
            <tr>
                <td><label>Description:</label></td>
                <td><textarea cols="40" rows="5" name="description" class="form-control"><?php echo $hotel['description']; ?></textarea></td>
            </tr>
            <tr>
                <td><label>Status:</label></td>
                <td>
                    <div class="form-check">
                        <input type="radio" name="status" id="status" class="form-check-input"<?php if ($hotel['status'] == 1) echo ' checked'; ?>>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="status" id="status" class="form-check-input"<?php if ($hotel['status'] == 0) echo ' checked'; ?>>
                        <label class="form-check-label" for="status">Not Active</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td><label>Images:</label></td>
                <td><input type="file" class="form-control" name="image[]" multiple></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="image-preview">
                        <?php
                        // Display inserted images
                        $db = new DatabaseConnection();
                        $imageQuery = "SELECT * FROM images WHERE hotel_id = '$hotelId'";
                        $imageResult = mysqli_query($db->con, $imageQuery);
                        while ($image = mysqli_fetch_assoc($imageResult)) {
                            echo '<img src="' . $image['path'] . '" alt="Image">';
                        }
                        ?>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
