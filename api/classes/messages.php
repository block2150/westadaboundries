<?php

class Message {
	
    public $message_id;
    public $parent_id;
	public $message;
	public $message_date;
	public $from;
	public $to = array();
	public $messages = array();
	public $users = array();
	
	
    public function NewMessage()
    {
        global $connection;
		
		$this->message_id = $this->GetMessageID();
		$message = mysql_real_escape_string($this->message);

        $sql = "insert into messages (message_id, parent_id, message, message_date) values ('$this->message_id', '$this->parent_id', '$message', NOW())";
		//echo $sql . "<br/><br/>";
        $result = mysql_query($sql, $connection);
        
		$this->SetFrom();
		$this->SetTo();
    } 
	
	private function SetFrom()
	{
        global $connection;
		
        $sql = "insert into message_users (message_id, user_id, sender, viewed) values ('$this->message_id', '$this->from', '1', '1')";
		//echo $sql . "<br/><br/>";
        $result = mysql_query($sql, $connection);
	}
	
	private function SetTo()
	{
        global $connection;
				
		foreach ($this->to as $item)
		{
			$sql = "insert into message_users (message_id, user_id, sender) values ('$this->message_id', '".$item['id']."', '0')";
			//echo $sql . "<br/><br/>";
			$result = mysql_query($sql, $connection);
		}
	}	
	
	public function GetMessageID()
	{		
        global $connection;
		$sql = "select uuid() as uuid";
        $result = mysql_query($sql, $connection);
        if(!empty($result)){
            $user = mysql_fetch_assoc($result);
            return $user['uuid'];
        }
	}
	
	public function SelectList($user_id)
	{
        global $connection;
		
        $query = mysql_real_escape_string($query);

        $sql = "SELECT 
					IFNULL(p.fullname, u.username) as 'name',
					p.user_id as 'id'
				FROM 
					profiles p 
					inner join users u on p.user_id = u.uuid
					inner join relationships r on u.uuid = r.target
				where
					r.actor = '$user_id'";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->users[] = $obj;
        }
		
		return json_encode($this->users);	
		
	}
	
	public function SentMessages($user_id)
	{
        global $connection;
        $query = mysql_real_escape_string($query);

        $sql = "select 
					*,
					IFNULL((select CONCAT('[',
									   GROUP_CONCAT(
										   CONCAT('{\"user_id\":\"', mu2.user_id, '\",\"username\":\"', u.username, '\",\"fullname\":\"', p.fullname, '\",\"sender\":\"', mu2.sender, '\"}')
								SEPARATOR ','),
							 ']')
						from 
							message_users mu2
							inner join users u on mu2.user_id = u.uuid
							inner join profiles p on p.user_id = u.uuid
						where 
							mu2.message_id = m.message_id
							and sender = 0),
					'[]') as recipients
				from 
					messages m
					inner join message_users mu on m.message_id = mu.message_id 
				where 
					sender = 1
					and mu.user_id = '$user_id'
					and mu.status = 1
				order by
					message_date DESC";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->messages[] = $obj;
        }
		
		return json_encode($this->messages);	
	}
	
	public function InboxMessages($user_id)
	{
        global $connection;
        $query = mysql_real_escape_string($query);

        $sql = "select 
					*,
					IFNULL((select CONCAT('[',
									   GROUP_CONCAT(
										   CONCAT('{\"user_id\":\"', mu2.user_id, '\",\"username\":\"', u.username, '\",\"fullname\":\"', p.fullname, '\",\"sender\":\"', mu2.sender, '\"}')
								SEPARATOR ','),
							 ']')
						from 
							message_users mu2
							inner join users u on mu2.user_id = u.uuid
							inner join profiles p on p.user_id = u.uuid
						where 
							mu2.message_id = m.message_id
							and sender = 1),
					'[]') as recipients
				from 
					messages m
					inner join message_users mu on m.message_id = mu.message_id 
				where 
					sender = 0
					and mu.user_id = '$user_id'					
					and mu.status = 1
				order by
					message_date DESC";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->messages[] = $obj;
        }
		
		return json_encode($this->messages);	
	}
	
	public function GetMessage($message_id)
	{
        global $connection;
        $query = mysql_real_escape_string($query);

        $sql = "select 
					*,
					IFNULL((select CONCAT('[',
									   GROUP_CONCAT(
										   CONCAT('{\"user_id\":\"', mu2.user_id, '\",\"username\":\"', u.username, '\",\"fullname\":\"', p.fullname, '\",\"sender\":\"', mu2.sender, '\"}')
								SEPARATOR ','),
							 ']')
						from 
							message_users mu2
							inner join users u on mu2.user_id = u.uuid
							inner join profiles p on p.user_id = u.uuid
						where 
							mu2.message_id = m.message_id),
					'[]') as recipients
				from 
					messages m
					inner join message_users mu on m.message_id = mu.message_id 
				where 
					(m.message_id = '$message_id' or m.parent_id = '$message_id')
					and mu.status = 1
				group by
					m.message_id
				order by 
					m.id desc";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->messages[] = $obj;
        }
		
		return json_encode($this->messages);	
	}
	
	
	public function DeleteMessage($user_id, $message_id)
	{
        global $connection;
				
		$sql = "update message_users set Status = 5 where message_id = '$message_id' and user_id = '$user_id'";
		$result = mysql_query($sql, $connection);
	}
	
	
	public function Read($user_id, $message_id)
	{
        global $connection;
				
		$sql = "update message_users set viewed = 1 where (message_id = '$message_id' or message_id in (select message_id from messages where parent_id = '$message_id')) and user_id = '$user_id'";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);
	}
	
	public function Unread($user_id)
	{
        global $connection;
				
		$sql = "select * from message_users where user_id = '$user_id' and viewed = 0";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->messages[] = $obj;
        }
		
		return json_encode($this->messages);	
	}
}