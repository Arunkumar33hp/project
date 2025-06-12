<?php
$con = mysqli_connect("localhost", "root", "", "jcom");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event'])) {
    $event = $_POST['event'];
    $day = $_POST['day'];

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = $_FILES['image']['name'];
    $fileTmp = $_FILES['image']['tmp_name'];
    $filePath = $uploadDir . basename($fileName);

    if (move_uploaded_file($fileTmp, $filePath)) {
        $sql = "INSERT INTO events (event, day, image) VALUES ('$event', '$day', '$filePath')";
        mysqli_query($con, $sql);
  
        header("Location: admin.php");
        exit;
    } else {
        echo "Failed to upload image.";
    }
}

$sql = "SELECT * FROM events";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Weekly Events</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }

        h3 {
            font-weight: 600;
            color: #333;
        }

        table img {
            max-height: 70px;
            border-radius: 8px;
        }

        .table thead {
            background-color: #343a40;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        form {
            margin-top: 30px;
        }

        form .form-control {
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #0d6efd;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-outline-light:hover {
            background-color: #ffffff20;
        }

        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="ms-auto">
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
</nav>

<!-- Main Container -->
<div class="container my-5">

    <!-- Weekly Events -->
    <h3 class="mb-4">Weekly Events</h3>
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Day</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
       <tbody>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo $row['event']; ?></td>
        <td><?php echo $row['day']; ?></td>
       <td>       <img src="<?php echo $row['image']; ?>" width="100" height="70" class="img-thumbnail"></td>

        <td>
            <form method="POST" action="delete.php">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
            </form>
        </td>
    </tr>
<?php } ?>
</tbody>

    </table>
    <h5 class="mt-5">Add New Event</h5>
    <form method="POST" action="admin.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Event Name</label>
            <input type="text" class="form-control" name="event" placeholder="Enter event name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Day</label>
            <select class="form-select" name="day" required>
                <option selected disabled>Choose a day</option>
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
                <option>Saturday</option>
                <option>Sunday</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Event Image</label>
            <input type="file" class="form-control" name="image"required>
        </div>
        <button type="submit" class="btn btn-primary">Add Event</button>
    </form>
</div>

</body>
</html>


 
