<?php
include 'db.php';

// Start the session
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION["username"])) {
  header("Location: login.php");
  exit;
}

// Query for the Image
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $image_query = $conn->query("SELECT image FROM students WHERE id = $id");
    $image = $image_query->fetch_assoc()['image'];

    if ($image && file_exists("uploads/$image")) {
        unlink("uploads/$image");
    }

    $conn->query("DELETE FROM students WHERE id = $id");
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>CRUD App</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">





</head>

<body>

  <div class="container">
    <h1 class="mt-4">Student Records</h1>

    <a class="btn btn-warning mb-3" href="create.php">Add New Student</a>

    <table class="table table-bordered" id="myTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Age</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * FROM `students`";
          $result = $conn->query($sql);

          if (mysqli_num_rows($result) > 0) {
              while ($row = $result->fetch_assoc()) : ?>
              <tr>
                  <td><?php echo $row['id']; ?></td>
                  <td><?php echo $row['first_name']; ?></td>
                  <td><?php echo $row['last_name']; ?></td>
                  <td><?php echo $row['age']; ?></td>
                  <td><img src="uploads/<?php echo $row['image']; ?>" width="50" height="50"></td>
                  <td>
                    <a class="btn btn-primary" href="download.php?file=<?php echo $row['image']; ?>">Download</a>
                    <a class="btn btn-danger" href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a class="btn btn-success" href="index.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                  </td>
              </tr>
              <?php endwhile;
          } else {
              echo "<tr><td colspan='6'>No results found.</td></tr>";
          }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#myTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
        ]
      });
    });
  </script>


</body>

</html>
