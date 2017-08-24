<?php
	class Member
	{
		private $member_id;
		private $member_firstname;
		private $member_lastname;
		private $member_email;
		private $member_username;
		private $member_password;
		private $member_organization;
		private $member_is_admin;
		private $member_last_submitted;

		public function get_member_id() 			{ return $this->member_id; }
		public function get_member_firstname() 		{ return $this->member_firstname; }
		public function get_member_lastname() 		{ return $this->member_lastname; }
		public function get_member_email() 			{ return $this->member_email; }
		public function get_member_username() 		{ return $this->member_username; }
		public function get_member_password() 		{ return $this->member_password; }
		public function get_member_organization()	{ return $this->member_organization; }
		public function get_member_is_admin() 		{ return $this->member_is_admin; }

		public function set_username($username) 	{ $this->username = $username; }
		public function set_password($password) 	{ $this->member_password = $member_password; }
		public function set_position($position) 	{ $this->member_position = $member_position; }
		public function set_firstname($firstname) 	{ $this->member_firstname = $member_firstname; }
		public function set_lastname($lastname) 	{ $this->member_lastname = $member_lastname; }
		public function set_email($email)		 	{ $this->member_email = $member_email; }
		public function set_image($image)		 	{ $this->member_image = $member_image; }
		public function set_admin($admin) 			{ $this->member_is_admin = $admin; }
			
		public static function authenticate($username="", $password="") 
		{
			global $db;
			
			$username = $db->escape_value($username);
			$password = $db->escape_value($password);
			
			$sql  = "SELECT * FROM members ";
			$sql .= "WHERE member_username = '{$username}' ";
			$sql .= "AND member_password = '".$password."' ";
			$sql .= "LIMIT 1";

			$result_array = self::find_by_sql($sql);
			return !empty($result_array) ? array_shift($result_array) : false;
		}
	
		public static function get_username(){
			global $db;
			$username = $db->escape_value($username);
			$password = $db->escape_value($password);
			echo $username;
			$sql  = "SELECT * FROM members ";
			$sql .= "WHERE member_username = '{$username}' ";
			$sql .= "AND member_password = '".$password."' ";
			$sql .= "LIMIT 1";
			$result = mysql_query($sql);
			while ($result) {
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$us = $row['member_username'];
			return $us;	
			}
			return $username;
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
			
			if(!empty($this->password))
				$password_sql = ", password = '".md5($db->escape_value($this->password))."'";
			
			if(!empty($this->image["name"]))
			{
				$image_sql = ", image = '".$db->escape_value($this->image["name"])."'";
				$thumb = new Thumb;
				$thumb->generate($this->image["tmp_name"], 158, 175, "../profiles/thumbs/".$this->image["name"]);
			}
			//echo "update members set username = '".$db->escape_value($this->username)."', firstname = '".$db->escape_value($this->firstname)."' $password_sql $image_sql ,lastname = '".$db->escape_value($this->lastname)."', email = '".$db->escape_value($this->email)."', admin = '".$db->escape_value($this->admin)."' where member_id = '".$this->member_id."' ";
			$db->query("update members set username = '".$db->escape_value($this->username)."', firstname = '".$db->escape_value($this->firstname)."' $password_sql $image_sql ,lastname = '".$db->escape_value($this->lastname)."', email = '".$db->escape_value($this->email)."', admin = '".$db->escape_value($this->admin)."', admin_travel_specials = '".$db->escape_value($this->admin_travel_specials)."' where member_id = '".$this->member_id."' ");
			echo mysql_error();
		}
		
		public static function find_by_id($member_id) 
		{
			$result_array = self::find_by_sql("SELECT * FROM members where member_id = '".$member_id."' limit 1");
			return !empty($result_array) ? array_shift($result_array) : false;
		}
		
		public static function find_all($start, $perpage) 
		{
			return self::find_by_sql("SELECT * FROM members limit $perpage offset $start");
		}
		
		public static function find_all_by_instructor($instructor_id, $start, $perpage)
		{
		//	echo "SELECT * FROM members where member_instructor_id limit $perpage offset $start";
		//echo "SELECT * FROM members where member_instructor_id = '$instructor_id' limit $perpage offset $start";
			return self::find_by_sql("SELECT * FROM members where member_instructor_id = '$instructor_id' limit $perpage offset $start");
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