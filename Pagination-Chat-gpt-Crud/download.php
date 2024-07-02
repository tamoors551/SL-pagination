<!-- https://www.php.net/manual/en/function.readfile.php -->
<?php
// Check if the 'file' parameter is set in the URL
if (isset($_GET['file'])) {
    // If yes, store the file name in a variable
    $file = $_GET['file'];
    // Construct the file path by combining the uploads directory and the file name
    echo $file_path = "uploads/" . $file;
  
    // Check if the file exists in the uploads directory
    if (file_exists($file_path)) {
      // Set headers to prompt a file download
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file_path));
  
      // Read the file and send it to the output buffer
      readfile($file_path);
      exit;
    } else {
      echo "File not found.";
    }
  } else {
    echo "No file specified.";
  }
  
?>


<!-- this is for the Download -->
<?php
// Check if the 'file' parameter is set in the URL
// if (isset($_GET['file'])) {
//   $file = $_GET['file'];
//   $file_path = "uploads/" . $file;

  
//   // Check if the file exists in the uploads directory
//   if (file_exists($file_path)) {
//     // Set headers to prompt a file download
//     header('Content-Description: File Transfer');
//     header('Content-Type: application/octet-stream');
//     header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
//     header('Expires: 0');
//     header('Cache-Control: must-revalidate');
//     header('Pragma: public');
//     header('Content-Length: ' . filesize($file_path));

//     // Read the file and send it to the output buffer
//     readfile($file_path);
//     exit;
//   } else {
//     echo "File not found.";
//   }
// } else {
//   echo "No file specified.";
// }
?>
