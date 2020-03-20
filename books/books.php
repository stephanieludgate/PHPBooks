<?php
 
require "inc/function.inc.php"; // configuration file
connectDB(); // Connect to database

if ( !loggedIn() ){
  header("Location: login.php");
  die();
}

// set initial values
$pageTitle = "All Books"; // Dynamic page title
$nonArchived = 0;
$availableBooks = DB::query("SELECT b.id, b.title, a.author, b.pub_year, b.description FROM book_books b JOIN book_authors a ON b.author=a.id WHERE b.archived=%i", $nonArchived);

// if GET method used
if ( $_SERVER['REQUEST_METHOD'] == "GET"){
  if (isset ($_GET['message'] )){
    $title  = isset($_GET['title']) ? $_GET['title'] : "";
  
    switch ($_GET['message']){
      case "removed" :
        $errorMessage = "Book $title has been removed";
        break;
      case "updated" :
        $errorMessage = "Book $title has been saved";
        break;
      case "created" :
        $errorMessage = "Book $title has been created";
        break;
      case "notFound":
        $errorMessage = "No book found";
        break;
    }
  }
}
include "inc/header.inc.php"; 
?>
      <div class="row">
        <div class="col-12">
          <h2>Bookstore Books <a class="btn btn-sm btn-success pull-right" href="book.php">Create</a></h2>
          <hr class="mb-4"> 
          <?php displayErrors(); ?>
        </div>
          
      </div>
      <div class="row">
        <div class="col-md-12 p-3">
          <table class="table table-hover table-striped table-bordered">
            <thead class="thead-dark">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Author</th>
                <th>Year</th>
                <th>Description</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($availableBooks as $row){ ?>
                <tr>
                  <td><?=$row['id'];?></td>
                  <td><?=$row['title'];?></td>
                  <td><?=$row['author']; ?></td>  
                  <td><?=$row['pub_year']; ?></td>
                  <td><?=$row['description']; ?></td>
                  <td>
                    <a href="book.php?id=<?=$row['id'];?>&mode=edit">Edit</a> | 
                    <a href="book.php?id=<?=$row['id'];?>&mode=delete">Delete</a>
                  </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>

      </div>
<?php include "inc/footer.inc.php"; ?>
