<?php

class UserInfo
{
	private $id;
	private $contacts;

	function set($_id, $_contacts)
	{
		$this->id = $_id;
		$this->contacts = $_contacts;
	}

	function getId()
	{
		return $this->id;
	}

	function getContacts()
	{
		return $this->contacts;
	}

	function printInfo()
	{
		printf("ID: %s\nContacts List:\n%s\n", $this->id, $this->contacts);
	}
}

?>