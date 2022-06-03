<?php

include_once("userinfo.php");

Const FETCH_QUERY = "SELECT * FROM %s WHERE id=\"%s\";";
Const PUSH_QUERY = "INSERT INTO %s (id, contacts) VALUES (\"%s\", \"%s\");";
Const DELETE_QUERY = "DELETE FROM %s WHERE id=\"%s\";";

Const HOST = "sql3.freemysqlhosting.net";
Const USERNAME = "sql3496710";
Const PASSWORD = "hHH2zVtfwD";
Const DATABASE_NAME = "sql3496710";
Const TABLE = "info";

function openConnection()
{
	$mysql = new mysqli(HOST, USERNAME, PASSWORD);

	if ($mysql->connect_errno)
	{
		printf("Error: %s\n", $mysql->connect_error);

		exit();
	}
	else
		printf("Connection successfuly stabilished\n");

	$mysql->select_db(DATABASE_NAME);

	return $mysql;
}

function pushUserInfo($mysql_instance, $userinfo_obj)
{
	$id = $userinfo_obj->getId();
	$contacts = $userinfo_obj->getContacts();
	
	if (contains($mysql_instance, $id))
		deleteUserInfo($mysql_instance, $id);

	$query = sprintf(PUSH_QUERY, TABLE, $id, $contacts);
	
	$result = $mysql_instance->query($query, MYSQLI_STORE_RESULT);
	
	if (!$result)
		echo "Could not insert data to Database\n";
	else
		echo "Successfully data write\n";
}

function fetchUserInfo($mysql_instance, $id)
{
	$query = sprintf(FETCH_QUERY, TABLE, $id);
	
	$result = $mysql_instance->query($query, MYSQLI_STORE_RESULT);
	
	if (!$result)
	{
		echo "Could not fetch UserInfo\n";

		return null;
	}
	else
	{
		if (list($id, $contacts) = $result->fetch_row())
		{

			$user = new UserInfo;
			$user->set($id, $contacts);

			return $user;
		}
	}
	
	return null;
}

function deleteUserInfo($mysql_instance, $id)
{
	$query = sprintf(DELETE_QUERY, TABLE, $id);
	
	$result = $mysql_instance->query($query, MYSQLI_STORE_RESULT);
	
	if (!$result)
		echo "Could not delete UserInfo\n";
	else
		echo "Successfully deleted\n";
}

function manageGET($mysql, $getValue)
{
	if ($getValue == "pull")
	{
		$user = fetchUserInfo($mysql, $_GET['id']);
		
		if ($user != null)
			printf("%s\n", $user->getContacts());
	}
	elseif ($getValue == "showids")
		printIds($mysql);
}

function managePOST($mysql, $postValue)
{
	if ($postValue == "push")
	{
		$user = new UserInfo;
		$user->set($_POST['id'], $_POST['contacts']);
		
		pushUserInfo($mysql, $user);
	}
}

function printIds($mysql_instance)
{
	$query = sprintf("SELECT * FROM %s;", TABLE);
	
	$result = $mysql_instance->query($query, MYSQLI_STORE_RESULT);
	
	if (!$result)
		echo "Could not print all IDS\n";
	else
	{
		while (list($id) = $result->fetch_row())
			printf("%s\n", $id);
	}
}

function printTable($mysql_instance)
{
	$query = sprintf("SELECT * FROM %s;", TABLE);
	
	$result = $mysql_instance->query($query, MYSQLI_STORE_RESULT);
	
	if (!$result)
		echo "Could not print table\n";
	else
	{
		while (list($id, $contacts) = $result->fetch_row())
			printf("ID:%s\nContacts:%s\n", $id, $contacts);
	}
}

function contains($mysql_instance, $id)
{
	$query = sprintf(FETCH_QUERY, TABLE, $id);
	
	$result = $mysql_instance->query($query, MYSQLI_STORE_RESULT);
	$rows_count = 0;
	
	if (!$result)
		return false;
	
	while ($result->fetch_row()) $rows_count++;
	
	if ($rows_count > 0)
		return true;
	else
		return false;
}

?>