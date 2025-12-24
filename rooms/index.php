<!DOCTYPE html>
<html lang="en">
<head>
  <title>Hotels Mygdalos</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="d-flex flex-column min-vh-100">

<div class="container-fluid p-5 bg-primary text-white text-center">
  <div class="row">
    <div class="col-9">
      <h1>Hotels Mygdalos</h1><br>
      <p>Find the best hotels at your area!</p>
    </div>

    <div class="col-3">
      <div class="dropdown">
        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
          Menu
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="admin_login.php">Admin</a></li>
          <li><a class="dropdown-item" href="rooms.php">Rooms</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid p-5 flex-grow-1">
  <div class="row justify-content-left">
    
    <div class="col-md-5"> <div id="demo" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-indicators">
          <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
          <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
          <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
        </div>
        
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="photos/hotel1.jpg" alt="Los Angeles" class="d-block w-100">
          </div>
          <div class="carousel-item">
            <img src="photos/hotel2.jpg" alt="Chicago" class="d-block w-100">
          </div>
          <div class="carousel-item">
            <img src="photos/hotel3.jpg" alt="New York" class="d-block w-100">
          </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    </div>  

    <div class="col-md-7">
      <div class="container-fluid p-5 justify-content-center">
        <p>Welcome to our Hotel. We have nice and cozy rooms for you to enjoy your stay</p>
        <h1 class="text-center">SINCE 1955</h1>
        <p>Our guests enjoy our facilities since 1955</p>
      </div>  
    </div>  

  </div>  
</div>  

<?php include 'footer.php'; ?>

</body>
</html>