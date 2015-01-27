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

<?php if ($statement->rowCount() > 0) : ?>
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
    <a href="index.php">Return to search menu</a>
<?php else : ?>
    <br> 
    <p>Your search did not match any results</p>
    <a href="index.php">Return to search menu</a>
<?php endif ?>





