<?php

class Invite {
    public $id;
    public $by;
    public $code;
    public $name;
    public $email;
    public $created;
    public $status;

    public function __construct($code = null) {
        $this->code = $code;
        if(!is_null($this->code)) {
            $this->read();
        }
    }

    public function create()
    {
        global $connection;

        $sql = "insert into invites (invite_by, invite_code, invite_name, invite_email, invite_created, invite_status) values ('$this->by', '$this->code', '$this->name', '$this->email', NOW(), 0)";
        //echo $sql . "<br />";
        mysql_query($sql, $connection);
    }

    public function read()
    {
        global $connection;

        $sql = "SELECT * FROM invites WHERE invite_code = '$this->code'";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);
        if(!empty($result)){
            $invite = mysql_fetch_assoc($result);

            $this->id = $invite['id'];
            $this->by = $invite['invite_by'];
            $this->code = $invite['invite_code'];
            $this->name = $invite['invite_name'];
            $this->email = $invite['invite_email'];
            $this->created = $invite['invite_created'];
            $this->status = $invite['invite_status'];
        }
        return false;
    }

    public function update()
    {
        global $connection;

        $sql = "update invites set invite_status = $this->status where invite_code = '$this->code'";
        //echo $sql . "<br />";
        mysql_query($sql, $connection);
    }

    public function destroy()
    {
        global $connection;

        $sql = "update invites set invite_status = 5 where invite_code = '$this->code'";
        //echo $sql . "<br />";
        mysql_query($sql, $connection);
    }

    public function toJson() {
        return json_encode(get_object_vars($this));
    }

}