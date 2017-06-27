<?php
// If it's going to need the database, then it's
// probably smart to require it before we start.
//require_once(LIB_PATH.DS.'database.php');

class IgesFile {

  protected static $table_name='files';
  protected static $db_fields=array('fileid', 'fileuserid', 'filename', 'filetype', 'filesize', 'filecaption',
  'filemodelunits', 'filemodelmaterial', 'fileuploaddate');
  public $fileID;
  public $fileUserID;
  public $fileName;
  public $fileType;
  public $fileSize;
  public $fileCaption;
  public $fileUploadDate;
  public $fileModifiedDate;
  public $fileModelUnits;
  public $fileModelMaterialsID;

  private $temp_path;
  protected $upload_dir="uploads";
  public $errors=array();

  protected $upload_errors = array(
    // http://www.php.net/manual/en/features.file-upload.errors.php
    UPLOAD_ERR_OK 				=> "No errors.",
    UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
    UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
    UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
    UPLOAD_ERR_NO_FILE 		=> "No file.",
    UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
    UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
    UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
  );

  // Pass in $_FILE(['uploaded_file']) as an argument
  public function attach_file($file) {
    // Perform error checking on the form parameters
    if(!$file || empty($file) || !is_array($file)) {
      // error: nothing uploaded or wrong argument usage
      $this->errors[] = "No file was uploaded.";
      return false;
    } elseif($file['error'] != 0) {
      // error: report what PHP says went wrong
      $this->errors[] = $this->upload_errors[$file['error']];
      return false;
    } else {
      if (isIGESfile($file['type'])) {
        // Set object attributes to the form parameters.
        $this->temp_path  = $file['tmp_name'];
        $this->fileName   = basename($file['name']);
        $this->fileType   = $file['type'];
        $this->fileSize   = $file['size'];
        $this->fileUserID = $_SESSION['username'];
        $this->fileUploadDate = date("Y-m-d H:i:s");
        // Don't worry about saving anything to the database yet.
        return true;
      }
    }
  }

  public function isIGESfile($file_type) {
    $perfect = false;
    if ($file_type == "model/iges") {
      $perfect = true;
    }
    return $perfect;
  }

  public function save() {
    // A new record won't have an id yet.
    if(isset($this->fileID)) {
      // Really just to update the caption
      $this->updateModelUnits();
    } else {
      // Make sure there are no errors

      // Can't save if there are pre-existing errors
      if(!empty($this->errors)) { return false; }

      // Make sure the caption is not too long for the DB
      if(strlen($this->fileCaption) > 255) {
        $this->errors[] = "The caption can only be 255 characters long.";
        return false;
      }

      // Can't save without filename and temp location
      if(empty($this->fileName) || empty($this->temp_path)) {
        $this->errors[] = "The file location was not available.";
        return false;
      }

      // Determine the target_path
      $target_path = SITE_ROOT .DS. $this->upload_dir .DS. $this->fileName;

      // Make sure a file doesn't already exist in the target location
      if(file_exists($target_path)) {
        $this->errors[] = "The file {$this->fileName} already exists.";
        return false;
      }

      // Attempt to move the file
      if(move_uploaded_file($this->temp_path, $target_path)) {
        // Success
        // Save a corresponding entry to the database
        if($this->create()) {
          // We are done with temp_path, the file isn't there anymore

          unset($this->temp_path);
          return true;
        }
      } else {
        // File was not moved.
        $this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
        return false;
      }
    }
  }

  public function destroy() {
    // First remove the database entry
    if($this->delete()) {
      // then remove the file
      // Note that even though the database entry is gone, this object
      // is still around (which lets us use $this->file_path()).
      $target_path = SITE_ROOT.DS.$this->file_path();
      return unlink($target_path) ? true : false;
    } else {
      // database delete failed
      return false;
    }
  }

  public function file_path() {
    return $this->upload_dir.DS.$this->fileName;
  }

  public function size_as_text() {
    if($this->size < 1024) {
      return "{$this->size} bytes";
    } elseif($this->size < 1048576) {
      $size_kb = round($this->size/1024);
      return "{$size_kb} KB";
    } else {
      $size_mb = round($this->size/1048576, 1);
      return "{$size_mb} MB";
    }
  }

  private function getUnits($gsection) {
    $unit = trim ( $gsection [13] );

    switch ($unit) {
      case 1 :
      $dim = "inches";
      break;
      case 2 :
      $dim = "mm";
      break;
      case 3 :
      $dim = "special";
      break;
      case 4 :
      $dim = "ft";
      break;
      case 5 :
      $dim = "miles";
      break;
      case 6 :
      $dim = "metres";
      break;
      case 7 :
      $dim = "Km";
      break;
      case 8 :
      $dim = "mils";
      break;
      case 9 :
      $dim = "microns";
      break;
      case 10 :
      $dim = "cm";
      break;
      case 11 :
      $dim = "minchs";
      break;
      default :
      break;
    }
    return $dim;
  }

  public function getModelUnits($gsection = []) {
    return getUnits($gsection);
  }
  // Common Database Methods
  public static function find_all() {
    global $database;
    return $database->getAllRows("SELECT * FROM ".self::$table_name, []);
  }

  public static function find_file_by_id($id=0) {
    global $database;
    $result_array = self::find_file_by_sql("SELECT * FROM ".self::$table_name." WHERE file_id= ? LIMIT 1", [$id]);
    return !empty($result_array) ? ($result_array) : false;
  }

  public static function find_file_by_sql($sql="", $params = []) {
    global $database;
    $result_set = $database->findRow($sql, $params);
    return $result_set;
  }

  public static function count_all() {
    global $database;
    $sql = "SELECT * FROM ".self::$table_name;
    $res = $database->count($sql);

    return ($res);
  }

  public function create() {
    global $database;
    // Don't forget your SQL syntax and good habits:
    // - INSERT INTO table (key, key) VALUES ('value', 'value')
    // - single-quotes around all values
    // - escape all values to prevent SQL injection
    $sql = "INSERT INTO ".self::$table_name." (";
    $sql .= "fileuserid, filename, filetype, filesize, filecaption,
    filemodelmaterial, fileuploaddate) VALUES (?,?,?,?,?,?,?)";

    $params = array($this->fileUserID, $this->fileName, $this->fileType, $this->fileSize, $this->fileCaption,
    $this->fileModelMaterialsID, $this->fileUploadDate);

    if($database->insertRow($sql, $params)) {
      $query = "SELECT fileid from files where filename = ?";
      $this->fileID = $database->insert_id($query, "fileid", [$this->fileName]);

      return true;
    } else {
      return false;
    }
  }

  public function updateModelUnits() {
    global $database;
    // Don't forget your SQL syntax and good habits:
    // - UPDATE table SET key='value', key='value' WHERE condition
    // - single-quotes around all values
    // - escape all values to prevent SQL injection

    $sql = "UPDATE ".self::$table_name." SET filemodelunits = ?  WHERE fileid = ?";
    $affected_rows = $database->updateRow($sql, [$this->fileModelUnits, $this->fileID]);
    return ($affected_rows == 1) ? true : false;
  }

  public function delete() {
    global $database;
    // Don't forget your SQL syntax and good habits:
    // - DELETE FROM table WHERE condition LIMIT 1
    // - escape all values to prevent SQL injection
    // - use LIMIT 1
    $sql = "DELETE FROM ".self::$table_name;
    $sql .= " WHERE fileid= ?";
    $sql .= " LIMIT 1";
    $affected_rows = $database->deleteRow($sql, [$this->fileID]);
    return ($affected_rows == 1) ? true : false;

    // NB: After deleting, the instance of User still
    // exists, even though the database entry does not.
    // This can be useful, as in:
    //   echo $user->first_name . " was deleted";
    // but, for example, we can't call $user->update()
    // after calling $user->delete().
  }

}

?>
