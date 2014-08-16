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
	
	// preload the form with the dream information from the db
	// if the user submits new information then UPDATE the db
	$action = (!empty($_POST['submit']) && ($_POST['submit'] === 'Submit Edits')) ? 'save_dream' : 'show_form';
	
	// assign dream id passed from previous page link to a variable
	$id = $_GET['id'];
	
	switch($action){
	case 'save_dream':
	
	// form input tagging
		$id = $_POST['id'];
		$title = $_POST['title'] = filter_var(htmlentities($_POST['title'], ENT_QUOTES, 'UTF-8'), FILTER_SANITIZE_STRING);
		$description = $_POST['description'] = filter_var(htmlentities($_POST['description'], ENT_QUOTES, 'UTF-8'), FILTER_SANITIZE_STRING);
		
		$query = "UPDATE dreams SET title=?,description=? WHERE id=?";
								
		$stmt = $db->prepare($query);
		
		$stmt->bindValue(1,$title,PDO::PARAM_STR);
		$stmt->bindValue(2,$description,PDO::PARAM_STR);
		$stmt->bindValue(3,$id,PDO::PARAM_INT);
		
		$stmt->execute();
		
		// This redirects the user back to the dreamboard page after they update 
        header("Location: dreamboard.php"); 
         
        // Calling die or exit after performing a redirect using the header function 
        // is critical.  The rest of your PHP script will continue to execute and 
        // will be sent to the user if you do not die or exit. 
        die("Redirecting to dreamboard.php");
		
	break;
	case 'show_form':
	default:
	
	if($_SESSION['user']['level']==="admin")
    {
	// populate form fields from db using GET id sent from previous page link
		try{
		$query = "SELECT * FROM dreams WHERE id = $id"; 
     
        // run the query against database table 
		$stmt = $db->query($query);
		
		// Finally, we can run the query 
    	$stmt->execute();
		}
		catch(PDOException $ex) 
    { 
        // Note: On a production website, you should not output $ex->getMessage(). 
        // It may provide an attacker with helpful information about your code. 
		// The die statement below would provide the SQL error if any to the page 
        // die("Failed to run query: " . $ex->getMessage());
		die("Failed to run query: " . $ex->getMessage()); 
    }
		// Save results into an array using fetch
		$dream = $stmt->fetch();
		
	}
	}
     
?> 
<?php
// load in html page header
require("header.php");
?>
<!-- begin html body layout -->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?>'s Dreams</a>
        </div>
      </div><!--/.container -->
    </div>
    <!-- Main jumbotron -->
<div class="jumbotron">
<div class="container">
<?php if ($_SESSION['user']['level']==="admin") : ?>
<!-- begin new row of columns -->
      <div class="row">
      <!-- columns in a row must add to a max width of 12 -->
        <div class="col-md-12">
        <div class="panel panel-default">
  			<div class="panel-body">
            <h3>* NOW EDITING DREAM INFORMATION *</h3>
            <!-- display php:pdo MySQL query results -->
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
	<input type="text" name="date_add" value="<?php echo htmlentities($dream['date_add'], ENT_QUOTES, 'UTF-8'); ?>" readonly /><p></p>
    Title:<br />
    <input type="text" name="title" value="<?php echo htmlentities($dream['title'], ENT_QUOTES, 'UTF-8'); ?>" /><p></p>
    Description:<br />
    <input type="text" name="description" value="<?php echo htmlentities($dream['description'], ENT_QUOTES, 'UTF-8'); ?>" />

    <br /><br />

    <input name="id" type="hidden" value="<?php echo htmlentities($id, ENT_QUOTES, 'UTF-8'); ?>"  /> 
    <button type="submit" name="submit" value="Submit Edits" class="btn btn-default" >Update</button>

</form>
</div><!-- /panel-body -->
</div><!-- /panel panel-default -->
</div><!-- /col-md-12 -->
</div> <!-- /row -->
<?php endif; ?>

<?php if ($_SESSION['user']['level']==="basic") : ?>
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
// load in html page footer
require("footer.php");
?>