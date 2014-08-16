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
	
	// preload the form with the user information from the db
	// if the user submits new information then UPDATE the db
	$action = (!empty($_POST['submit']) && ($_POST['submit'] === 'Submit Edits')) ? 'save_user' : 'show_form';
	
	// assign user id passed from previous page link to a variable
	$id = $_GET['id'];
	
	switch($action){
	case 'save_user':
	
	// form input tagging
		$id = $_POST['id'];
		$level = $_POST['level'];
		
		$query = "UPDATE users SET level=? WHERE id=?";
								
		$stmt = $db->prepare($query);
		
		$stmt->bindValue(1,$level,PDO::PARAM_STR);
		$stmt->bindValue(2,$id,PDO::PARAM_INT);
		
		$stmt->execute();
		
		// This redirects the user back to the users page after they update 
        header("Location: users.php"); 
         
        // Calling die or exit after performing a redirect using the header function 
        // is critical.  The rest of your PHP script will continue to execute and 
        // will be sent to the user if you do not die or exit. 
        die("Redirecting to users.php");
		
	break;
	case 'show_form':
	default:
	
	if($_SESSION['user']['level']==="admin")
    {
	// populate form fields from db using GET id sent from previous page link
		try{
		$query = "SELECT * FROM users WHERE id = $id"; 
     
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
		$user = $stmt->fetch();
		
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
            <h3>* NOW EDITING USER ACCOUNT INFORMATION *</h3>
            <!-- display php:pdo MySQL query results -->

	Date Registered:<br />
    <?php echo htmlentities($user['date_add'], ENT_QUOTES, 'UTF-8'); ?><p></p><p></p>
    Username:<br />
    <?php echo htmlentities($user['username'], ENT_QUOTES, 'UTF-8'); ?><p></p>
    E-Mail:<br />
	<?php echo htmlentities($user['email'], ENT_QUOTES, 'UTF-8'); ?><p></p>
    Level:<br />
	<?php echo htmlentities($user['level'], ENT_QUOTES, 'UTF-8'); ?><p></p>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post"> 
    <select name="level">
    	<option value="admin" <?php if($user['level']=='admin'){ echo "selected=\"true\""; }?>>ADMINISTRATOR</option>
    	<option value="basic" <?php if($user['level']=='basic'){ echo "selected=\"true\""; }?>>BASIC USER</option>
	</select>

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