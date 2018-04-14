<?php
		//$_POST['username'] = $_SESSION['username'];
		$username = $_SESSION['user']['username'];
		//echo $username;
$query = "SELECT * FROM files  where file_username = '$username'";
foreach ($database->getAllRows($query) as $row) {
?><tr>
			<td><?php echo $row['file_id']; ?></td>
			<td><?php echo $row['filename']; ?></td>
			<td><?php echo $row['file_caption']; ?></td>
<td><?php echo $upload->getMaterialName($row['file_material']); ?></td>
			<td><?php echo $row['file_date']; ?></td>
<td><a class="btn btn-sm btn-primary" role="button" href="a_index.php?id=<?php echo $row['file_id']; ?>">Process</a>
<a class="btn btn-sm btn-primary" role="button" href="?delete_id=<?php echo $row['file_id']; ?>"
onclick="return confirm('Are you sure you want to delete this file from the database?'); ">Delete</a>
</td>
		</tr>
<?php
}
?>
