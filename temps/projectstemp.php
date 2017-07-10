<?php
  $rows = getProjects($_SESSION['user']['username']);
  if ($rows)
  foreach ($rows as $row) {
?>
<tr>
  <td><?php echo "$row['projectname']"; ?></td>
  <td><?php echo "$row['projectdescription']"; ?></td>
  <td><?php echo "$row['projectmaterial']"; ?></td>
  <td><?php echo "$row['proojectcreationdate']"; ?></td>
  <td>
    <div class="btn-group" role="group" aria-label="...">
      <button type="button" class="btn btn-default" onclick="window.location.href='myprojectdetails.php'"><span class="fa fa-eye"></span></button>
      <button type="button" class="btn btn-default" onclick="window.location.href='#'"><span class="fa fa-edit"></span></button>
      <button type="button" class="btn btn-default"><span class="fa fa-trash"></span></button>
    </div>
  </td>
</tr>
<?php } ?>

// function getProjects ($username) {
//   // global $database;
//   $query = "SELECT * FROM projects WHERE projectownerid=?";
//
//   $userID[] = User::getUserID($username);
//
//   return isset($database->getAllRows($query, $userID)) ? $database->getAllRows($query, $userID) : FALSE;
// }
