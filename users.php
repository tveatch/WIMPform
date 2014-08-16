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
     
    // Everything below this point in the file is secured by the login system 
     
	
	// We can retrieve a list of users from the database using a SELECT query. 
    // In this case we do not have a WHERE clause because we want to select all 
    // of the rows from the database table. 
    $query = "SELECT users.id, users.username, users.email, users.level FROM users"; 
     
    try 
    { 
        // These two statements run the query against your database table. 
        $stmt = $db->prepare($query); 
        $stmt->execute(); 
    } 
    catch(PDOException $ex) 
    { 
        // Note: On a production website, you should not output $ex->getMessage(). 
        // It may provide an attacker with helpful information about your code.  
        // die("Failed to run query: " . $ex->getMessage());
		die();
    } 
         
    // Finally, we can retrieve all of the found rows into an array using fetchAll 
    $users = $stmt->fetchAll(); 
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
<div class="jumbotron">
<div class="container">
<?php if ($_SESSION['user']['level']==="admin") : ?>
<div class="panel panel-default">
<div class="panel-body">
<h2>REGISTERED USERS</h2>
<table class="table">
	<?php foreach($users as $user): ?> 
        <tr>
        	<td>username: <?php echo htmlentities($user['username'], ENT_QUOTES, 'UTF-8'); ?><br />
        		email: <?php echo htmlentities($user['email'], ENT_QUOTES, 'UTF-8'); ?><br />
        		level: <?php echo htmlentities($user['level'], ENT_QUOTES, 'UTF-8'); ?></td><tr>
        <?php
            // this code will only display if the current session user
			// has a value of super in their 'super' column
			if ($_SESSION['user']['super']==="super") : ?>
            <tr>
            <td>
            <div class="btn-group btn-group-justified">
            	<div class="btn-group">
            		<button type="button" class="btn btn-default"><a href="editUser.php?id=<?php echo $user['id']; ?>">EDIT USER INFO</a></button>
                    </div>
                <div class="btn-group">
            		<button type="button" class="btn btn-default"><a href="#" onclick="confirmDeleteu('<?php echo $user['id']; ?>')">DELETE USER</a></button>
                    </div>
            </td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
</table>
<table class="table">
		<tr>
        	<td>
            <?php
			// this code will only display if the current session user
			// has a value of super in their 'super' column
			if ($_SESSION['user']['super']==="super") : ?>
                <div class="btn-group btn-group-justified">
                      <div class="btn-group">
                        <button type="button" class="btn btn-default"><a href="dreamboard.php">DREAMS</a></button>
                      </div>
            <?php endif; ?>
                            	
                      <div class="btn-group">
                        <button type="button" class="btn btn-default"><a href="logout.php">LOG OUT</a></button>
                      </div>
                    </div>
                </td>
            </tr>
</table>
</div><!-- /panel-body -->
</div><!-- /panel panel-default -->
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
// load in html page header
require("footer.php");
?>