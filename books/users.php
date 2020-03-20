<?php 
require "inc/function.inc.php"; // configuration file
connectDB(); // Connect to database

if (isset ($_GET['message'] )){
  $name  = isset($_GET['name']) ? $_GET['name'] : "";

  switch ($_GET['message']){
    case "removed" :
      $errorMessage = "User $name has been removed";
      break;
    case "updated" :
      $errorMessage = "User $name has been saved";
      break;
    case "created" :
      $errorMessage = "User $name has been created";
      break;
    case "notFound":
      $errorMessage = "No user found";
      break;
  }
}

// fetch users from database
$sql = "SELECT * FROM book_users ORDER BY name ASC";
$results = $db->query( $sql );
if ( !$results )
  die("Error during select: " . $db->error );

$pageTitle = "Users"; // Dynamic page title
include "inc/header.inc.php"; // header 
?>
      <div class="row mt-4">
        <div class="col-12">
          <h2>List All User<a class="btn btn-sm btn-success pull-right" href="user.php">Create</a></h2>
          <hr>
          <?php displayErrors(); ?>
        </div>
        
        <div class="table-responsive col-12">
          <table class="table table-hover table-striped table-bordered">
            <thead class="thead-dark">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
             <?php while ($row = $results->fetch_assoc()){ ?>
                <tr>
                  <td><?=$row['id'];?></td>
                  <td><?=$row['name'];?></td>
                  <td><?=$row['email']; ?></td>  
                  <td><?=$row['pword']; ?></td>
                  <td>
                  <!-- <a href="user_edit.php?id=<?=$row['id'];?>&mode=edit">Edit</a> | 
                    <a href="user_edit.php?id=<?=$row['id'];?>&mode=delete">Delete</a> -->
                    <a href="user.php?id=<?=$row['id'];?>&mode=edit">Edit</a> | 
                    <a href="user.php?id=<?=$row['id'];?>&mode=delete">Delete</a>
                  </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

<?php include "inc/footer.inc.php"; //footer ?>