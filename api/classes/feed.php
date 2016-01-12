<?php

class ActivityFeed {
	
    public $limit = 50;
	public $stories = array();
	public $viewer;
	public $user_id;
	
	// Feeds Map
	// Community 	= 1 // 0001
	// Profile 		= 2 // 0010
	// Both 		= 3 // 0011
	// Friends 		= 4 // 0100
	// 
	
    public function listCommunityFeed()
    {
        global $connection;

        $sql = "SELECT 
					f.*,
					u.username,   
					p.fullname,
					IFNULL((select status from relationships where actor = '" . $this->viewer ."' and target = f.actor), 0) as relationship,
				    (select count(*) from activity_feed_likes where feed_id = f.feed_id and user_id = '" . $this->viewer ."') as liked
				FROM 
					activity_feed f
					INNER JOIN (SELECT max(published) as published,actor,type,title, feed_id FROM activity_feed GROUP BY actor,type,title) latest USING (published,actor,type)
					inner join users u on f.actor = u.uuid
					inner join profiles p on f.actor = p.user_id
				and 
					u.status = 1
				group by actor,type,title
				order by id desc
				limit ".$this->limit."";
		//echo $sql;
        $result = mysql_query($sql, $connection);
        while($obj = mysql_fetch_object($result)) {
            $this->stories[] = $obj;
        }
		
		return json_encode($this->stories);
    } 

    public function listNotifications()
    {
        global $connection;

        $sql = "select 
					f.feed_id,
					f.actor,
					u.username,   
					f.notification,
					f.notification_url,
					f.published    
				from 
					activity_feed f
					inner join users u on f.actor = u.uuid				
				where 
					target = '$this->user_id' 
					and actor <> '$this->user_id'
					and viewed = 0
					and notification_url <> ''
				order by f.id desc";
		//echo $sql;
        $result = mysql_query($sql, $connection);
        while($obj = mysql_fetch_object($result)) {
            $this->stories[] = $obj;
        }
		
		return json_encode($this->stories);
    } 
		
    public function getFeed($feed_id)
    {
        global $connection;

        $sql = "SELECT 
					f.*,
					u.username,   
					p.fullname,
					IFNULL((select status from relationships where actor = '" . $this->viewer ."' and target = f.actor), 0) as relationship,
				    (select count(*) from activity_feed_likes where feed_id = f.feed_id and user_id = '" . $this->viewer ."') as liked
				FROM 
					activity_feed f
					INNER JOIN (SELECT max(published) as published,actor,type,title, feed_id FROM activity_feed GROUP BY actor,type,title) latest USING (published,actor,type)
					inner join users u on f.actor = u.uuid
					inner join profiles p on f.actor = p.user_id and u.status = 1
				where 
					f.feed_id = '".$feed_id."' OR f.parent_id = '".$feed_id."'
				group by actor,type,title
				order by id desc
				limit ".$this->limit."";
		//echo $sql;
        $result = mysql_query($sql, $connection);
        while($obj = mysql_fetch_object($result)) {
            $this->stories[] = $obj;
        }
		
		return json_encode($this->stories);
    }
	
    public function listUserFeed()
    {
        global $connection;

        $sql = "SELECT 
					f.*,
					u.username,   
					p.fullname,
					IFNULL((select status from relationships where actor = '" . $this->viewer ."' and target = f.actor), 0) as relationship,
				    (select count(*) from activity_feed_likes where feed_id = f.feed_id and user_id = '" . $this->viewer ."') as liked
				FROM 
					activity_feed f
					INNER JOIN (SELECT max(published) as published,actor,type,title, feed_id FROM activity_feed GROUP BY actor,type,title) latest USING (published,actor,type)
					inner join users u on f.actor = u.uuid
					inner join profiles p on f.actor = p.user_id and u.status = 1
				where
					f.actor = '" . $this->user_id ."' OR f.target = '" . $this->user_id ."'
				group by actor,type,title
				order by id desc
				limit ".$this->limit."";
		//echo $sql;
        $result = mysql_query($sql, $connection);
        while($obj = mysql_fetch_object($result)) {
            $this->stories[] = $obj;
        }
		
		return json_encode($this->stories);
    }
	
	public function viewNotification($feed_id)
	{
        global $connection;

        $sql = "update activity_feed set viewed = 1 where feed_id = '$feed_id'";
        mysql_query($sql, $connection);
	}
	
	
	public function reportAbuse($user_id, $type_id, $type, $comments, $reported_by)
	{
        global $connection;
		
        $sql = "insert into abuse (user_id, type_id, type, comments, reported_on, reported_by) values ('$user_id', '$type_id', '$type', '$comments', NOW(), '$reported_by')";
        mysql_query($sql, $connection);

        $sql = "update users set status = 5 where uuid = '$user_id'";
        mysql_query($sql, $connection);
	}
	
	public function LikePost($user_id, $feed_id, $like)
	{
        global $connection;
		
		if ($like == "0")
		{
			$sql = "delete from activity_feed_likes where feed_id = '$feed_id' and user_id = '$user_id'";
			echo $sql;
			mysql_query($sql, $connection);

			$sql = "update activity_feed set Likes = Likes - 1 where feed_id = '" . $feed_id . "'";
			echo $sql;
			mysql_query($sql, $connection);
		}
		else
		{
			$sql = "insert into activity_feed_likes (feed_id, user_id) values ('$feed_id', '$user_id')";
			echo $sql;
			mysql_query($sql, $connection);

			$sql = "update activity_feed set Likes = Likes + 1 where feed_id = '" . $feed_id . "'";
			echo $sql;
			mysql_query($sql, $connection);
		}
	}	
}