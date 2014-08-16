<?php 

    // First we execute our common code to connection to the database and start the session 
    require("_inc/common.php");
	
	// Assign current date to variable for date the dream is added to db
	$today = date('m.d.Y');
     
	// if the user submits new form information then INSERT into the db
	$action = (!empty($_POST['submit']) && ($_POST['submit'] === 'log dream')) ? 'save_dream' : 'show_form';
	
	switch($action){
	case 'save_dream':
	
	// form input tagging
	
		$f1 = $_POST['title'] = filter_var(htmlentities($_POST['title'], ENT_QUOTES, 'UTF-8'), FILTER_SANITIZE_STRING);
		$f2 = $_POST['description'] = filter_var(htmlentities($_POST['description'], ENT_QUOTES, 'UTF-8'), FILTER_SANITIZE_STRING);
		$f3 = $_POST['date_add'];
		
		$query = "INSERT INTO dreams (title,description,date_add) VALUES (:f1,:f2,:f3)";
		
		try 
        { 
            // Execute the query against the database 
            $stmt = $db->prepare($query);
		
			$stmt->bindValue(':f1',$f1,PDO::PARAM_STR);
			$stmt->bindValue(':f2',$f2,PDO::PARAM_STR);
			$stmt->bindValue(':f3',$f3,PDO::PARAM_STR);
			
			$stmt->execute();
			
			} 
        catch(PDOException $ex) 
        { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
             die("Failed to run query: " . $ex->getMessage());
			//die();
        }
			
		// This redirects the user back to the index page after they submit 
        header("Location: index.php"); 
         
        // Calling die or exit after performing a redirect using the header function 
        // is critical.  The rest of your PHP script will continue to execute and 
        // will be sent to the user if you do not die or exit. 
        die("Redirecting to index.php");
		
	break;
	case 'show_form':
	default:
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
<div class="jumbotron">
<div class="container">
<?php if ($_SESSION['user']['level']==="admin") : ?>
<div class="panel panel-default">
<div class="panel-body">
<h3>ENTER A NEW DREAM</h3>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post"> 
    Title:<br /> 
    <input type="text" name="title" value="" required /> 
    <br /><br /> 
    Description:<br /> 
    <input type="text" name="description" value="" required /> 
    <br /><br />
    <input type="hidden" name="date_add" value="<?php echo htmlentities($today, ENT_QUOTES, 'UTF-8'); ?>"  />
    <button type="submit" name="submit" value="log dream" class="btn btn-default" >Log Dream</button> 
</form>

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