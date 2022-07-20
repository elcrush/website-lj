<?php
  $target_dir = "./assets/images/profile/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    // Check if image file is a actual image or fake image

    if(isset($_POST["change"])) {

    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    $sql = "UPDATE users SET userPic = '".$_FILES['fileToUpload']['name']."' WHERE username = '" . $username . "'";

    $check = $conn->query($sql);

    if($check !== false) {
    echo "<a href = profile.php> Profile pciture has been changed </a>" . $check["mime"] . ".";
    $uploadOk = 1;

    } else {
    echo "File is not an image.";
    $uploadOk = 0;
    }
    } else {

    echo"did not change";
    }
?>