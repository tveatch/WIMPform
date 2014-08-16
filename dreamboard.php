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
	
	// SQL query wrapped in a variable
	// Select everything from the dreams table
	$query = "SELECT * FROM dreams";
	
	// php try/catch statement
	// ie: try this operation
    try 
    { 
        // These statements run the query against your database table.
		// prepare the query for the database connection 
        $stmt = $db->prepare($query);
		// execute the query
        $stmt->execute(); 
    } 
	// catch all errors and store in $ex variable
    catch(PDOException $ex) 
    { 
        // Note: On a production website, you should not output $ex->getMessage(). 
        // It may provide an attacker with helpful information about your code. 
		// The die statement below would provide the SQL error if any to the page 
        // die("Failed to run query: " . $ex->getMessage());
		die("Failed to run query: " . $ex->getMessage()); 
    } 
         
    // If connection is made and all is good with your query
	// retrieve all records found and assign the results to a variable.
	$dreams = $stmt->fetchAll();
     
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
            <h3>Current list of dreams</h3>
            <table class="table">
            <!-- display php:pdo MySQL query results -->
        	<!-- a simple repeater if multiple records are found -->
        	<?php foreach($dreams as $dream): ?>
    			<tr>
        			<td><h2><?php echo htmlentities($dream['title'], ENT_QUOTES, 'UTF-8'); ?></h2></td>
                </tr>
                    
                    <?php
						// this code will only display if the current session user
						// has a value of super in their 'super' column
						if ($_SESSION['user']['super']==="super") : ?>
                        <tr>
                        	<td>
                            	<div class="btn-group btn-group-justified">
                                  <div class="btn-group">
                                    <button type="button" class="btn btn-default"><a href="editDream.php?id=<?php echo $dream['id']; ?>">EDIT</a></button>
                                  </div>
                                  <div class="btn-group">
                                    <button type="button" class="btn btn-default"><a href="printDream.php?id=<?php echo $dream['id'] ?>">PRINT</a></button>
                                  </div>
                                  <div class="btn-group">
                                    <button type="button" class="btn btn-default"><a href="#" onclick="confirmDeleted('<?php echo $dream['id']; ?>')">DELETE</a></button>
                                  </div>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
        		</tr>
        	<?php endforeach; ?>
    </table>
    <p></p>
    <h5>Super User Links</h5>
    <table class="table">
    <tr>
    <td>
		<div class="btn-group btn-group-justified">
	<?php
    // this code will only display if the current session user
    // has a value of super in their 'super' column
    if ($_SESSION['user']['super']==="super") : ?>
        	<div class="btn-group">
                <button type="button" class="btn btn-default"><a href="users.php">USERS</a></a></button>
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
</div><!-- /col-md-12 -->
</div> <!-- /row -->

<?php endif; ?>
<?php if ($_SESSION['user']['level']==="basic") : ?>
	<div>
		<b>SORRY. THE PAGE YOU WERE LOOKING FOR WAS NOT FOUND ON THIS SERVER.</b><p>
		<a href="login.php">PLEASE GO BACK TO THE LOGIN HERE.</a>
	</div><!-- end div -->
<?php endif; ?>
</div> <!-- /container -->
</div> <!-- /jumbotron -->

<?php
// load in html page footer
require("footer.php");
?>