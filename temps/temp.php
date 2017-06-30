<?php

class Post
{
	public $id;
	public $title;
	public $content;
	public $created;

	public static function getBySql($sql)
	{
		try
		{
			// Open database connection
			$database = new Database();

			// Set the error reporting attribute
			$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Execute database query
			$statement = $database->query($sql);

			// Fetch results from cursor
			$statement->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
			$result = $statement->fetchAll();

			// Close database resources
			$database = null;

			// Return results
			return $result;
		}
		catch (PDOException $exception)
		{
			die($exception->getMessage());
		}
	}

	public static function getAll()
	{
		$sql = 'select * from post';
		return self::getBySql($sql);
	}

	public static function getById($id)
	{
		try
		{
			// Open database connection
			$database = new Database();

			// Set the error reporting attribute
			$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Build database statement
			$sql = "select * from post where id = :id limit 1";
			$statement = $database->prepare($sql);
			$statement->bindParam(':id', $id, PDO::PARAM_INT);

			// Execute database statement
			$statement->execute();

			// Fetch results from cursor
			$statement->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
			$result = $statement->fetch();

			// Close database resources
			$database = null;

			// Return results
			return $result;
		}
		catch (PDOException $exception)
		{
			die($exception->getMessage());
		}
	}

	private function insert()
	{
		try
		{
			// Open database connection
			$database = new Database();

			// Set the error reporting attribute
			$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Build database statement
			$sql = "insert into post (title, content) values (:title, :content)";
			$statement = $database->prepare($sql);
			$statement->bindParam(':title', $this->title, PDO::PARAM_STR);
			$statement->bindParam(':content', $this->content, PDO::PARAM_STR);

			// Execute database statement
			$statement->execute();

			// Get affected rows
			$count = $statement->rowCount();

			// Close database resources
			$database = null;

			// Return affected rows
			return $count;
		}
		catch (PDOException $exception)
		{
			die($exception->getMessage());
		}
	}

	private function update()
	{
		try
		{
			// Open database connection
			$database = new Database();

			// Set the error reporting attribute
			$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Build database query
			$sql = "update post set title = :title, content = :content where id = :id";

			// Build database statement
			$statement = $database->prepare($sql);
			$statement->bindParam(':title', $this->title, PDO::PARAM_STR);
			$statement->bindParam(':content', $this->content, PDO::PARAM_STR);
			$statement->bindParam(':id', $this->id, PDO::PARAM_INT);

			// Execute database statement
			$statement->execute();

			// Get affected rows
			$count = $statement->rowCount();

			// Close database resources
			$database = null;

			// Return affected rows
			return $count;
		}
		catch (PDOException $exception)
		{
			die($exception->getMessage());
		}
	}

	public function delete()
	{
		try
		{
			// Open database connection
			$database = new Database();

			// Set the error reporting attribute
			$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Build database statement
			$sql = "delete from post where id = :id limit 1";
			$statement = $database->prepare($sql);
			$statement->bindParam(':id', $this->id, PDO::PARAM_INT);

			// Execute database statement
			$statement->execute();

			// Get affected rows
			$count = $statement->rowCount();

			// Close database resources
			$database = null;

			// Return affected rows
			return $count;
		}
		catch (PDOException $exception)
		{
			die($exception->getMessage());
		}
	}

	public function save()
	{
		// Check object for id
		if (isset($this->id)) {

			// Return update when id exists
			return $this->update();

		} else {

			// Return insert when id does not exists
			return $this->insert();
		}
	}
}
