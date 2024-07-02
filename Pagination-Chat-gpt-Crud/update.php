<?php

// Include the database connection file (assuming it's named db.php)
include 'db.php';

// Check if an "id" parameter is present in the URL (GET request)
if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Prepare a SQL query to select student details based on the provided ID
  $result = $conn->query("SELECT * FROM students WHERE id = $id");

  // If the query is successful, fetch the student data as an associative array
  if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
  } else {
    // Handle the case where no student is found with the provided ID (optional)
    echo "Error: Student not found.";
  }
}

// Check if the update form was submitted (POST request)
if (isset($_POST['update_student'])) {
  $id = $_POST['id'];
  $fname = $_POST['f_name'];
  $lname = $_POST['l_name'];
  $age = $_POST['age'];
  $oldimage = $_POST['old_image']; // Store the old image filename

  // Check if a new image was uploaded
  if ($_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
    // No new image uploaded, use the old image filename
    $file_name = $oldimage;
  } else {
    // New image uploaded, extract details
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $upload_directory = "uploads/";

    // Move the uploaded image to the uploads directory
    move_uploaded_file($file_tmp, $upload_directory . $file_name);

    // If an old image existed, delete it
    if ($oldimage && file_exists("uploads/$oldimage")) {
        unlink("uploads/$oldimage");
    }
  }

  // Prepare an SQL query to update the student record
  $query = "UPDATE students SET first_name = '$fname', last_name = '$lname', age = '$age', image = '$file_name' WHERE id = $id";

  // Execute the update query
  $result = $conn->query($query);

  // Check the execution result
  if ($result) {
    // Update successful, redirect to the index page
    header("Location: index.php");
  } else {
    // Update failed, display error message with details from the database connection
    echo "Error: " . $conn->error;
  }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Update Student</title>
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
</head>
<body>
    <h1>Update Student</h1>
    <form  action="" method="POST" class="form-control" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
        <input type="hidden" name="old_image" value="<?php echo $student['image']; ?>">
        <label for="f_name">First Name:</label>
        <input type="text" class="form-control"  name="f_name" value="<?php echo $student['first_name']; ?>" required><br>
        <label for="l_name">Last Name:</label>
        <input type="text" class="form-control" name="l_name" value="<?php echo $student['last_name']; ?>" required><br>
        <label for="age">Age:</label>
        <input type="number" class="form-control" name="age" value="<?php echo $student['age']; ?>" required><br>
        <label for="image">Image:</label>
        <input type="file" name="image"><br>
        <button class="btn btn-primary" type="submit" name="update_student">Update Student</button>
    </form>
</body>
</html>
