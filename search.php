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
//var_dump($titles);  //dump info on any variable

echo "You searched for '$dvd'"; 
?>

<?php foreach($titles as $title) :?> 
    <div>
        <?php echo $title->title ?><br>
        <?php echo $title->genre_name ?><br>
        <?php echo $title->format_name ?><br>
        <?php echo $title->rating_name ?><br>
        
<!--
        <a href="genres.php?genre=<?php echo $song->genre ?>">
            <?php echo $song->genre ?>
        </a>
-->
    </div>
<?php endforeach ?>




