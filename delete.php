<?php
if (isset($_GET['delete'])) {
    $hotelId = $_GET['delete'];
    
    
    $deleteQuery = "DELETE FROM hotels WHERE id = '$hotelId'";
    mysqli_query($con, $deleteQuery);
    
    // $deleteImagesQuery = "DELETE FROM images WHERE hotel_id = '$hotelId'";
    // mysqli_query($con, $deleteImagesQuery);
    
    header("Location: index.php");
    exit();
}
?>