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