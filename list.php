<?php

error_reporting(1);
include 'connection.php';
include 'delete.php';

$query = "SELECT * FROM hotels";
$result = mysqli_query($con, $query);
$hotels = [];

while ($row = mysqli_fetch_assoc($result)) {
    $hotelId = $row['id'];
    $query = "SELECT path FROM images WHERE hotel_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $hotelId);
    mysqli_stmt_execute($stmt);
    $imageResult = mysqli_stmt_get_result($stmt);
    $images = [];

    while ($imageRow = mysqli_fetch_assoc($imageResult)) {
        $images[] = $imageRow['path'];
    }

    $row['images'] = $images;
    $hotels[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <title>Hotel Listing</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            color: #fff;
            background: #63738a;
            font-family: 'Roboto', sans-serif;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 30px;
        }

        .table {
            background-color: #f2f3f7;
            border: none;
            border-radius: 3px;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 20px;
            margin-bottom: 30px;
        }

        .table-header {
            background-color: #5cb85c;
            color: #fff;
            padding: 10px;
            margin-bottom: 10px;
        }

        .table-body {
            margin-bottom: 10px;
        }

        .table-title {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .table-description {
            margin-bottom: 10px;
        }

        .table-facilities {
            margin-bottom: 10px;
        }

        .table-facilities ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .table-facilities li {
            display: inline-block;
            margin-right: 10px;
        }

        .btn {
            margin-right: 10px;
        }

        .search-box {
            margin-bottom: 20px;
        }
    </style>
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
                                <li>
                                    <img src="<?php echo $image; ?>" alt="Hotel Image" width="50">
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="insert.php?insert=<?php echo $hotel['id']; ?>" class="btn btn-success">Create</a>
                    <a href="edit.php?edit=<?php echo $hotel['id']; ?>" class="btn btn-primary">Edit</a>
                    <a href="list.php?delete=<?php echo $hotel['id']; ?>" class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to delete this hotel?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function () {
        $('#search').keyup(function () {
            var searchValue = $(this).val().toLowerCase();
            $('.table-body tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1)
            });
        });
    });
</script>

</body>
</html>
