<?php

class Images {
    public $id;
    public $user_id;
    public $file_name;
    public $description;
    public $user_image;
    public $category_id = 1;
    public $sort;
    public $published;
    public $list = array();


    public function __construct($id = null) {
        $this->id = $id;
        if(!is_null($this->id)) {
            $this->load_data();
        }
    }

    public function load_data()
    {
        global $connection;

        $sql = "SELECT * FROM portfolio_images WHERE id = $this->id";
        $result = mysql_query($sql, $connection);
        if(!empty($result)){
            $image = mysql_fetch_assoc($result);

            $this->id = $image['id'];
            $this->user_id = $image['user_id'];
            $this->file_name = $image['file_name'];
            $this->description = $image['description'];
            $this->user_image = $image['profile_image'];
            $this->category_id = $image['category_id'];
            $this->published = $image['published'];
            $this->sort = $image['sort'];
        }
        return false;
    }

    public function add()
    {
        global $connection;

        $this->file_name = mysql_real_escape_string($this->file_name);
        $this->sort = $this->nextSort();
        $this->user_image = 0;

        if ($this->sort == 1)
        {
            $this->user_image = 1;
            $_SESSION['user_image'] = $this->file_name;
        }

        $sql = "delete from  portfolio_images where file_name = '".$this->file_name."'";
        mysql_query($sql, $connection);

        $sql = "insert into portfolio_images (user_id, file_name, description, user_image, category_id, sort, published) values ('$this->user_id', '$this->file_name', '', '$this->user_image', '$this->category_id', '$this->sort', NOW())";
        //echo $sql."\n";
        mysql_query($sql, $connection);
    }

    public function delete($clearProfileImage) {

        global $connection;

        if ($clearProfileImage == 1)
        {
            $_SESSION['user_image'] = "";
        }

        $sql = "DELETE FROM  portfolio_images WHERE id = $this->id";
        mysql_query($sql, $connection);

        return false;
    }

    public function update()
    {
        global $connection;

        $this->description = mysql_real_escape_string($this->description);
        $this->sort = mysql_real_escape_string($this->sort);

        $sql = "UPDATE portfolio_images SET description = '$this->description', category_id = '$this->category_id', sort = '$this->sort' WHERE id = $this->id";
        mysql_query($sql, $connection);

        return true;
    }

    public function all() {

        global $connection;

        $sql = "SELECT 
					i.*,
					IFNULL((select id from board_items bi where bi.user_id = 'fcd07723-d9e8-11e2-9895-00ff893578ce' and bi.source_id = i.user_id and bi.file_name = i.file_name), 0) as luved 
				FROM 
					portfolio_images i
				WHERE 
					i.user_id = '$this->user_id'";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $list[] = $obj;
        }

        return $list;
    }

    public function listByCategory() {

        global $connection;

        $sql = "SELECT * FROM portfolio_images WHERE user_id = '$this->user_id' and category_id = '$this->category_id'";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $list[] = $obj;
        }

        return $list;
    }

    public function user_image_set()
    {
        global $connection;

        $_SESSION['user_image'] = $this->file_name;

        $sql = "update portfolio_images set user_image = 0 where user_id = '$this->user_id'";
        mysql_query($sql, $connection);

        $sql = "update portfolio_images set user_image = 1 where id = $this->id";
        mysql_query($sql, $connection);

        return true;
    }

    private function nextSort() {
        global $connection;

        $sql = "select ifNull(MAX(sort), 0) + 1  as sort from portfolio_images where user_id = '$this->user_id'";
        echo $sql . "\n";
        $result = mysql_query($sql, $connection);
        $portfolio = mysql_fetch_assoc($result);

        return $portfolio['sort'];
    }

    public function toJson() {
        return json_encode(get_object_vars($this));
    }

}