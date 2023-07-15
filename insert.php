<?php

error_reporting(1);
include('connection.php');

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $facility = implode(",", $_POST['facility']);
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Validation for required fields
    if (empty($name) || empty($type) || empty($facility) || empty($description) || empty($status)) {
        echo "Please fill in all the required fields.";
    }

    // Image upload validation
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $maxFileSize = 1 * 1024 * 1024;

    for ($i = 0; $i < count($_FILES['image']['tmp_name']); $i++) {
        $fileName = $_FILES['image']['name'][$i];
        $fileSize = $_FILES['image']['size'][$i];
        $fileTmp = $_FILES['image']['tmp_name'][$i];
        $fileType = $_FILES['image']['type'][$i];

        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate file extension
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
            exit;
        }

        // Validate file size
        if ($fileSize > $maxFileSize) {
            echo "File size exceeds the maximum limit of 1MB.";
            exit;
        }

        $currentDateTime = date('Y-m-d_H-i-s');
        $newFileName = $currentDateTime . '_' . $i . '.' . $fileExtension;
        $uploadFolder = 'images/' . $newFileName;
        move_uploaded_file($fileTmp, $uploadFolder);

        $query = "INSERT INTO images (hotel_id, path) VALUES ('$hotelId', '$uploadFolder')";
        mysqli_query($con, $query);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <title>Hotel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        body {
            color: #fff;
            background: #63738a;
            font-family: 'Roboto', sans-serif;
        }
        .form-control {
            height: 40px;
            box-shadow: none;
            color: #969fa4;
        }
        .form-control:focus {
            border-color: #5cb85c;
        }
        .form-control, .btn {        
            border-radius: 3px;
        }
        .signup-form {
            width: 450px;
            margin: 0 auto;
            padding: 30px 0;
            font-size: 15px;
        }
        .signup-form h2 {
            color: #636363;
            margin: 0 0 15px;
            position: relative;
            text-align: center;
        }
        .signup-form h2:before, .signup-form h2:after {
            content: "";
            height: 2px;
            width: 30%;
            background: #d4d4d4;
            position: absolute;
            top: 50%;
            z-index: 2;
        }
        .signup-form h2:before {
            left: 0;
        }
        .signup-form h2:after {
            right: 0;
        }
        .signup-form .hint-text {
            color: #999;
            margin-bottom: 30px;
            text-align: center;
        }
        .signup-form form {
            color: #999;
            border-radius: 3px;
            margin-bottom: 15px;
            background: #f2f3f7;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }
        .signup-form .form-group {
            margin-bottom: 20px;
        }
        .signup-form input[type="checkbox"] {
            margin-top: 3px;
        }
        .signup-form .btn {        
            font-size: 16px;
            font-weight: bold;      
            min-width: 140px;
            outline: none !important;
        }
        .signup-form .row div:first-child {
            padding-right: 10px;
        }
        .signup-form .row div:last-child {
            padding-left: 10px;
        }
        .signup-form a {
            color: #fff;
            text-decoration: underline;
        }
        .signup-form a:hover {
            text-decoration: none;
        }
        .signup-form form a {
            color: #5cb85c;
            text-decoration: none;
        }
        .signup-form form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="signup-form">
    <form method="POST" enctype="multipart/form-data">
        <h2>Hotel Data</h2>

        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Hotel:</label>
            <select name="type" class="form-control" required>
                <option value="">Type</option>
                <option value="sell">Sell</option>
                <option value="rent">Rent</option>
            </select>
        </div>

        <div class="form-group">
            <label>Facility:</label>
            <br>
            <input type="checkbox" id="facility1" name="facility[]" value="Playground">
            <label for="facility1">Playground</label><br>
            <input type="checkbox" id="facility2" name="facility[]" value="Parking">
            <label for="facility2">Parking</label><br>
            <input type="checkbox" id="facility3" name="facility[]" value="Swimmingpool">
            <label for="facility3">Swimmingpool</label>
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea cols="40" rows="5" name="description" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label>Status:</label><br>
            <input type="radio" name="status" value="1" checked> Yes<br>
            <input type="radio" name="status" value="0"> No
        </div>

        <div class="form-group">
            <label>Image:</label>
            <input type="file" class="form-control" required name="image[]" multiple>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success btn-lg btn-block" name="submit">Submit</button>
        </div>
    </form>
    <div class="text-center">
        View Already Inserted Data!!  <a href="list.php">View</a>
    </div>
</div>
</body>
</html>
