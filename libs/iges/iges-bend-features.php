<?php

class BendFeatures
{
  protected static $table_name='features';
  protected static $db_fields = array(
    // "featureid",
    "projectid",
    "fileid",
		"bend_id",
		"face1_id",
		"face2_id",
		"angle",
		"bend_loop_id",
		"bend_length",
		"bend_thickness",
		"bend_radius",
		"bend_height",
		"bending_force"
 	);

  public $featureid;
  public $projectid;
  public $fileid;
  public $bend_id;
  public $face1_id;
  public $face2_id;
  public $angle;
  public $bend_loop_id;
  public $bend_length;
  public $bend_thickness;
  public $bend_radius;
  public $bend_height;
  public $bending_force;

// $record = argument is a bend feature
  public function __construct($record)
  {
    // Could check that $record exists and is an array
	  $object = new self;

	  // More dynamic, short-form approach:
	  foreach ($record as $attribute=>$value) {
	      if ($object->has_attribute($attribute)) {
	          $object->$attribute = $value;
	      }
	  }

		return $object;
  }

  private function has_attribute($attribute)
  {
    // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  return array_key_exists($attribute, $this->attributes());
  }

  protected function attributes()
  {
      // return an array of attribute names and their values
  	$attributes = array();
    foreach (self::$db_fields as $field) {
          // echo property_exists($this, $field);
    if (property_exists($this, $field)) {
        // echo $field.": ";
      // echo $this->$field." ";
      $attributes[$field] = $this->$field;
    }
      }
  // print_r($attributes);
  	return $attributes;
  }

  protected function sanitized_attributes()
  {
    global $database;
    $clean_attributes = array();
  	// sanitize the values before submitting
  	// Note: does not alter the actual value of each attribute
  	foreach ($this->attributes() as $key => $value) {
      $clean_attributes[$key] = ($value);
  	}

    return $clean_attributes;
  }

  public function save()
  {
    // A new record won't have an id yet.
  	return isset($this->featureid) ? $this->update() : $this->create();
  }

  public function create()
  {
    global $database;
  	// Don't forget your SQL syntax and good habits:
  	// - INSERT INTO table (key, key) VALUES ('value', 'value')
  	// - single-quotes around all values
  	// - escape all values to prevent SQL injection
  	$attributes = $this->sanitized_attributes();

    $sql = "INSERT INTO ".self::$table_name." (";
    $sql .= join(", ", array_keys($attributes));
    $sql .= ") VALUES (?,?,?,?,?)";

    $params = array_values($attributes);

    if ($database->insertRow($sql, $params)) {
      // $query = "SELECT projectid from projects where projectname = ?";
      // $this->projectid = $database->insert_id($query, "projectid", [$this->projectname]);
      return true;
    } else {
      	return false;
    }
  }

  public function update()
  {
    global $database;
	  // Don't forget your SQL syntax and good habits:
	  // - UPDATE table SET key='value', key='value' WHERE condition
	  // - single-quotes around all values
	  // - escape all values to prevent SQL injection
	  $attributes = $this->sanitized_attributes();
    $attribute_pairs = array();
    foreach ($attributes as $key => $value) {
      $attribute_pairs[] = "{$key}='{$value}'";
    }
    $sql = "UPDATE ".self::$table_name." SET ";
    $sql .= join(", ", $attribute_pairs);
    $sql .= " WHERE projectid=?";

    return ($database->updateRow($sql, [$this->projectid]) == 1) ? true : false;
  }

  public function destroy()
  {
      // First remove the database entry
  if ($this->delete()) {
      // then remove the file
    // Note that even though the database entry is gone, this object
    // is still around (which lets us use $this->file_path()).
    // $target_path = SITE_ROOT.DS.$this->file_path();
    return unlink($target_path) ? true : false;
  } else {
      // database delete failed
    return false;
  }
  }

// Common Database Methods
public static function find_all()
{
    global $database;
    return $database->getAllRows("SELECT * FROM ".self::$table_name, []);
}

  public static function find_project_by_id($id=0)
  {
      global $database;
      $result_array = self::find_project_by_sql("SELECT projectfileid FROM ".self::$table_name." WHERE projectid=? LIMIT 1", [$id]);
      print_r($result_array);
      return !empty($result_array) ? array_shift($result_array) : false;
  }

  public static function find_project_by_sql($sql="", $params = [])
  {
      global $database;
      $result_set = $database->getAllRows($sql, $params);
      return !empty($result_set) ? ($result_set) : false;
  }

  public static function count_all()
  {
      global $database;
      $sql = "SELECT * FROM ".self::$table_name;
      $res = $database->count($sql);

      return ($res);
  }

  public function updateProject()
  {
      global $database;
  // Don't forget your SQL syntax and good habits:
  // - UPDATE table SET key='value', key='value' WHERE condition
  // - single-quotes around all values
  // - escape all values to prevent SQL injection

    $sql = "UPDATE ".self::$table_name." SET filemodelunits = ?  WHERE fileid = ?";
      $affected_rows = $database->updateRow($sql, [$this->fileModelUnits, $this->fileID]);
      return ($affected_rows == 1) ? true : false;
  }

  public static function delete($id)
  {
      global $database;
  // Don't forget your SQL syntax and good habits:
  // - DELETE FROM table WHERE condition LIMIT 1
  // - escape all values to prevent SQL injection
  // - use LIMIT 1
    $projectsql = "DELETE FROM ".self::$table_name;
      $projectsql .= " WHERE projectid= ?";
      $projectsql .= " LIMIT 1";

      $filesql = "DELETE FROM files";
      $filesql .= " WHERE fileprojectid= ?";
      $filesql .= " LIMIT 1";

      $fileid = self::getModelFileID($id);

      $deleted_rows   = IgesFile::destroy($fileid);
      $affected_rows  = $database->deleteRow($projectsql, [$id]);

      return ($affected_rows == 1 && $deleted_rows == 1) ? true : false;

  // NB: After deleting, the instance of User still
  // exists, even though the database entry does not.
  // This can be useful, as in:
  //   echo $user->first_name . " was deleted";
  // but, for example, we can't call $user->update()
  // after calling $user->delete().
  }
}
