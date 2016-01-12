<?php

class Relationship {
    public $id;
    public $actor;
    public $target;
	public $note;
    public $status;
    public $created;
	public $list = array();
	
	// Status Map
	// 1 = Pending
	// 3 = Connected
	// 5 = Ignored
	// 7 = blocked

    public function __construct($user_id = null) {
        $this->user_id = $user_id;
    }
	
    public function create()
    {
        global $connection;
			
        $this->note = mysql_real_escape_string($this->note);
		
		$sql = "delete from relationships where actor = '$this->actor' and target = '$this->target'";
		//echo $sql . "<br>";
		mysql_query($sql, $connection);
		
        $sql = "insert into relationships (actor, target, note, status, created, updated) values ('$this->actor', '$this->target', '$this->note', '$this->status', NOW(), NOW())";
        //echo $sql . "<br>";
        mysql_query($sql, $connection);
    }
	
	public function update()
	{
        global $connection;
		
		if ($this->status == 1 || $this->status == 3)
		{	
			$sql = "delete from relationships where target = '$this->actor' and actor = '$this->target'";
			//echo $sql . "<br>";
			mysql_query($sql, $connection);
			
			$sql = "insert into relationships (actor, target, note, status, created, updated) values ('$this->target', '$this->actor', 'System Message: Reverse Connection Made', '$this->status', NOW(), NOW())";
			//echo $sql . "<br>";
			mysql_query($sql, $connection);
		}
		
        $sql = "update relationships set status = '$this->status' where actor = '$this->actor' and target = '$this->target'";
        //echo $sql . "<br>";
        mysql_query($sql, $connection);
	}
	
	public function listRelationships()
	{
        global $connection;

        $sql = "select 
					p.user_id,
					u.username,
					p.fullname,
					p.location,
					p.type,
					r.status,
					r.created
				from 
					relationships r 
					inner join users u on r.actor = u.uuid
					inner join profiles p on r.actor = p.user_id
				where 
					target = '$this->user_id'
				    and r.status in (1,3)";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->list[] = $obj;
        }
		
		return json_encode($this->list);
	}

    public function toJson() {
        return json_encode(get_object_vars($this));
    }

}