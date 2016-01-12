<?php

class Portfolio {
    public $id;
    public $user_id;
    public $public_portfolio;
    public $show_descriptions;
	
    public $featured;
    public $featured_id;
	public $featured_username;
	public $featured_fullname;
	public $featured_type;
	
	public $listPortfolioViews = array();

    public function __construct($user_id = null) {
        $this->user_id = $user_id;
        if(!is_null($this->user_id)) {
            $this->load_data();
			if ($this->user_id == '')
			{
       			$this->user_id = $user_id;
				$this->public_portfolio = 1;
				$this->show_descriptions = 1;
				$this->featured = 0;
				$this->set();
			}
        }
    }

    public function load_data()
    {
        global $connection;

        $sql = "SELECT * FROM portfolio_settings WHERE user_id = '$this->user_id'";
        //echo $sql . "<br>";
		$result = mysql_query($sql, $connection);
        if(!empty($result)){
            $image = mysql_fetch_assoc($result);

            $this->id = $image['id'];
            $this->user_id = $image['user_id'];
            $this->public_portfolio = $image['public_portfolio'];
            $this->show_descriptions = $image['show_descriptions'];
            $this->featured = $image['featured'];
        }
        return false;
    }

    public function set()
    {
        global $connection;

        $sql = "delete from portfolio_settings WHERE user_id = '$this->user_id'";
        //echo $sql . "<br>";
		mysql_query($sql, $connection);

        $sql = "insert into portfolio_settings (user_id, public_portfolio, show_descriptions, featured) values ('$this->user_id', '$this->public_portfolio', '$this->show_descriptions', '$this->featured')";
        //echo $sql . "<br>";
        mysql_query($sql, $connection);

    }
	
	public function toggleFeatured()
	{
		if ($this->featured == 1)
		{
			$this->featured = 0;
		}
		else
		{
			$this->featured = 1;
		}
		$this->set();
	}
	
	public function getFeatured()
    {
        global $connection;
		
		$sql = "SELECT 
					p.user_id, 
					username,
					fullname,
					type
				FROM 
					users u 
					inner join portfolio_settings ps on u.uuid = ps.user_id 
					inner join profiles p on u.uuid = p.user_id 
				where 
					featured = 1 
				ORDER BY RAND() ASC limit 0,1";
        //echo $sql . "<br>";
		$result = mysql_query($sql, $connection);
        if(!empty($result)){
            $featured = mysql_fetch_assoc($result);
            $this->featured_id = $featured['user_id'];
            $this->featured_username = $featured['username'];
            $this->featured_fullname = $featured['fullname'];
            $this->featured_type = $featured['type'];
        }
	}
	
	public function userView($user_id, $portfolio_id)
	{
        global $connection;

        $sql = "insert into portfolio_views (user_id, portfolio_id, new, last_viewed) values ('$user_id', '$portfolio_id', 1, NOW()) ON DUPLICATE KEY UPDATE views=views+1";
        //echo $sql . "<br>";
        mysql_query($sql, $connection);

	}

	public function listViews()
	{
		
        global $connection;

        $sql = "select 
					p.user_id,
					u.username,
					p.fullname,
					p.location,
					p.type,
					IFNULL(r.status, 0) as relationship,
					v.views,
					v.new as new_views,
					v.last_viewed
				from 
					portfolio_views v
					left join relationships r on v.user_id = r.actor
					inner join users u on v.user_id = u.uuid
					inner join profiles p on v.user_id = p.user_id
				where 
					v.portfolio_id = '$this->user_id'
				group by user_id
				order by new, last_viewed DESC
				limit 0,50";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->listPortfolioViews[] = $obj;
        }
		
        $sql = "update portfolio_views set new = 0 WHERE portfolio_id = '$this->user_id'";
        //echo $sql . "<br>";
		mysql_query($sql, $connection);

		
		return json_encode($this->listPortfolioViews);	
	}

    public function toJson() {
        return json_encode(get_object_vars($this));
    }

}