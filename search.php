<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>DVD Search using PDO</title>

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

//check for errors when loading search.php, it redirects to home or index.php
if(!isset($_GET['dvd'])){
    header('Location: index.php'); 
}

//Global method $_GET, $_POST, $_REQUEST (works for both post and get method) 
$dvd = $_GET['dvd']; 

$host = 'itp460.usc.edu'; 
$dbname = 'dvd'; 
$user = 'student';
$password = 'ttrojan'; 

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);

//prepared statement 
//prevent malicious sql injection attacks by eliminating user input and precompiling sql code 
$sql = "
    SELECT title, genre_name, format_name, rating_name
    FROM dvds
    INNER JOIN genres
    ON genre_id = genres.id
    INNER JOIN formats 
    ON format_id = formats.id
    INNER JOIN ratings
    ON rating_id = ratings.id
    WHERE title LIKE ?
"; 

//-> is pointer, prepare compiles the template, optimizes for security and performance 
$statement = $pdo->prepare($sql); 
//bind parameter to like clause to enable display of only searched names
//param 1 indicates nth binding placeholder (?s) 
$like = '%' . $dvd . '%'; 
$statement->bindParam(1, $like); 
$statement->execute();  //executes statement
$titles = $statement->fetchAll(PDO::FETCH_OBJ); 

?>

<?php if ($statement->rowCount() > 0) : ?>
    <div class="container">
    <div class="starter-template">
    <h1>You searched for "<?php echo $dvd ?>"</h1>
    <tbod>
    <table class="table table-hover">
        <tr>
            <th>Title</th>
            <th>Genre</th>
            <th>Format</th>
            <th>Rating</th>
        </tr>
        <?php foreach($titles as $title) :?> 
        <tr>
            <td><?php echo $title->title ?></td>
            <td><?php echo $title->genre_name ?></td>
            <td><?php echo $title->format_name ?></td>
            <td>
                <a href="ratings.php?rating=<?php echo $title->rating_name ?>">
                    <?php echo $title->rating_name ?>
                </a>
            </td>
        </tr>
    <?php endforeach ?>
    </table>
    </tbod>
    <a href="index.php">Return to search menu</a>
    </div>
    </div>
<?php else : ?>
    <div class="container">
    <div class="starter-template"> 
        <p>Your search did not match any results</p>
        <a href="index.php">Return to search menu</a>
    </div>
    </div>
<?php endif ?>
</body>
</html>


