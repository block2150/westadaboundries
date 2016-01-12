<?php

class User {
    public $id;
    public $login;
    public $username;
	public $fullname;
	public $type;
	public $type_name;
    public $email;
    public $contributor;
    public $created;
    public $lastlogin;
    public $optin;
    public $user_image;
	public $status;
	
	public $search = array();

    public function __construct($id = null) {
        $this->id = $id;
        if(!is_null($this->id)) {
            $this->load_user_data();
        }
    }

    protected function load_user_data()
    {

        global $connection;

        $sql = "select u.*, p.fullname, p.type from users u left join profiles p on u.uuid = p.user_id  where uuid = '$this->id'";
        //echo $sql . "<br>";
		$result = mysql_query($sql, $connection);
        if(!empty($result)){
            $user = mysql_fetch_assoc($result);

            $this->id = $user['uuid'];
            $this->username = $user['username'];
            $this->email = $user['email'];
            $this->fullname = $user['fullname'];
            $this->type = $user['type'];
            $this->type_name = getTypeName($user['type']);
            $this->contributor = $user['contributor'];
            $this->created = $user['created'];
            $this->lastlogin = $user['lastlogin'];
            $this->optin = $user['optin'];
            $this->status = $user['status'];

            $this->user_image = $this->get_user_image();
        }
        return false;
    }
    public function login($login, $password) {

        $this->login = mysql_real_escape_string($login);
        $password = mysql_real_escape_string($password);

        $this->id = $this->checkCredentials($password);
        if($this->id)
        {
            $this->load_user_data();
            $_SESSION['user_id'] = $this->id;
            $_SESSION['user_username'] = $this->username;
            $_SESSION['user_fullname'] = $this->fullname;
            $_SESSION['user_type'] = getTypeName($this->type);
            $_SESSION['user_email'] = $this->email;
            $_SESSION['user_contributor'] = $this->contributor;
            $_SESSION['user_image'] = $this->user_image;
            $_SESSION['user_status'] = $this->status;
            
            $this->lastlogin = date('Y-m-d H:i:s');
            $this->save();

			$this->getInviteCount();

            return $this->id;
        }
        return false;
    }
	
	public function getInviteCount()
	{
        global $connection;

        $sql = "select count(*) from relationships r inner join profiles p on r.actor = p.user_id where target = 'b3673a8a-df44-11e2-908d-0019b9ce92aa' and status = 1;";
		$result = mysql_query($sql, $connection);
        if(!empty($result)){
            $cnt = mysql_fetch_assoc($result);
			$Invites = "";
			if ($cnt['Invites'] > 0)
			{
				$Invites = $cnt['Invites'];
			}
			
            $_SESSION['user_invites'] = $Invites;
        }
	}
	
    protected function checkCredentials($password) {

        global $connection;

        $sql = "SELECT * FROM users WHERE email = '$this->login' or username = '$this->login'";
		//echo $sql."<br>";
        $result = mysql_query($sql, $connection);
        if(!empty($result)){
            $user = mysql_fetch_assoc($result);
            if($password == $user['password']){
                return $user['uuid'];
            }
        }
        return false;
    }

    public function create($username, $email, $password)
    {
        global $connection;
        global $public_portfolio;
        global $show_descriptions;

        $this->username = mysql_real_escape_string($username);
        $this->email = mysql_real_escape_string($email);
        $this->password = mysql_real_escape_string($password);
		
		$sql = "select uuid() as uuid";
        $result = mysql_query($sql, $connection);
        if(!empty($result)){
            $user = mysql_fetch_assoc($result);
            $this->id = $user['uuid'];
        }
		
        $sql = "insert into users (username, uuid, email, password, contributor, created, lastlogin, optin) values ('$this->username', '$this->id', '$this->email', '$this->password', 0, NOW(), NOW(), 1)";
        mysql_query($sql, $connection);
        $new_userid = mysql_insert_id();

        $sql = "insert into portfolio_settings (user_id, public_portfolio, show_descriptions) values ('$this->id', '$public_portfolio', '$show_descriptions')";
        mysql_query($sql, $connection);

        return $this->login($this->username, $this->password);
    }

    public function save() {
        global $connection;

        $sql = "UPDATE users SET username = '$this->username', email = '$this->email', contributor = '$this->contributor', lastlogin = '$this->lastlogin', optin = '$this->optin' where uuid = '$this->id'";
        $result = mysql_query($sql, $connection);
    }

    public function delete() {
    }

    public function get_user_image()
    {
        global $connection;

        $query = "SELECT file_name FROM portfolio_images WHERE user_image = 1 AND user_id = '$this->id'";
        $result = mysql_query($query, $connection);
        if(!empty($result)){
            $rs = mysql_fetch_assoc($result);
            return $rs['file_name'];
        }
        else
        {
            return "";
        }

    }
	
	public function setPassword($password)
	{
        global $connection;

        $sql = "UPDATE users SET password = '$password' where uuid = '$this->id'";
        $result = mysql_query($sql, $connection);
		
		
	}

    public function toJson() {
        return json_encode(get_object_vars($this));
    }

    public function getCommunityProfile($username)
    {
        global $connection;

        $sql = "select u.*, p.fullname, p.type from users u left join profiles p on u.uuid = p.user_id  where username = '$username'";
        $result = mysql_query($sql, $connection);
        if(!empty($result)){
            $user = mysql_fetch_assoc($result);

            $this->id = $user['uuid'];
            $this->username = $user['username'];
            $this->fullname = $user['fullname'];
            $this->type = $user['type'];
            $this->email = $user['email'];
            $this->contributor = $user['contributor'];
            $this->created = $user['created'];
            $this->lastlogin = $user['lastlogin'];
            $this->optin = $user['optin'];
            $this->status = $user['status'];

            $this->user_image = $this->get_user_image();

            $_SESSION['profile_id'] = $this->id;
            $_SESSION['profile_username'] = $this->username;
            $_SESSION['profile_fullname'] = $this->fullname;
            $_SESSION['profile_type'] = getTypeName($this->type);
            $_SESSION['profile_email'] = $this->email;
            $_SESSION['profile_contributor'] = $this->contributor;
            $_SESSION['profile_image'] = $this->user_image;
            $_SESSION['profile_status'] = $this->user_status;
        }
    }

	public function Search($query)
	{
        global $connection;
		
        $query = mysql_real_escape_string($query);

        $sql = "SELECT 
					p.user_id,
					u.username,
					p.fullname,
					p.location,
					t.type,
					IFNULL((select status from relationships where actor = '" . $this->id ."' and target = p.user_id), 0) as relationship
				FROM 
					profiles p 
					inner join users u on p.user_id = u.uuid
					inner join profile_types t on p.type = t.id
				where 
					(u.username LIKE '%" . $query . "%' OR p.fullname LIKE '%" . $query . "%' OR p.location LIKE '%" . $query . "%' OR t.type LIKE '%" . $query . "%')";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->search[] = $obj;
        }
		
		return json_encode($this->search);	
	}
}