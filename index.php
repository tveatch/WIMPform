<?php 

    // First we execute our common code to connect to the database and start the session 
    require("_inc/common.php"); 
     
    // Everything below this point in the file is secured by the login system
	
// LOG IN A REGISTERED USER

	// This variable will be used to re-display the user's username to them in the 
    // login form if they fail to enter the correct password.  It is initialized here 
    // to an empty value, which will be shown if the user has not submitted the form. 
    $submitted_username = ''; 
     
    // This if statement checks to determine whether the login form has been submitted 
    // If it has, then the login code is run, otherwise the form is displayed 
    if(!empty($_POST)) 
    { 
        // This query retreives the user's information from the database using 
        // their username. 
        $query2 = " 
            SELECT 
                id, 
                username, 
                password, 
                salt, 
                email,
				level,
				super 
            FROM users 
            WHERE 
                username = :username 
        "; 
         
        // The parameter values 
        $query_params = array( 
            ':username' => $_POST['username'] 
        ); 
         
        try 
        { 
            // Execute the query against the database 
            $stmt2 = $db->prepare($query2); 
            $result = $stmt2->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
            // die("Failed to run query: " . $ex->getMessage());
			die(); 
        } 
         
        // This variable tells us whether the user has successfully logged in or not. 
        // We initialize it to false, assuming they have not. 
        // If we determine that they have entered the right details, then we switch it to true. 
        $login_ok = false; 
         
        // Retrieve the user data from the database.  If $row is false, then the username 
        // they entered is not registered. 
        $row = $stmt2->fetch(); 
        if($row) 
        { 
            // Using the password submitted by the user and the salt stored in the database, 
            // we now check to see whether the passwords match by hashing the submitted password 
            // and comparing it to the hashed version already stored in the database. 
            $check_password = hash('sha256', $_POST['password'] . $row['salt']); 
            for($round = 0; $round < 65536; $round++) 
            { 
                $check_password = hash('sha256', $check_password . $row['salt']); 
            } 
             
            if($check_password === $row['password']) 
            { 
                // If they do, then we flip this to true 
                $login_ok = true; 
            } 
        } 
         
        // If the user logged in successfully, then we send them to the private members-only page 
        // Otherwise, we display a login failed message and show the login form again 
        if($login_ok) 
        { 
            // Here I am preparing to store the $row array into the $_SESSION by 
            // removing the salt and password values from it.  Although $_SESSION is 
            // stored on the server-side, there is no reason to store sensitive values 
            // in it unless you have to.  Thus, it is best practice to remove these 
            // sensitive values first. 
            unset($row['salt']); 
            unset($row['password']); 
             
            // This stores the user's data into the session at the index 'user'. 
            // We will check this index on the private members-only page to determine whether 
            // or not the user is logged in.  We can also use it to retrieve 
            // the user's details. 
            $_SESSION['user'] = $row;
			$_SESSION['timeout'] = time();
			
            // Redirect the user to the private members-only page. 
            header("Location: dreamboard.php"); 
            die("Redirecting to: dreamboard.php"); 
        } 
        else 
        { 
            // Tell the user they failed 
            print("Login Failed."); 
             
            // Show them their username again so all they have to do is enter a new 
            // password.  The use of htmlentities prevents XSS attacks.  You should 
            // always use htmlentities on user submitted values before displaying them 
            // to any users (including the user that submitted them).  For more information: 
            // http://en.wikipedia.org/wiki/XSS_attack 
            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); 
        } 
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
          <a class="navbar-brand" href="#">myDreams App</a>
        </div>
        <div class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" >
            <div class="form-group">
              <input type="text" name="username" placeholder="Username" value="" class="form-control" required >
            </div>
            <div class="form-group">
              <input type="text" name="password" placeholder="Password" value="" class="form-control" required >
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div><!--/.container -->
    </div>
    
    <!-- Main jumbotron -->
    <div class="jumbotron">
      <div class="container">
      <h1>Check Out My Recent Dreams</h1><br />
      <!-- begin new row of columns -->
      <div class="row">
      <!-- columns in a row must add to a max width of 12 -->
        <div class="col-md-12">
        <div class="panel panel-default">
  			<div class="panel-body">
            <table class="table">
            <!-- display php:pdo MySQL query results -->
        	<!-- a simple repeater if multiple records are found -->
        	<?php foreach($dreams as $dream): ?>
    			<tr>
        			<td><h2><?php echo htmlentities($dream['title'], ENT_QUOTES, 'UTF-8'); ?></h2></td>
        		</tr>
        		<tr>
        			<td>Date Added: <?php echo htmlentities($dream['date_add'], ENT_QUOTES, 'UTF-8'); ?><br />
                    <span class="description"><i>Description:</i></span><br /><?php echo htmlentities($dream['description'], ENT_QUOTES, 'UTF-8'); ?></td>
        		</tr>
        		<p></p>
        	<?php endforeach; ?>
            </table>
  			</div><!-- /panel-body -->
		</div><!-- /panel panel-default -->
        </div><!-- /col-md-12 -->
      </div><!-- /row -->
      </div><!-- /container -->
    </div><!-- /jumbotron -->

    <div class="container">
    <!-- begin new row of columns -->
    <div class="row">
      <!-- columns in a row must add to a max width of 12 -->
        <div class="col-md-12">
            <a class="btn btn-primary btn-lg" role="button" href="newUser.php">SIGN UP</a>
        </div><!-- /col-md-12 -->
    </div><!-- /row -->  
    </div><!-- /container -->
    
<?php
// load in html page footer
require("footer.php");
?>