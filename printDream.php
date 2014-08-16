<?php
// First we execute our common code to connection to the database and start the session 
require("_inc/common.php");
	
include ('_inc/MPDF56/mpdf.php');
	$id = $_GET['id'];
	
	
// populate form fields from db using GET id sent from previous page link
		$query = "SELECT * FROM dreams WHERE id = $id"; 
     
        // run the query against database table 
		$stmt = $db->query($query);
		
		// Finally, we can run the query 
    	$stmt->execute();
		
		// Save results into an array using fetch
		// This populates the page with all of the "vendors" table information
		$dream = $stmt->fetch();
		// Just pulled everything from the vendors table where the
		// row "id" matched the $id from the page variable

ob_start();
?>

<!-- profile information begin -->

<form> 

    <h3>Dream</h3>
    <table class="table">
    <tr>
    <td>
    	<b><?php echo htmlentities($dream['title'], ENT_QUOTES, 'UTF-8'); ?></b>
    </td>
    <td>
    	<span class="question">Description:</span> <?php echo htmlentities($dream['description'], ENT_QUOTES, 'UTF-8'); ?>
    </td>
    </tr>
    </table>
</form>

<?php
$gotcha = ob_get_clean();

$mpdf=new mPDF('','Letter','0','','30','10');

// Use different Odd/Even headers and footers and mirror margins
$mpdf->mirrorMargins = 0;
$mpdf->defaultheaderfontsize = 8;
$mpdf->defaultfooterfontsize = 8;
$mpdf->defaultheaderfontstyle = 'sans-serif';
$mpdf->defaultfooterfontstyle = 'sans-serif';
$mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */
$mpdf->defaultfooterline = 1; 	/* 1 to include line below header/above footer */

$mpdf->SetHeader('MyDreams|Printed {DATE m.d.Y}|'.$dream['title'].'');
$mpdf->SetFooter('MyDreams|{PAGENO}|Created '.$dream['date_add'].'');

// LOAD a stylesheet
$stylesheet = file_get_contents('styles/pdf.css');
$mpdf->WriteHTML($stylesheet,1);

// The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($gotcha,2);
$mpdf->Output();
exit;
?>