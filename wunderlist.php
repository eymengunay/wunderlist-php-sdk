<?php

/*
* Unofficial Wunderlist PHP SDK
*
* @author: Eymen Gunay
* @mail: eymen@egunay.com
* @web: egunay.com
*
*/
class Wunderlist
{
	var $email;
	var $password;
	var $login_url = "http://www.wunderlist.com/ajax/user";
	var $cookie_file = "cookie.txt";
	var $user_agent	= "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.106 Safari/535.2";

	function __construct($params)
	{
		$email = $params[0];
		$password = $params[1];
		// Set username & password
		if (isset($email) && isset($password))
		{
			$this->email = $email;
			$this->password = $password;	
		}
		else
		{	
			die("username_password_missing");
		}
		// Check cookie file and if login is necessary
		$login_required = TRUE;
		if (file_exists($this->cookie_file) && is_readable($this->cookie_file))
		{
			$handle = fopen($this->cookie_file, "r");
			$expr = "";
			while (($buffer = fgets($handle, 4096)) !== false)
			{
				$expr = $buffer . "\n";
			}
			if(!empty($expr))
			{
				$expr = str_replace("\t", ",", $expr);
				if(!empty($expr))
				{
					$expr = $expr[4];
					if (time() < $expr)
					{
						$login_required = FALSE;	
					}
				}
			}
		}
		else
		{
			if (fopen($this->cookie_file, "w"))
			{
				fclose($handle);	
			}
			else
			{
				return "cookie_file_create_error";	
			}
		}
		if ($login_required === TRUE)
		{
			$login = $this->_login();
			$login = json_decode($login);
			if ($login->code == 202)
			{
				return "auth_error";
			}
			elseif ($login->code != 200)
			{
				return "error";
			}
		}
	}

	private function _login()
	{
		$email_input_name = "email";
		$password_input_name = "password";

		$params[$email_input_name] = $this->email;
		$params[$password_input_name] = md5($this->password);

		return $this->_curl($this->login_url, $this->_serialize($params));
	}
	/*
	* Deletes a task
	*
	* Usage: delete_task(task_id, list_id)
	* You have to specify both task and list ids.
	* It will return "success" or "error"
	*
	* @param: string
	* @param: string
	* @return: string
	*/
	public function delete_task($task_id, $list_id)
	{
		$params = array(
			"id" => "" . $task_id . "",
			"list_id" => "" . $list_id . "",
			"deleted" => "" . 1 . ""
		);
		$json = json_encode($params);
		$params = array("task" => $json);
		$serialized = $this->_serialize($params);
		$return = $this->_curl("http://www.wunderlist.com/ajax/tasks/update/", $serialized);
		$return = json_decode($return);
		if ($return->status == "success")
		{
			return "success";
		}
		else
		{
			return "error";	
		}
	}
	/*
	* Updates a task
	*
	* Usage: update_task(task_id, param array)
	* Param array accepts: name, note, date and important
	* Example Usage: update_task("123456", array("name" => "New Name", "note" => "Some note", "date" => "1323415672", "important" => "1"));
	*
	* @param: string
	* @param: array
	* @return: string
	*/
	public function update_task($id, $params)
	{
		$json = json_encode($params);
		$params = array("task" => $json);
		$serialized = $this->_serialize($params);
		$return = $this->_curl("http://www.wunderlist.com/ajax/tasks/insert/", $serialized);
		$return = json_decode($return);
		if ($return->status == "success")
		{
			return "success";
		}
		else
		{
			return "error";	
		}
	}
	/*
	* Adds a new task
	*
	* Usage: add_task(list_id, name, date)
	* Date param is optional. Use it only if you want to set a due date
	* Example Usage: add_task("123456", "My new task name", "1323415672");
	*
	* @param: string
	* @param: string
	* @param: string
	* @return: string
	*/
	public function add_task($list_id, $name, $date = NULL)
	{
		$params = array(
			"list_id" => "" . $list_id . "",
			"name" => "" . $name . ""
		);
		if ($date != NULL)
		{
			$params['date'] = "" . intval($date) . "";
		}
		$json = json_encode($params);
		$params = array("task" => $json);
		$serialized = $this->_serialize($params);
		$return = $this->_curl("http://www.wunderlist.com/ajax/tasks/insert/", $serialized);
		$return = json_decode($return);
		if ($return->status == "success")
		{
			return $return->id;
		}
		else
		{
			return "error";	
		}
	}

	/*
	* Adds a new list
	*
	* Usage: add_list(name)
	* Date param is optional. Use it only if you want to set a due date
	* Example Usage: add_task("123456", "My new task name", "1323415672");
	*
	* @param: string
	* @return: string
	* @author: James Mountford
	*/
	public function add_list($name)
	{
		$params = array(
			"name" => $name,
		);
		$json = json_encode($params);
		$params = array("list" => $json);
		$serialized = $this->_serialize($params);
		$return = $this->_curl("http://www.wunderlist.com/ajax/lists/insert/", $serialized);
		$return = json_decode($return);
		if ($return->status == "success")
		{
			return $return->id;
		}
		else
		{
			return "error";	
		}
	}

	/*
	* Removes a list
	*
	* Usage: remove_list(list_id)
	* Date param is optional. Use it only if you want to set a due date
	* Example Usage: remove_list(123456);
	*
	* @param: string
	* @return: string
	* @author: James Mountford
	*/
	public function remove_list($id)
	{
		$params = array(
			"id" => $id,
			"deleted" => 1,
		);
		$json = json_encode($params);
		$params = array("list" => $json);
		$serialized = $this->_serialize($params);
		$return = $this->_curl("http://www.wunderlist.com/ajax/lists/update/", $serialized);
		$return = json_decode($return);
		if ($return->status == "success")
		{
			return true;
		}
		else
		{
			return false;	
		}
	}

	/*
	* Updates a list
	*
	* Usage: update_list(list_id, name)
	* Date param is optional. Use it only if you want to set a due date
	* Example Usage: update_list(123456, "Test");
	*
	* @param: string
	* @param: string
	* @return: string
	* @author: James Mountford
	*/
	public function update_list($id, $name)
	{
		$params = array(
			"id" => $id,
			"name" => $name,
		);
		$json = json_encode($params);
		$params = array("list" => $json);
		$serialized = $this->_serialize($params);
		$return = $this->_curl("http://www.wunderlist.com/ajax/lists/update/", $serialized);
		$return = json_decode($return);
		if ($return->status == "success")
		{
			return $return->id;
		}
		else
		{
			return "error";	
		}
	}


	/*
	* Returns badge counts
	*
	* Usage: count_badge()
	* It will return an associative array with "overdue" and "today" keys
	*
	* @return: array
	*/
	public function count_badge()
	{
		$url = "http://www.wunderlist.com/ajax/tasks/badgecounts/";
		$params = array("date" => time());
		$data = $this->_curl($url, $this->_serialize($params));
		$data = json_decode($data);
		if ($data->status == "success")
		{
			$return['overdue'] = $data->overdue;
			$return['today']	= $data->today;
			return $return;
		}
		else
		{
			return "error";	
		}
	}
	/*
	* Returns task count of a specified list
	*
	* Usage: count_list(list_id)
	* It will return an integer
	*
	* @return: int
	*/
	public function count_list($id)
	{
		$url = "http://www.wunderlist.com/ajax/lists/count/" . $id;
		$data = $this->_curl($url, NULL, "GET");
		$data = json_decode($data);
		if ($data->status == "success")
		{
			return $data->count;
		}
		else
		{
			return "error";	
		}
	}
	/*
	* Get list tasks
	*
	* Usage: get_list(list_id)
	* It will return an associative array with all information.
	* Example output:
	* Array
	* todo
	* 0
	* task => task_id
	* note => task_note
	* date => task_due_date
	* name => task_name
	* done
	* 0
	* task => task_id
	* note => task_note
	* date => task_due_date
	* name => task_name
	*
	* @return: array
	*/
	public function get_list($list_id)
	{
		$url = "http://www.wunderlist.com/ajax/lists/id/" . $list_id;
		$data = $this->_curl($url, NULL, "GET");
		$data = json_decode($data);
		if ($data->status == "success")
		{
			$return = array();
			$dom = new DOMDocument();
			$dom->strictErrorChecking = false;
			libxml_use_internal_errors(true);
			if(!empty($data->data))
			{
				$dom->loadHTML($data->data);
				$uls = $dom->getElementsByTagName("ul");
				$i = 0;
				foreach ($uls as $ul)
				{
					if (strpos($ul->getAttribute("class"), "mainlist") !== FALSE)
					{
					$list = "todo";
					}
					elseif ($ul->getAttribute("class") == "donelist")
					{
					$list = "done";	
					}
				foreach ($ul->getElementsByTagName("li") as $li)
				{
					$return[$list][$i]['task'] = $li->getAttribute("id");
					$return[$list][$i]['note'] = NULL;
					$return[$list][$i]['date'] = NULL;
					foreach ($li->getElementsByTagName("span") as $span)
					{
						if (strpos($span->getAttribute("class"), "description") !== FALSE)
						{
							$return[$list][$i]['name'] = $span->nodeValue;
						}
						elseif (strpos($span->getAttribute("class"), "activenote") !== FALSE)
						{
							$return[$list][$i]['note'] = $span->nodeValue;	
						}
						elseif (strpos($span->getAttribute("class"), "showdate") !== FALSE)
						{
							$return[$list][$i]['date'] = $span->getAttribute("rel");	
						}
					}
					if ($ul->getAttribute("class") == "donelist")
					{
						$return[$list][$i]['done'] = str_replace("donelist_", "", $ul->getAttribute("id"));	
					}
					$i++;
					}
					if (strpos($ul->getAttribute("class"), "mainlist") !== FALSE)
					{
						$i = 0;
					}
				}
				$old_list = $dom->getElementsByTagName("donelist");
				return $return;
			}
			else
			{
			return "Error";	
			}
		}
		else
		{
			return 'empty';
		}
	}
	/*
	* Returns an array of all available lists
	*
	* Usage: get_lists()
	* It will return an array
	* Example Output:
	* Array
	* 0
	* name => List name
	* id => List id
	*
	* @return: array
	*/
	public function get_lists()
	{
		$html = $this->_curl("http://www.wunderlist.com/home", NULL, "GET", FALSE);
		$dom = new DOMDocument();
		$dom->loadHTML($html);
		$lists = $dom->getElementById("lists");
		$bs = $lists->getElementsByTagName("b");
		$i = 0;
		foreach ($bs as $b)
		{
			$return[$i]['name'] = $b->nodeValue;
			$i++;
		}
		$as = $lists->getElementsByTagName("a");
		$i = 0;
		foreach ($as as $a)
		{
			$return[$i]['id'] = str_replace("list", "" , $a->getAttribute("id"));
			$i++;	
		}

		return $return;
	}
	private function _serialize($array)
	{
		if (is_array($array))
		{
			$string = "";
			foreach ($array as $key => $val)
			{
				$string .= urlencode($key) . "=" . urlencode($val) . "&";	
			}
			$string = substr($string, 0, -1);
			return $string;
		}
	}
	private function _curl($url, $params = NULL, $method = "POST", $ajax = TRUE)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($method == "POST")
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			if ($params != NULL)
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);	
			}
		}
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_file);
		if ($ajax === TRUE)
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Requested-With: XMLHttpRequest"));	
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close ($ch);
		return $result;
	}
}