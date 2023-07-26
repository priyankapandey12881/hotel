<?php
require_once('vendor/autoload.php');

use App\Database\DatabaseConnection;
use App\Services\HotelService;
include('delete.php');

//$a = new HotelService()
//$a->someMethod();

$hotels = HotelService::getAllHotels();
$db = new \App\Database\DatabaseConnection();
$table = 'hotels';
$columns = ['name', 'type'];
$conditions = [
    'id' => 1,
    'name' => 'test'
];
/*
if conditions are empty => ''
else => WHERE `id`=1 AND `name`='test' AND `type`='rent';
*/
$data= $db->select($table, $conditions, $columns);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('includes/header.php') ?>
</head>
<body>
    <div class="container">
        <h2>Hotel Listing</h2>

        <!-- Search Box -->
        <div class="search-box">
            <input type="text" id="search" class="form-control" placeholder="Search hotels">
        </div>

        <table class="table">
        <thead class="table-header">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Facilities</th>
                    <th>Status</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-body">
                <?php foreach ($hotels as $hotel) : ?>
                
                    <tr>
                        <td><?php echo $hotel['id']; ?></td>
                        <td><?php echo $hotel['name']; ?></td>
                        <td><?php echo $hotel['type']; ?></td>
                        <td><?php echo $hotel['description']; ?></td>
                        <td>
                            <?php $facilities = explode(',', $hotel['facility']); ?>
                            <?php foreach ($facilities as $facility) : ?>
                                <?php echo $facility; ?>
                            <?php endforeach; ?>
                        </td>
                        <td><?php echo ($hotel['status'] == 1) ? 'Active' : 'Not Active'; ?></td>
                        <td>
                            <?php if (!empty($hotel['images'])) : ?>
                                <ul>
                                    <?php foreach ($hotel['images'] as $image) : ?>
                                        <img src="<?php echo $image; ?>" alt="Hotel Image" width="50">
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="insert.php" class="btn btn-success">Create</a>
                            <a href="index.php?delete=<?php echo $hotel['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this hotel?')">Delete</a>
                            <a href="edit.php?edit=<?php echo $hotel['id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="view.php?id=<?php echo $hotel['id']; ?>" class="btn btn-info">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="./js/custom.js"></script>

</body>

</html>
