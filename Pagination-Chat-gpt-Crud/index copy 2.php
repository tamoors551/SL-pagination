<?php
include 'db.php';

// =========================================================================search term-code============================================================================================
// ========================================================================Start the session===========================================================================================

// Start the session
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION["username"])) {
  header("Location: login.php");
  exit;
}

// =========================================================================Pagination-Count============================================================================================
// Default number of records per page
$default_records_per_page = 5;

// Get the number of records per page from the URL parameter or default value
$records_per_page = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : $default_records_per_page;
if ($records_per_page <= 0) {
    $records_per_page = $default_records_per_page;
}

// Calculate the total number of pages
$total_records_query = "SELECT COUNT(*) FROM students";
$total_records_result = $conn->query($total_records_query);
$total_records = $total_records_result->fetch_row()[0];
$total_pages = ceil($total_records / $records_per_page);

// Get the current page from URL parameter (default to 1 if not set)
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) {
    $current_page = 1;
} elseif ($current_page > $total_pages) {
    $current_page = $total_pages;
}

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $records_per_page;
// =========================================================================Pagination-Count============================================================================================

// =========================================================================search term-code============================================================================================

// Perform search if search term is provided, otherwise fetch students for the current page
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM students WHERE CONCAT(id, first_name, last_name) LIKE '%$search%' LIMIT $offset, $records_per_page";
    $result = $conn->query($sql);
} else {
    $sql = "SELECT * FROM students LIMIT $offset, $records_per_page";
    $result = $conn->query($sql);
}
// =========================================================================search term-code============================================================================================

// =========================================================================Query for the Image============================================================================================

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
<!-- // =========================================================================Query for the Image============================================================================================ -->

<!-- // ======================================================================HTML-CODE============================================================================================= -->

<!DOCTYPE html>
<html>

<head>
  <title>CRUD App</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Tamoor</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Link
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Link</a>
        </li>
      </ul> <form action="" method="GET">
    <div class="input-group mb-3">
      <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
                                                  echo $_GET['search'];
                                                } ?>" class="form-control" placeholder="Search by Name or ID">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </form>
    </div>
  </div>
</nav>
  <h1>Student Records</h1>

  <a class="btn btn-warning" href="create.php">Add New Student</a>
  <br><br>

  <!-- // =========================================================================search term-code============================================================================================ -->

  <form action="" method="GET">
    <div class="input-group mb-3">
      <input type="text" name="search" value="<?php if (isset($_GET['search'])) {
                                                  echo $_GET['search'];
                                                } ?>" class="form-control" placeholder="Search by Name or ID">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </form>
  <!-- // =========================================================================search term-code============================================================================================ -->

  <!-- // =========================================================================Show entries dropdown============================================================================================ -->

  <form action="" method="GET">
    <div class="input-group mb-3">
      <label class="input-group-text" for="records_per_page">Show</label>
      <select class="form-select" id="records_per_page" name="records_per_page" onchange="this.form.submit()">
        <option value="5" <?php if ($records_per_page == 5) echo 'selected'; ?>>5</option>
        <option value="10" <?php if ($records_per_page == 10) echo 'selected'; ?>>10</option>
        <option value="20" <?php if ($records_per_page == 20) echo 'selected'; ?>>20</option>
      </select>
      <input type="hidden" name="search" value="<?php if (isset($_GET['search'])) echo $_GET['search']; ?>">
    </div>
  </form>
  <br>
  <!-- // =========================================================================Show entries dropdown============================================================================================ -->
  <a class="btn btn-warning" href="create.php">Add New Student</a>

  
  <table border="1" class="table">
    <tr>
      <th>ID</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Age</th>
      <th>Image</th>
      <th>Actions</th>
    </tr>
    <tbody>
 <!-- // =========================================================================Code for Fetch data and show on the table============================================================================================ -->

      <?php
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
 <!-- // =========================================================================Code for Fetch data and show on the table============================================================================================ -->

  <!-- // ======================================================================== Pagination controls============================================================================================ -->

  <!-- Pagination controls -->
  <nav>
    <ul class="pagination">
      <?php if ($current_page > 1): ?>
        <li class="page-item"><a class="page-link" href="index.php?page=<?= $current_page - 1 ?>&records_per_page=<?= $records_per_page ?>&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">Previous</a></li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>"><a class="page-link" href="index.php?page=<?= $i ?>&records_per_page=<?= $records_per_page ?>&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>"><?= $i ?></a></li>
      <?php endfor; ?>

      <?php if ($current_page < $total_pages): ?>
        <li class="page-item"><a class="page-link" href="index.php?page=<?= $current_page + 1 ?>&records_per_page=<?= $records_per_page ?>&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">Next</a></li>
      <?php endif; ?>
    </ul>
  </nav>
  <!-- // ======================================================================== Pagination controls============================================================================================ -->

</body>

</html>
