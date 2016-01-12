<?php

class Profile {
    public $user_id;
    public $username;
    public $fullname;
    public $location;
    public $summary;
    public $type;
    public $birthdate;
    public $public_profile;
	public $age;
    public $created;
    public $type_name;
	public $relationship;
    public $kv = array();
    public $jobs = array();
	public $settings = array();

    public function __construct($user_id = null) {
        $this->user_id = $user_id;
        if(!is_null($this->user_id)) {
            $this->read();
        }
    }

    public function create()
    {
        global $connection;

        $fullname = mysql_real_escape_string($this->fullname);
        $summary = mysql_real_escape_string($this->summary);
        $location = mysql_real_escape_string($this->location);

        $this->destroy();

        $sql = "insert into profiles (user_id, fullname, location, summary, type, birthdate, public_profile, created) values ('".$this->user_id."', '".$fullname."', '".$location."', '".$summary."', '".$this->type."', '".$this->birthdate."', 0, NOW())";
        //echo $sql . "<br />";
        mysql_query($sql, $connection);

        $this->setKV();
        $this->read();
    }

    public function read()
    {
        global $connection;

        $sql = "SELECT * FROM profiles p inner join users u on p.user_id = u.uuid WHERE user_id = '$this->user_id'";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);
        if(!empty($result)){
            $invite = mysql_fetch_assoc($result);

            $this->user_id = $invite['user_id'];
            $this->username = $invite['username'];
            $this->fullname = $invite['fullname'];
            $this->location = $invite['location'];
            $this->summary = $invite['summary'];
            $this->type = $invite['type'];
            $this->birthdate = $invite['birthdate'];
            $this->public_profile = $invite['public_profile'];
            $this->created = $invite['created'];
        }
        $this->listKV();
		$this->listJobs();
		$this->getAge();
		$this->getSettings();
		$this->getRelationship();
        $this->type_name = getTypeName($this->type);
    }

    public function update()
    {
        global $connection;

		// Set Updated Session Variables
		$_SESSION['user_fullname'] = $this->fullname;
		$_SESSION['user_type'] = $this->type;
		
        $sql = "update profiles set fullname = '$this->fullname', location = '$this->location', summary = '$this->summary', type = '$this->type', birthdate = '$this->birthdate', public_profile = $this->public_profile where user_id = '$this->user_id'";
        echo $sql . "<br />";
        mysql_query($sql, $connection);

        $this->setKV();
    }

    public function destroy()
    {
        global $connection;

        $sql = "delete from profiles where user_id = '$this->user_id'";
        //echo $sql . "<br />";
        mysql_query($sql, $connection);
    }

    public function listKV()
    {
        global $connection;

        $sql = "SELECT * FROM profile_kvs WHERE user_id = '$this->user_id'";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->kv[] = $obj;
        }
    }

    public function setKV()
    {
        global $connection;
		
        foreach ($this->kv as $key => $value) {

            $key = mysql_real_escape_string($key);
            $value= mysql_real_escape_string($value);
			$check = strpos($key, "profile");
						
			if ($check !== false)
			{
				$sql = "delete from profile_kvs where user_id = '$this->user_id' and k = '$key'";
				mysql_query($sql, $connection);
	
				$sql = "insert into profile_kvs (user_id, k, v) values ('$this->user_id','$key', '$value')";
				mysql_query($sql, $connection);
			}
        }
    }

    function getKV($k)
    {
        global $connection;

        $sql = "SELECT * FROM profile_kvs WHERE user_id = '$this->user_id' and k = '$k'";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);
        if(!empty($result)){
            $value = mysql_fetch_assoc($result);

           return $value['v'];
        }
    }
	
	function setJob($id, $name, $location, $jobdate, $details)
	{
        global $connection;
		
		$name = mysql_real_escape_string($name);
		$location = mysql_real_escape_string($location);
		$details = mysql_real_escape_string($details);
		if ($id == "")
		{
			$sql = "insert into profile_jobs (user_id, name, location, job_date, details, sort) values ('$this->user_id','$name', '$location', '$jobdate', '$details', 1)";
		}
		else
		{
			$sql = "update profile_jobs set name = '$name', location = '$location', job_date = '$jobdate', details = '$details' where id = $id";
		}
		//echo $sql . "<br />";
		mysql_query($sql, $connection);
		$this->listJobs();
	}

    public function listJobs()
    {
        global $connection;

        $sql = "SELECT * FROM profile_jobs WHERE user_id = '$this->user_id'";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->jobs[] = $obj;
        }
    }
	
	public function deleteJob($id)
	{
        global $connection;

        $sql = "DELETE FROM profile_jobs WHERE id = '$id'";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);
	}

    public function getSettings()
    {
        global $connection;

        $sql = "SELECT * FROM portfolio_settings WHERE user_id = '$this->user_id'";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->settings[] = $obj;
        }
    }
	
	public function getRelationship()
	{
        global $connection;

        $sql = "select * from relationships where target = '".$_SESSION['user_id']."' and actor = '$this->user_id'";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);
        if(!empty($result)){
            $Relationship = mysql_fetch_assoc($result);

            $this->relationship = $Relationship['status'];
        }
	}
	
	public function getFeatured()
    {
        global $connection;
		
		$sql = "SELECT user_id FROM portfolio_settings where featured = 1 ORDER BY RAND() ASC limit 0,1";
        //echo $sql . "<br>";
		$result = mysql_query($sql, $connection);
        if(!empty($result)){
            $featured = mysql_fetch_assoc($result);
            $this->user_id = $featured['user_id'];
			$this->read();
        }
	}
	
	function getAge()
	{
		//date in mm/dd/yyyy format; or it can be in other formats as well
        //$birthDate = "12/17/1983";
        //$birthDate = "1967-02-04";
		$birthDate = $this->birthdate;
		//explode the date to get month, day and year
		$birthDate = explode("-", $birthDate);
		//get age from date or birthdate
		$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
		$this->age = $age;
	}

    public function kvJson()
    {
        return json_encode($this->kv);
    }

    public function toJson() {
        return json_encode(get_object_vars($this));
    }
}