<?php
namespace App\Services;
require_once('vendor/autoload.php');

use App\Database\DatabaseConnection;
use Rakit\Validation\Validator;

class HotelService
{
    public $name;
    public $description;
    public $type;
    public $facility;
    public $status;
    
    public static function insertData($request, $files)
    {
        $validator = new Validator;
        $validation = $validator->make($request + $files, [
            'name' => 'required',
            'type' => 'required', 
            'facility' => 'array',
            'description' => 'required',
            'status' => 'required',
            'image' => 'array',
            'image.*'  => 'required|uploaded_file:0,16M,png,jpeg',

        ]);
        $validation->validate();

        if ($validation->fails())
        {
        $errors = $validation->errors();
        echo "<pre>";
        print_r($errors->firstOfAll());
        echo "</pre>";
        exit;
        } 
        $name = $request['name'];
        $type = $request['type'];
        $facility = isset($request['facility']) ? $request['facility'] : array();
        $description = $request['description'];
        $status = $request['status'];
        $facilityString = implode(",", $facility);
        $db = new DatabaseConnection();
        $table = 'hotels';
        $data = array(
            'name' => $name,
            'type' => $type,
            'facility' => $facilityString,
            'description' => $description,
            'status' => $status
        );
        $hotelId = $db->insert($table, $data);
        
        for ($i = 0; $i < count($files['image']['tmp_name']); $i++) {
            $fileName = $files['image']['name'][$i];
            $fileSize = $files['image']['size'][$i];
            $fileTmp = $files['image']['tmp_name'][$i];
            $fileType = $files['image']['type'][$i];
            
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $currentDateTime = date('Y-m-d_H-i-s');
            $newFileName = $currentDateTime . '_' . $i . '.' . $fileExtension;
            $uploadFolder = 'images/' . $newFileName;
            move_uploaded_file($fileTmp, $uploadFolder);

            $table = 'images';
            $data = array(
                'hotel_id' => $hotelId,
                'path' => $uploadFolder,
            );
            $imageId = $db->insert($table, $data);
        }
        
        return true;
    }
    
    public static function getAllHotels()
    {
        $db = new DatabaseConnection();
        $table = 'hotels';
        $columns = [];
        $conditions = [];
        $result = $db->select($table, $conditions, $columns);
        $hotels = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $hotelId = $row['id'];
            $table = 'images';
            $columns = ['path'];
            $conditions = ['hotel_id' => $hotelId];
            $imageResult = $db->select($table, $conditions, $columns);
            $images = [];

            while ($imageRow = mysqli_fetch_assoc($imageResult)) {
                $images[] = $imageRow['path'];
            }

            $row['images'] = $images;
            $hotels[] = $row;
        }

        return $hotels;
    }

    public static function deleteHotel($hotelId)
    {
        $db = new DatabaseConnection();
        $deleteQuery = "DELETE FROM hotels WHERE id = '$hotelId'";
        mysqli_query($db->con, $deleteQuery);
    }

    public static function updateHotel($hotelId, $name, $type,  $description, $facility, $status)
    {
        {
            $db = new DatabaseConnection();
            $updateQuery = "UPDATE hotels SET name = '$name', type = '$type', description = '$description', facility = '$facility', status = '$status' WHERE id = '$hotelId'";
            mysqli_query($db->con, $updateQuery);
        }
    }

    public  static function deleteImages($hotelId)
    {
        $db = new DatabaseConnection();
        $query = "DELETE FROM images WHERE hotel_id = '$hotelId'";
        mysqli_query($db->con, $query);
    }

    public  static function getHotel($hotelId)
    {
        $db = new DatabaseConnection();
        $table = 'images';
        $columns = [];
        $conditions = ['id' => $hotelId];
        $result = $db->select($table, $conditions, $columns);
        return mysqli_fetch_assoc($result);
    }
}
?>
