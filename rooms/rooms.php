<?php
require "db.php";

$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Rooms</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<div class="p-5 bg-primary text-white text-center">
  <h1>Rooms</h1>
  <p>Find and book your room at the best price</p>
</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" href="http://localhost/rooms/mainPage.html">Main Page</a>
      </li>
      
      
    </ul>
  </div>
</nav>

<div class="container mt-5">

<?php foreach ($rooms as $room): ?>
  <div class="row border rounded-3 p-3 mb-4 align-items-center">

    <div class="col-md-4">
      <img src="<?= htmlspecialchars($room['image']) ?>" class="img-fluid rounded">
    </div>

    <div class="col-md-4">
      <h4>Room <?= htmlspecialchars($room['room_number']) ?></h4>
     <ul>
      <li><p><?= htmlspecialchars($room['type']) ?></p></li>
      <li><p><?= htmlspecialchars($room['bed']) ?></p></li>
      <li><p class="fw-bold text-primary"><?= $room['price'] ?>€ / night</p></li>
	  </ul>
    </div>

    <div class="col-md-4 text-md-end">
      <span class="badge bg-success fs-6"><?= htmlspecialchars($room['rating']) ?></span><br>
      <a href="room.php?id=<?= $room['id'] ?>" class="btn btn-primary mt-3">
  Book Now
</a>

    </div>

  </div>
<?php endforeach; ?>

</div>

<?php include 'footer.php'; ?>

</body>
</html>

