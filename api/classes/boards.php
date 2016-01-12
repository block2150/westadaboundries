<?php

class Boards {
    public $user_id;
	
	public $board_id;
	public $board;
	public $file_name;
	public $comments;
	public $source;
	public $source_id;
	
    public $boards = array();
    public $items = array();

    public function __construct($user_id = null) {
        $this->user_id = $user_id;
        if(!is_null($this->user_id)) {
            $this->listBoards();
            $this->listItems();
        }
    }

    public function add()
    {
        global $connection;

        $board = mysql_real_escape_string($this->board);
        $comments = mysql_real_escape_string($this->comments);

		if ($this->board != "")
		{
			$sql = "insert into boards (user_id, board, private, created, status) values ('".$this->user_id."', '".$board."', '0', NOW(), '1')";
			//echo $sql . "<br />";
			 mysql_query($sql, $connection);
        	$this->board_id = mysql_insert_id();
		}

        $sql = "insert into board_items (user_id, board_id, file_name, comments, source, source_id, published) values ('".$this->user_id."', '".$this->board_id."', '".$this->file_name."', '".$commnets."', '".$this->source."', '".$this->source_id."', NOW())";
        //echo $sql . "<br />";
        mysql_query($sql, $connection);
    }

    public function listBoards()
    {
        global $connection;

        $sql = "SELECT 
					b.*,
					i.file_name,
					i.source,
					i.source_id,
					(select count(*) from board_items bi where bi.board_id = b.id) as luvs
				FROM 
					boards b
					INNER JOIN board_items i on b.id = i.board_id
				WHERE 
					b.user_id = '$this->user_id'
				GROUP BY
					b.id";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->boards[] = $obj;
        }
    }

    public function listItems()
    {
        global $connection;

        $sql = "SELECT 
				i.id,
				i.board_id,
				b.board,
				i.file_name,
				i.comments,
				i.source,
				i.source_id,
				i.published
			FROM
				board_items i
				inner join boards b on i.board_id = b.id
			WHERE 
				b.user_id = '$this->user_id'";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->items[] = $obj;
        }
    }

    public function toJson() {
        return json_encode(get_object_vars($this));
    }
}