<?php
 
require "inc/function.inc.php"; // configuration file
connectDB(); // Connect to database

if ( !loggedIn() ){
  header("Location: login.php");
  die();
}

// set initial values
$data = array('title'=>"", 'author'=>"", 'year_pub' =>"", 'description'=>"", 'archived'=>"", 'id'=>"");
$pageTitle = "Add a Book"; // Dynamic page title
$currentYear = date('Y');
$oldestYear = 1950;
$availableAuthors = DB::query("SELECT * FROM book_authors");

// if GET method used
if ( $_SERVER['REQUEST_METHOD'] == "GET"){
  // check IF an id was given
  if (isset($_GET['id']) && is_numeric($_GET['id'])){

    // query db to check if book exists
    $data =  DB::queryFirstRow("SELECT * FROM book_books WHERE id=%i", $_GET['id']);
		if (DB::count() != 0){ // book exists

      if ( $_GET['mode'] == "delete" ){
        DB::delete('book_books', "id=%i", $_GET['id']);
        logMsg("books", "warning", "Book was removed", $data['title']);
        header("Location: books.php?message=removed&title=". htmlentities($data['title']));
        die();

      } else if ( $_GET['mode'] == "edit" ){
        $pageTitle = "Edit Book";
      }
    } else{
      header("Location: books.php?message=notFound");
      die();
    }
  }
}else if ( $_SERVER['REQUEST_METHOD'] == "POST"){
  // set info for form incase of error
  $id = isset($_POST['id']) ? $_POST['id'] : "";
	$title = isset($_POST['title']) ? $_POST['title'] : "";
  $author = isset($_POST['author']) ? $_POST['author'] : "";
  $publication = isset($_POST['year_pub']) ? $_POST['year_pub'] : "";
  $description = isset($_POST['description']) ? $_POST['description'] : "";
  
  // validate our data
  if (isFieldEmpty($_POST['title']) || isFieldEmpty($_POST['author']) || 
  isFieldEmpty($_POST['year_pub']) || isFieldEmpty($_POST['description'])){
    $errorMessage = "All fields are required";
  } 
  // unclear if I should have further validation here, maybe!

  // check if we can save
  if ( $errorMessage == "" ){
    // no error = we can save to the databse

    // setup data to be inserted into the database
    $vars = array(
			'title' => $_POST['title'],  // title
			'author' => $_POST['author'], // author
			'pub_year' => $_POST['year_pub'], // publication year
      'description' => $_POST['description'], // description
    );
    // we check for an id so we know if it's a create or an update
    if (isset($_POST['id']) && is_numeric($_POST['id']))
      $vars['id'] = $_POST['id'];

    /* INSERT ON DUPLICATE UPDATE statement - 
        meekro handles this for us should the id we give exist, it will update the record
          if it does not exist it will insert a new record
    */
    DB::insertUpdate("book_books", $vars);
    //updated so different redirection
    $action = isset($vars['id']) ? "updated" : "created";
    
    if(isset($action) && $action=='created'){
      logMsg("books", "notice", "New book added", $_POST['title']);
    } else if(isset($action) && $action=='updated'){
      logMsg("books", "info", "Book was updated", $_POST['title']);
    }

		header("Location: books.php?message=$action&title=". htmlentities($title));
  }
  $data = $_POST; // this seems to save text input but not selects, will work on this
}
include "inc/header.inc.php"; 
?>
      <div class="row">
        <div class="col-12">
          <h2>Bookstore Book - <?= $pageTitle ?></h2>
          <hr>
          <?php displayErrors(); ?>
          <div class="col-sm-12 col-md-8 offset-md-2">
            <form action="" method="POST">
              <div class="form-group">
                <label>Book Title</label>
                <input type="text" class="form-control" name="title" value="<?=$data['title']; ?>">
              </div>
              <div class="form-group">
                <label>Author</label>
                <select class="form-control" name="author" />
                  <option value="">----</option>
                  <?php
                      foreach($availableAuthors as $row){ ?>
                        <option value="<?= $row['id'] ?>" 
                        <?php 
                          if(isset($_GET['id']) && $data['author']==$row['id']){
                            echo "selected";
                          } ?> ><?= $row['author'] ?></option>
                      <?php } 
                    ?>
                </select>
              </div>
              <div class="form-group">
                <label>Year of Publication</label>
                <select class="form-control" name="year_pub" />
                    <option value="">----</option>
                    <?php
                      for($i = $currentYear; $i >= $oldestYear; $i--){ ?>
                        <option value="<?= $i ?>"
                        <?php 
                          if(isset($_GET['id']) && $data['pub_year']==$i){
                            echo "selected";
                          } ?>
                        ><?= $i ?></option>
                      <?php }
                    ?>
                  </select>
              </div>
              <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" rows="3" name="description"><?=$data['description']; ?></textarea>
              </div>
              <!-- 
              <div class="form-group">
                <label>Archived</label>
                <input type="checkbox" name="archived">
              </div>
                      -->
              <button type="submit" class="btn btn-primary">Submit</button>
              <input type="hidden" name="id" value="<?=$data['id']; ?>" />
            </form>
          </div>
          
        </div>
      </div>
<?php include "inc/footer.inc.php"; ?>
