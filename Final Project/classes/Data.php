<?php
	class Data
	{
		private $data_id;
		private $data_member_id;
		private $data_overview;
		private $data_perception;
		private $data_idea;
		private $data_recording;
		private $data_understanding;
		private $data_transformation;
		private $data_presentation;
		private $data_journal;

		public function get_data_id() { return $this->data_id; }
		public function get_data_member_id() { return $this->data_member_id; }
		public function get_data_overview() { return $this->data_overview; }
		public function get_data_perception() { return $this->data_perception; }
		public function get_data_idea() { return $this->data_idea; }
		public function get_data_recording() { return $this->data_recording; }
		public function get_data_understanding() { return $this->data_understanding; }
		public function get_data_transformation() { return $this->data_transformation; }
		public function get_data_presentation() { return $this->data_presentation; }
		public function get_data_journal() { return $this->data_journal; }

		
		public function set_data_member_id($member_id) { $this->data_member_id = $member_id; }
		public function set_data_overview($overview) { $this->data_overview = $overview; }
		public function set_data_perception($perception) { $this->data_perception = $perception; }
		public function set_data_idea($idea) { $this->data_idea = $idea; }
		public function set_data_recording($recording) { $this->data_recording = $recording; }
		public function set_data_understanding($understanding) { $this->data_understanding = $understanding; }
		public function set_data_transformation($transformation) { $this->data_transformation = $transformation; }
		public function set_data_presentation($presentation) { $this->data_presentation = $presentation; }
		public function set_data_journal($journal) { $this->data_journal = $journal; }
		
		public static function authenticate($username="", $password="") 
		{
		global $db;
		$username = $db->escape_value($username);
		$password = $db->escape_value($password);
		
		$sql  = "SELECT * FROM members ";
		$sql .= "WHERE member_email = '{$username}' ";
		$sql .= "AND member_password = '".$password."' ";
		$sql .= "LIMIT 1";

		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}
		
		public function add()
		{
			global $db;
			
			$this->date_added = date("Y-m-d H:i:s");
			$db->query("insert into members(username, password, position, firstname, lastname, email, image, admin, admin_travel_specials, date_added) values('".$db->escape_value($this->username)."', '".md5($db->escape_value($this->password))."', '".$db->escape_value($this->position)."', '".$db->escape_value($this->firstname)."', '".$db->escape_value($this->lastname)."', '".$db->escape_value($this->email)."', '".$db->escape_value($this->image["name"])."', '".$db->escape_value($this->admin)."', '".$db->escape_value($this->admin_travel_specials)."', '".$this->date_added."');");
			$last_insert_id = mysql_insert_id();
			
			$db->query("update members set priority = '".$last_insert_id."' where member_id = '".$last_insert_id."' limit 1");
			
			if(!empty($this->image["name"]))
			{
				$thumb = new Thumb;
				$thumb->generate($this->image["tmp_name"], 158, 175, "../profiles/thumbs/".$this->image["name"]);
			}
		}
		
		public function edit()
		{
			global $db;
		$db->query("update data set data_overview = '".$db->escape_value($this->data_overview)."', data_perception = '".$db->escape_value($this->data_perception)."', data_idea = '".$db->escape_value($this->data_idea)."', data_recording = '".$db->escape_value($this->data_recording)."', data_understanding = '".$db->escape_value($this->data_understanding)."', data_transformation = '".$db->escape_value($this->data_transformation)."', data_presentation = '".$db->escape_value($this->data_presentation)."', data_journal = '".$db->escape_value($this->data_journal)."' where data_id = '".$this->data_id."' ");
			echo mysql_error();
		}
		
		public static function find_by_id($member_id) 
		{
			$result_array = self::find_by_sql("SELECT * FROM data where data_id = '".$member_id."' limit 1");
			return !empty($result_array) ? array_shift($result_array) : false;
		}
		
		public static function find_by_member_id($member_id) 
		{
		//	echo "SELECT * FROM data where data_member_id = '".$member_id."' limit 1";
		$result_array = self::find_by_sql("SELECT * FROM data where data_member_id = '".$member_id."' limit 1");
			return !empty($result_array) ? array_shift($result_array) : false;
		}
		
		public static function find_all($start, $perpage) 
		{
			return self::find_by_sql("SELECT * FROM members order by priority limit $perpage offset $start");
		}
		
		public static function find_total()
		{
			global $db;
			
			$results = $db->query("select count(*) from members");
		
			$row = $db->fetch_array($results);

			return array_shift($row);
		}
		
		public static function remove($member_id)
		{
			global $db;
			
			$db->query("delete from members where member_id = '$member_id' limit 1");
		}
		
		public static function moveup($member_id)
		{
			global $db;
			
			$result = $db->query("select * from members where member_id = '$member_id' limit 1");
			$row1 = mysql_fetch_assoc($result);
			
			$result2 = $db->query("select * from members where priority < ".$row1['priority']." order by priority desc limit 1");
			
			if(mysql_num_rows($result2)>0)
			{
				$row2 = mysql_fetch_assoc($result2);
				$row3 = $row1;

				$db->query("update members set priority = '".$row2['priority']."' where member_id = '".$row1['member_id']."' limit 1");
				$db->query("update members set priority = '".$row3['priority']."' where member_id = '".$row2['member_id']."' limit 1");
			}
		}
		
		public static function movedown($member_id)
		{
			global $db;
			
			$result = $db->query("select * from members where member_id = '$member_id' limit 1");
			$row1 = mysql_fetch_assoc($result);
			
			$result2 = $db->query("select * from members where priority > ".$row1['priority']." order by priority limit 1");
			
			if(mysql_num_rows($result2)>0)
			{
				$row2 = mysql_fetch_assoc($result2);
				$row3 = $row1;

				$db->query("update members set priority = '".$row2['priority']."' where member_id = '".$row1['member_id']."' limit 1");
				$db->query("update members set priority = '".$row3['priority']."' where member_id = '".$row2['member_id']."' limit 1");
			}
		}
		
		public static function display($section_name)
		{
			global $db;
			
			$section_parts = explode("-", $section_name);
			
			$result = $db->query("select * from sections where section_id = '".$section_parts[0]."' limit 1");
			
			if($result)
				$row = mysql_fetch_assoc($result);
			
			echo htmlspecialchars_decode($row['content']);
		}
		
		public static function find_by_sql($sql="") 
		{
			global $db;
			$result_set = $db->query($sql);
			$object_array = array();
			
			while ($row = $db->fetch_array($result_set)) 
			{
				$object_array[] = self::instantiate($row);
			}
			return $object_array;
		}

		private static function instantiate($record) 
		{
			// Could check that $record exists and is an array
			$object = new self;
			// Simple, long-form approach:
			// $object->id 				= $record['id'];
			// $object->username 	= $record['username'];
			// $object->password 	= $record['password'];
			// $object->first_name = $record['first_name'];
			// $object->last_name 	= $record['last_name'];

			// More dynamic, short-form approach:
			foreach($record as $attribute=>$value)
			{
				if($object->has_attribute($attribute)) 
				{
					$object->$attribute = $value;
				}
			}
			return $object;
		}

		private function has_attribute($attribute) 
		{
			// get_object_vars returns an associative array with all attributes
			// (incl. private ones!) as the keys and their current values as the value
			$object_vars = get_object_vars($this);
			// We don't care about the value, we just want to know if the key exists
			// Will return true or false
			return array_key_exists($attribute, $object_vars);
		}
	}
?>