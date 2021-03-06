<?php
ini_set("display_errors", "on");
require_once('includes/initialize.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }

//define page title
$title = 'Members Page';

//include header template
require('templates/header.php');
?>

<div class="container">

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">

				<h2>Member only page - Welcome <?php echo $_SESSION['fullname']; ?></h2>
				<p><a href='logout.php'>Logout</a></p>
				<hr>

		</div>
	</div>


</div>

<?php
//include header template
require('templates/footer.php');
?>
