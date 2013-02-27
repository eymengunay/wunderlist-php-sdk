Wunderlist 1 PHP SDK
=============

Unofficial PHP SDK for Wunderlist 1

### Warning: This is not for Wunderlist 2! It is an old experiment with Wunderlist 1.

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
	
### Get Lists

Returns an array of all available lists

Params:

* No params required

Usage:
	
	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
		$wunderlist->get_lists();
	
	?>
	
Example Output:

	Array
	(
		[0] => Array
			(
				[name] => Inbox
				[id] => 1234567
			)
	
		[1] => Array
			(
				[name] => Some Other List
				[id] => 7654321
			)
	
	)

### Get List

Returns all tasks in given list

Params:

* list_id (string, required)

Usage:
	
	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
		$wunderlist->get_list("list_id");
	
	?>
	
Example Output:

	Array
	(
		[todo] => Array
			(
				[0] => Array
					(
						[task] => 444111
						[note] => NULL
						[date] => 1319407200
						[name] => Do something else
					)
					
				[1] => Array
					(
						[task] => 222333
						[note] => NULL
						[date] => NULL
						[name] => Download wunderlist
					)
			)
	
		[done] => Array
			(
				[0] => Array
					(
						[task] => 123123
						[note] => Additional notes...
						[date] => 1319407200
						[name] => Download Wunderlist php sdk
						[done] => 3
					)
			)
	)
	
The output is divided into 2 arrays. "todo" array contains tasks that are not completed yet, instead "done" contains completed ones. 

In "done" array you will see "done" key. That indicates how many days ago a task is completed. In this example case "Download Wunderlist php sdk" has been completed 3 days ago.

In both "todo" and "done" arrays there is a key called "task". That is your task id. You will need it in case you want to update or delete it.

If there are no notes attached to the task or no due date specified NULL value is returned.	

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

Updates "name", "note", "date" or "important" values of given task.

Params:

* task_id (string, required)
* params (array, required. Possible array keys are: "name", "note", "date" and "important")

All values of params must be string not integer!
You have to pass at least one array key, all keys are optional.

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

### Add list

Params:

* name (string, required) 

Usage:

	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
		$wunderlist->add_list("My new task name");
	
	?>
	
If no error occurs, the function will return the id of inserted list, otherwise it will return a string: "error"

### Remove list

Params:

* id (string, required) 

Usage:

	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
		$wunderlist->remove_list(58441240);
	
	?>
	
If no error occurs, the function will return boolean true, otherwise it will return boolean false

### Update list

Params:

* id (string, required)
* name (string, required) 

Usage:

	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
		$wunderlist->update_list(58441240, "An updated list name");
	
	?>
	
If no error occurs, the function will return the id of updated list, otherwise it will return a string: "error"

### Badge counts

It will return an associative array with "overdue" and "today" keys

Params:

* No params required

Usage:
	
	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
		$wunderlist->count_badge();
	
	?>
	
### List count

It will return an integer that shows no. of tasks in given list

Params:

* list_id (string, required)

Usage:
	
	<?php
	
		include "wunderlist.php";
		
		$wunderlist = new Wunderlist("your_email", "your_password");
	
		$wunderlist->count_list("list_id");
	
	?>