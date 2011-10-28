Wunderlist PHP SDK
=============

Unofficial PHP SDK for Wunderlist

Quickstart
-------

Start by including wunderlist.php file and creating an instance:

	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
	?>

Call get_lists method:

	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
		$lists = $wunderlist->get_lists();
		
	?>

And finally call get_list method to see the full list of tasks

	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
		$lists = $wunderlist->get_lists();
		
		$list = $wunderlist->get_list($lists[0]['id']);
	
	?>

Methods
-------
	
### Add task

Params:

* list_id (string, required)
* name (string, required)
* date (string, optional, in unix timestamp format) 

Usage:

	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
		$wunderlist->add_task("list_id", "My new task name", "1323415672");
	
	?>
	
If no error occurs, the function will return the id of inserted task, otherwise it will return a string: "error"

### Delete task

Params:

* task_id (string, required)
* list_id (string, required)

Usage:
	
	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
		$wunderlist->delete_task("task_id", "list_id");
	
	?>

Function will return "success" or "error"

### Update task

Params:

* task_id (string, required)
* params (array, required. Possible array keys are: "name", "note", "date" and "important")

All values of params must be string not integer!
You have to pass at least array key, all keys are optional.

Usage:
	
	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
		$wunderlist->update_task("task_id", array(
			"name" => "New Name", 
			"note" => "Some note", 
			"date" => "1323415672",
			"important" => "1"
		));
	
	?>