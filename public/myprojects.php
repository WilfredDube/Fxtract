<?php
ini_set("display_errors", "on");
require_once('../includes/initialize.php');

// ini_set('display_errors','on');
//ini_set('file_uploads','on');
//ini_set('upload_temp_dir','/home/digitalincentives/www/loginregister/uploads/');

//if not logged in redirect to login page
if (!$user->is_logged_in()) {
    header('Location: login.php');
}

$_POST['cool'] = 1;

if (isset($_GET['delete_id'])) {
    $fid = $_GET['delete_id'];
    try {//echo "dsds"; update files set units='nn' where file_id=2;
$query = "DELETE FROM `files` where file_id=$fid";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $query = "DELETE FROM `bends`";
        $stmt = $db->prepare($query);
        $stmt->execute();
            /**/
$query = "ALTER TABLE `files` DROP file_id";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $query = "ALTER TABLE `files` AUTO_INCREMENT = 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
            /**/
$query = "ALTER TABLE `files` ADD `file_id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
        $stmt = $db->prepare($query);
        $stmt->execute();
    } catch (PDOException $e) {
        $error[] = $e->getMessage();
    }
    header("Location: uploadhistory.php");
}

//define page title
$title = 'My Projects';

//include header template
require('templates/header.php');

?>

<div class="container">

<div id="content-wrapper">

		<div class="page-header">
			<h2>My Projects</h2>
		</div> <!-- / .page-header -->

		<div class="row">
			<div class="col-sm-12">
<!--<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
 6. $BORDERED_TABLES ===========================================================================

				Bordered tables
-->
				<div class="panel">
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>#</th>
									<th>File Name</th>
									<th>Description</th>
									<th>Material</th>
									<th>Upload Date</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
<!-- /6. $BORDERED_TABLES -->

	</div> <!-- / #content-wrapper -->
	<div id="main-menu-bg"></div>
</div> <!-- / #main-wrapper -->
</div>
</div>
<?php
//include header template
// get the contents, and echo it out.
require('templates/footer.php');
?>
