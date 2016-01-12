<?php

class Proofs {
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

        $sql = "insert into proofs_code (code_by, code_code, code_name, code_email, code_created, code_status) values ('$this->by', '$this->code', '$this->name', '$this->email', NOW(), 0)";
        //echo $sql . "<br />";
        mysql_query($sql, $connection);
    }

    public function read()
    {
        global $connection;

        $sql = "SELECT * FROM proofs_code WHERE code_code = '$this->code'";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);
        if(!empty($result)){
            $code = mysql_fetch_assoc($result);

            $this->id = $code['id'];
            $this->by = $code['code_by'];
            $this->code = $code['code_code'];
            $this->name = $code['code_name'];
            $this->email = $code['code_email'];
            $this->created = $code['code_created'];
            $this->status = $code['code_status'];
        }
        return false;
    }

    public function update()
    {
        global $connection;

        $sql = "update proofs_code set code_status = $this->status where code_code = '$this->code'";
        //echo $sql . "<br />";
        mysql_query($sql, $connection);
    }

    public function destroy()
    {
        global $connection;

        $sql = "update proofs_code set code_status = 5 where code_code = '$this->code'";
        //echo $sql . "<br />";
        mysql_query($sql, $connection);
    }

    public function toJson() {
        return json_encode(get_object_vars($this));
    }

}