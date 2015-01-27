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
//var_dump($titles);  //dump info on any variable

echo "DVD titles with $rating rating"; 

?>

<?php if ($statement->rowCount() > 0) : ?>

    <?php foreach($titles as $title) :?> 
    <div>
        <br><?php echo $title->title ?>
    </div>
    <?php endforeach ?>
    <a href="index.php">Return to search menu</a>
<?php else : ?>
    <br> 
    <p>This rating did not match any results</p>
    <a href="index.php">Return to search menu</a>
<?php endif ?>