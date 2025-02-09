<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Database connection parameters
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hostel";

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $photos = $_FILES["photos"]["name"]; // Assuming you are storing the image filename in the database
    // $stuID  = "SELECT `id` FROM `hostel_form` WHERE id ='$idToUpdate'";
    // $resultempID = mysqli_fetch_assoc($conn->query($stuID));
    
    // $personIdIncrement = isset($resultempID['id']) ? $resultempID['id'] + 1 : 1;
    // $stu_id = 'LLOYD' . date('Y') . $personIdIncrement;


    // if ($_FILES["photos"]["size"] > 500000) {
    //     return json_encode(["message" => "Sorry, your file is too large."]);
        
    // } 
	

    // $target_dir = "uploads/".$stu_id.'/';

    //         $imageFileType = explode(".", $photos);
    //         $extension = strtolower($imageFileType[count($imageFileType) - 1]);
    //         $photos_path = 'prof_' . time() . '-emp.' . $extension;
    // if(!is_dir($target_dir)){
    //     mkdir($target_dir, 0755);
    // }
            
            
        
    // $target_file = $target_dir . $photos_path;
    // move_uploaded_file($_FILES["photos"]["tmp_name"], $target_file);
    //foreach ($_FILES['photos'] as $idToUpdate) {

        //}
        //echo "<pre>";print_r($_FILES['photos']['name'][$_POST['update'][0]]);die;
        // foreach ($_POST['update'] as $idToUpdate) {
            $idToUpdate = $conn->real_escape_string($_POST['update'][0]);
            
         
            $stuID  = "SELECT `id` FROM `hostel_form` WHERE id ='$idToUpdate'";
            $resultempID = mysqli_fetch_assoc($conn->query($stuID));
            
            $personIdIncrement = isset($resultempID['id']) ? $resultempID['id'] + 1 : 1;
            $stu_id = 'LLOYD' . date('Y') . $personIdIncrement;
            // Update the database with the student id
            $stu_idSQL = $conn->real_escape_string($stu_id);
            $sql2 = "UPDATE hostel_form SET id_card='$stu_idSQL' WHERE id=$idToUpdate";
            $conn->query($sql2);
            
            
            //echo "<pre>";print_r($_FILES['photos']['name']);die;
            // if ($_FILES["photos"]["size"] > 500000) {
            //     return json_encode(["message" => "Sorry, your file is too large."]);
                
            // } 
            
            $target_dir = "uploads/".$stu_id.'/';
            //print_r($target_dir); die();
            
            $imageFileType = explode(".", $_FILES["photos"]["name"][$_POST['update'][0]]);
            $extension = strtolower($imageFileType[count($imageFileType) - 1]);
            $photos_path = 'prof_' . time() . '-stu.' . $extension;
            if(!is_dir($target_dir)){
                mkdir($target_dir, 0755);
            }
                
        
        $target_file = $target_dir .'/'. $photos_path;
        //print_r($target_file); die();
        move_uploaded_file($_FILES["photos"]["tmp_name"][$_POST['update'][0]], $target_file);
        // $updateSql = "UPDATE hostel_form SET $photos='$target_file' WHERE id=$idToUpdate";
        // $conn->query($sql);
        // Update other columns based on the input fields

        foreach ($_FILES as $columnName => $values) {
            //echo "<pre>"; print_r($idToUpdate2); die();
            // echo "<pre>"; print_r($values); die();
            //if ($columnName != 'update') {
                $value = $conn->real_escape_string($values[$idToUpdate2][$target_file]);
                $sql1 = "UPDATE hostel_form SET $columnName='$photos_path' WHERE id=$idToUpdate";
                $conn->query($sql1);
            //}
        }
        foreach ($_POST as $columnName => $values) {
            if ($columnName != 'update') {
                $value = $conn->real_escape_string($values[$idToUpdate]);
                $sql = "UPDATE hostel_form SET $columnName='$value' WHERE id=$idToUpdate";
                $conn->query($sql);
            }
        }
    // }

    // Set success message in session variable
    $_SESSION['success_message'] = "Data updated successfully.";

    // Close the database connection
    $conn->close();

    // Redirect back to the main page after updating
    header("Location: index.php");
    exit();

    // palak chaudhary

}
?>
