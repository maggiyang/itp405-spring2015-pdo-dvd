<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Ratings</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">

  </head>
  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Home</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

<?php

//check if genre info is available otherwise redirect
if(!isset($_GET['rating'])){
    header('Location: index.php'); 
}

//write sql statement to query genre data and then display 
$rating = $_GET['rating']; 

$host = 'itp460.usc.edu'; 
$dbname = 'dvd'; 
$user = 'student';
$password = 'ttrojan'; 

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);

//prepared statement 
//prevent malicious sql injection attacks by eliminating user input and precompiling sql code 
$sql = "
    SELECT title
    FROM dvds
    INNER JOIN ratings
    ON rating_id = ratings.id
    WHERE rating_name LIKE ?
"; 

//-> is pointer, prepare compiles the template, optimizes for security and performance 
$statement = $pdo->prepare($sql); 
//bind parameter to like clause to enable display of only searched names
//param 1 indicates nth binding placeholder (?s) 
$like = $rating; 
$statement->bindParam(1, $like); 
$statement->execute();  //executes statement
$titles = $statement->fetchAll(PDO::FETCH_OBJ); 


?>

<?php if ($statement->rowCount() > 0) : ?>
    <div class="container">
    <div class="starter-template">
    <h1>Here are DVD titles with "<?php echo $rating ?>" rating</h1>
    <table class="table table-hover">
        <tr>
            <th>Title</th>
        </tr>
    <?php foreach($titles as $title) :?> 
        <tr>
            <td><?php echo $title->title ?></td>
        </tr>
    <?php endforeach ?>
    </table>
    <a href="index.php">Return to search menu</a>
    </div>
    </div>
<?php else : ?>
    <div class="container">
    <div class="starter-template">
        <p>This rating did not match any results</p>
        <a href="index.php">Return to search menu</a>
    </div>
    </div>
<?php endif ?>
      
</body>
</html>