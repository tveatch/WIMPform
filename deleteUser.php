<?php 

    // First we execute our common code to connection to the database and start the session 
    require("_inc/common.php"); 
     
    // At the top of the page we check to see whether the user is logged in or not 
    if(empty($_SESSION['user'])) 
    { 
        // If they are not, we redirect them to the login page. 
        header("Location: login.php"); 
         
        // Remember that this die statement is absolutely critical.  Without it, 
        // people can view your members-only content without logging in. 
        die("Redirecting to login.php"); 
    }
	
	// Log out user after session has reached 30 minutes
	if ($_SESSION['timeout'] + 30 * 60 < time()) {
     // session timed out
	 header("Location: login.php");
  	}
	
	// assign id sent from previous page link to a variable
	$id = $_GET['id'];
	
	try { 
     
        // Delete row in "users" where id eauals
  		$query = "DELETE FROM users WHERE id = $id";
  		$count = $db->exec($query);

  		$db = null;        // Disconnect
		}
	catch(PDOException $e) {
  		echo $e->getMessage();
	}
?>
<?php
// load in html page header
require("header.php");
?>
<?php
// The content below will only load for users logged in with admin as user level
if ($_SESSION['user']['level']==="admin") : ?>
<h3>DELETED USER FROM DATABASE</h3>

<?php
// If the query is succesfully performed ($count not false)
if($count !== false) {
	// This redirects the user back to the users page after they update 
        header("Location: users.php"); 
         
        // Calling die or exit after performing a redirect using the header function 
        // is critical.  The rest of your PHP script will continue to execute and 
        // will be sent to the user if you do not die or exit. 
        die("Redirecting to users.php");
}
?>
<?php endif; ?>

<?php
// The content below will load for users logged in with a basic user level
if ($_SESSION['user']['level']==="basic") : ?>
<div class="panel panel-default">
<div class="panel-body">
<b>SORRY. THE PAGE YOU WERE LOOKING FOR WAS NOT FOUND ON THIS SERVER.</b><p>
<a href="index.php">PLEASE GO BACK TO THE HOME PAGE HERE.</a>
</div><!-- /panel-body -->
</div><!-- /panel panel-default -->
<?php endif; ?>
</div> <!-- /container -->
</div> <!-- /jumbotron -->

<?php
// load in html page header
require("footer.php");
?>