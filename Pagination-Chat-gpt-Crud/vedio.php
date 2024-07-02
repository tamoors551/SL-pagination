<!doctype html>
<html lang="en">
  <head>
    <title>Video page</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"crossorigin="anonymous"
    />
  </head>

  <body>

 

  <form method="post" enctype="multipart/form-data">
 <h1> Select video to upload:</h1> 
 <br>
  <input  type="file" name="video" accept=".mp4, .avi" accept="video/*">
  <br><br><br>
  <button class="btn btn-danger" name="submit" type="submit">Upload Video</button>
  
  <a class="btn btn-success"  href="download.php?file=myvedio.mp4">Download Video</a>

</form>
<!-- this is the code for the Video upload -->
<?php
  include 'db.php';
  $result = $conn->query("SELECT * FROM video");

 while ($row = $result->fetch_assoc()) : ?>
  <video width="375" height="275" controls autoplay muted>
    <source src="uploads/<?php echo $row['video']; ?>" type="video/mp4">
    Your browser does not support the video tag.
  </video>
<?php endwhile; ?>
<!-- this is the code for the Video upload -->

<!-- this is the code for the submit video  -->
<?php
include 'db.php';
// Check if the form was submitted with a file
if (isset($_POST['submit'])) {
  // <!-- this is the code for the submit video  -->

  // Get information about the uploaded video
  $fileName = $_FILES['video']['name'];
  $fileSize = $_FILES['video']['size'];
  $tmpName = $_FILES['video']['tmp_name'];

  // Define allowed extensions (modify as needed)
  $allowedExtensions = array('mp4', 'avi', 'mov');

  // Get file extension
  $extension = pathinfo($fileName, PATHINFO_EXTENSION);

  // Validate file size and extension
  if ($fileSize > 50000000) { // Limit to 50MB (adjust as needed)
    echo "Error: File size exceeds limit (50MB)";
  } else if (!in_array($extension, $allowedExtensions)) {
    echo "Error: Invalid file type. Only " . implode(', ', $allowedExtensions) . " allowed.";

  } else {
    // Generate a unique name for the uploaded file (optional)
    $newName = uniqid('', true) . '.' . $extension;

    // Define upload path (modify as needed)
    $uploadPath = 'uploads/';

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($tmpName, $uploadPath . $newName)) {

      $query = "INSERT INTO video (id, video) VALUES (NULL, '$newName')";
      $result = $conn->query($query);
      echo "Video uploaded successfully!";
      // You can optionally save video information to a database here
    } else {
      echo "Error: Failed to upload video.";
    }
  }
} else {
  echo "No video selected for upload.";
}
?>

    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
      integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
      crossorigin="anonymous"
    ></script>
  </body>
</html>




