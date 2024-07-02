<?php
include 'db.php';
if (isset($_POST['create_student'])) {
  // Check if the form was submitted with a "create_student" button click

  $fname = $_POST['f_name'];
  // Get the value from the form field named "f_name" and store it in $fname

  $lname = $_POST['l_name'];
  // Get the value from the form field named "l_name" and store it in $lname

  $age = $_POST['age'];
  // Get the value from the form field named "age" and store it in $age

  $file_name = $_FILES['image']['name'];
  // Get the original filename of the uploaded image from the $_FILES superglobal
  // 'image' corresponds to the name attribute of the file input field in the form

  $file_tmp = $_FILES['image']['tmp_name'];
  // Get the temporary location of the uploaded image on the server from $_FILES
  // This is where the uploaded file is stored temporarily before processing

  $upload_directory = "uploads/";
  // Define the directory path for storing uploaded images

  $fileName = $_FILES['image']['name'];
  // Get the original filename again (duplicate assignment)

  $fileSize = $_FILES['image']['size'];
  // Get the size of the uploaded image in bytes from $_FILES

  $tmpName = $_FILES['image']['tmp_name'];
  // Get the temporary location again (duplicate assignment)

  $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
  // Define an array of allowed image extensions for validation

  $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
  // Get the file extension of the uploaded image in lowercase
  // pathinfo() function extracts information about a file path
  // PATHINFO_EXTENSION returns the file extension
  // strtolower() converts the extension to lowercase for case-insensitive comparison


  if ($fileSize > 5000000) { // Limit to 5MB (adjust as needed)
    echo "Error: File size exceeds limit (5MB)";
} else if (!in_array($extension, $allowedExtensions)) {
    echo "Error: Invalid image type. Only " . implode(', ', $allowedExtensions) . " allowed.";
  } else {
    // Generate a unique name for the uploaded image (optional)
    $newName = uniqid('', true) . '.' . $extension;

    // Define upload path (modify as needed)
    $uploadPath = 'uploads/images/'; // Create a subdirectory for images

    // Validate image using getimagesize (optional)
    $check = getimagesize($tmpName);
    if ($check === false) {
      echo "Error: File is not a valid image.";
      exit;
    }



    move_uploaded_file($file_tmp, $upload_directory . $file_name);

    $query = "INSERT INTO students (first_name, last_name, age, image) VALUES ('$fname', '$lname', '$age', '$file_name')";
    $result = $conn->query($query);

    if ($result) {
      header("Location: index.php");
    } else {
      echo "Error: " . $conn->error;
    }
  }
} else {
  echo "No image selected for upload.";
}
?>












<!DOCTYPE html>
<html>

<head>
  <title>Add New Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
  <h1>Add New Student</h1>
  <form action="create.php" method="POST" class="form-control" enctype="multipart/form-data">
    <label for="f_name">First Name:</label>
    <input class="form-control" type="text" name="f_name" required><br>
    <label for="l_name">Last Name:</label>
    <input class="form-control" type="text" name="l_name" required><br>
    <label for="age">Age:</label>
    <input class="form-control" type="number" name="age" required><br>
    <label for="image">Image:</label>
    <!-- accept=".jpg, .jpeg" it is used for the accept attribute of the file input --> 
      
    <input class="form-control" type="file" name="image" accept=".jpg, .jpeg" required><br>

    <button class="btn btn-warning" type="submit" name="create_student">Add Student</button>
  </form>
</body>

</html>