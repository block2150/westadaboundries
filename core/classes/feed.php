<?php
class ActivityFeedCreate {
	
	public $id;
	public $type;
	public $status;
	public $actor;
	public $target;
	public $parent_id;	
	public $kv = array();
	
	private $title;
	private $content_title;
	private $content_body;
	private $notification;
	private $notification_url;
    private $feedKV = array();
	private $story;
	private $text;
	
    public function __construct($type = null, $actor = null, $target = null, $parent_id = null, $kv) 
	{
        $this->type = $type;
        $this->actor = $actor;
        $this->target = $target;
        $this->parent_id = $parent_id;
        $this->kv = $kv;
		
        if(!is_null($this->type)) 
		{	
			$this->getActorKV();
			$this->getTargetKV();
        	$this->getText();
			$this->getID();
						
			$this->status = $this->getFeedHeaders($this->text, "status");
			if (trim($this->status) == "active")
			{
				$this->title = $this->getFeedHeaders($this->text, "title");	
				$this->content_title = $this->getFeedHeaders($this->text, "title_url");
				$this->notification = $this->getFeedHeaders($this->text, "notification");
				$this->notification_url = $this->getFeedHeaders($this->text, "notification_url");
				$this->content_body = $this->getFeedBody($this->text);	
				
				$this->feedKV["{content_title}"] = $this->title;
				$this->feedKV["{content_title_url}"] = $this->content_title;
				$this->feedKV["{content_body}"] = $this->content_body;
				$this->feedKV["{parent_id}"] = $this->parent_id;
				
				$this->getNotification();
				$this->getStory();
				$this->create();
			}
        }
    }
	
	function create()
	{
        global $connection;		
		
		
        $title = mysql_real_escape_string($this->title);
        $story = mysql_real_escape_string($this->story);
        $notification = mysql_real_escape_string($this->notification);
        $notification_url = mysql_real_escape_string($this->notification_url);
		
		$sql = "insert into activity_feed (feed_id, actor, target, type, title, story, parent_id, published, notification, notification_url) values ('$this->id', '$this->actor', '$this->target', '$this->type', '$title', '$story', '$this->parent_id', NOW(), '$notification', '$notification_url')";
        echo $sql . "<br />";
		mysql_query($sql, $connection);		
	}
	
	function getID()
	{
        global $connection;		
		
		$sql = "select uuid() as uuid";
        $result = mysql_query($sql, $connection);
        if(!empty($result)){
            $user = mysql_fetch_assoc($result);
            $this->id = $user['uuid'];
			$this->feedKV["{id}"] = $user['uuid'];
        }
		
	}
	
	function getActorKV()
	{
        global $connection;
		
		$sql = "SELECT 
					u.uuid as actor_id,
					u.username as actor_username,   
					p.fullname as actor_name,
					(select case profile_kvs.v when 'male' then 'his' when 'female' then 'her' end as v from profile_kvs where user_id = u.uuid and k = 'profile-sex') as actor_possessive,
					IFNULL((SELECT i.file_name FROM portfolio_images i WHERE i.user_image = 1 AND user_id = u.uuid), '') as actor_profile_image
				from 
					users u 
					left join profiles p on u.uuid = p.user_id
				where 
					u.uuid = '".$this->actor."'";
					
        $result = mysql_query($sql, $connection);
        if(!empty($result)){
            $kv = mysql_fetch_assoc($result);
			
			$actor_name	= $kv['actor_name'];
				
			if ($actor_name == "")
			{
				$actor_name = $kv['actor_username'];
			}			
			
			$actor_name_url = '<a href="/portfolio/' . $kv['actor_username'] . '/">' . $actor_name . '</a>';
			
			$this->feedKV['{actor_id}'] = $kv['actor_id'];
			$this->feedKV['{actor_username}'] = $kv['actor_username'];
			$this->feedKV['{actor_name}'] = $actor_name;
			$this->feedKV['{actor_name_url}'] = $actor_name_url;
			$this->feedKV['{actor_portfolio_url}'] = "/portfolio/" . $kv['actor_username'];
			$this->feedKV['{actor_possessive}'] = $kv['actor_possessive'];
			$this->feedKV['{actor_profile_image}'] = "/portfolio/".$kv['actor_id']."/thumb_" . $kv['actor_profile_image'];
        }
		
		$actor_portfolio = "";
		$sql = "select file_name from portfolio_images where user_id = '".$this->actor."' and file_name <> '' order by sort";
        $result = mysql_query($sql, $connection);
		if ($result) {
		  while($row = mysql_fetch_array($result)) {
			$actor_portfolio .= '<img src="/portfolio/'.$this->actor.'/small_'.$row["file_name"].'" width="125" height="125" />';
		  }
		}
		
		$this->feedKV['{actor_portfolio}'] = $actor_portfolio;
	}
	
	function getTargetKV()
	{
		if ($this->target != "")
		{
			global $connection;
			
			$sql = "SELECT 
						u.uuid as target_id,
						u.username as target_username,   
						p.fullname as target_name,
						(select case profile_kvs.v when 'male' then 'his' when 'female' then 'her' end as v from profile_kvs where user_id = u.uuid and k = 'profile-sex') as target_possessive, 
						IFNULL((SELECT i.file_name FROM portfolio_images i WHERE i.user_image = 1 AND user_id = u.uuid), '') as target_profile_image
					from 
						users u 
						left join profiles p on u.uuid = p.user_id
					where 
						u.uuid = '".$this->target."'";
						
			$result = mysql_query($sql, $connection);
			if(!empty($result)){
				$kv = mysql_fetch_assoc($result);
				
				$target_name	= $kv['target_name'];
					
				if ($target_name == "")
				{
					$target_name = $kv['target_username'];
				}			
				
				$target_name_url = '<a href="/portfolio/' . $kv['target_username'] . '/">' . $target_name . '</a>';
	
				$this->feedKV['{target_id}'] = $kv['target_id'];
				$this->feedKV['{target_username}'] = $kv['target_username'];
				$this->feedKV['{target_name}'] = $target_name;
				$this->feedKV['{target_name_url}'] = $target_name_url;
				$this->feedKV['{target_portfolio_url}'] = "/portfolio/" . $kv['target_username'];
				$this->feedKV['{target_possessive}'] = $kv['target_possessive'];
				$this->feedKV['{target_profile_image}'] = "/portfolio/".$kv['target_id']."/thumb_" . $kv['target_profile_image'];
				$this->feedKV['{unique}'] = uniqid();
			}
		
			$target_portfolio = "";
			$sql = "select file_name from portfolio_images where user_id = '".$this->target."' order by sort";
			$result = mysql_query($sql, $connection);
			if ($result) {
			  while($row = mysql_fetch_array($result)) {
				$target_portfolio .= '<img src="/portfolio/'.$this->target.'/small_'.$row["file_name"].'" width="125" height="125" />';
			  }
			}
			
			$this->feedKV['{target_portfolio}'] = $target_portfolio;
		}
	}
	
	function getText() {
		$temp  = file_get_contents($_SERVER['DOCUMENT_ROOT']."/views/messages/feeds/".$this->type.".html");
        $temp = str_replace(array_keys($this->feedKV), $this->feedKV, $temp );
		$this->text = str_replace(array_keys($this->kv), $this->kv, $temp);
		
	}
	
	function getStory() 
	{
		$storyTemplate = "story.basic.html";
		if (strrpos($this->type,"comment") > -1)
		{
			$storyTemplate = "story.comment.html";		
		}
        $temp = file_get_contents($_SERVER['DOCUMENT_ROOT']."/views/messages/feeds/".$storyTemplate);
        $temp = str_replace(array_keys($this->feedKV), $this->feedKV, $temp );
		$this->story = str_replace(array_keys($this->kv), $this->kv, $temp);
	}
	
	function getNotification() {
		$temp  = $this->notification_url;
        $temp = str_replace(array_keys($this->feedKV), $this->feedKV, $temp );
		$this->notification_url = str_replace(array_keys($this->kv), $this->kv, $temp);
	}
		
	function getFeedHeaders($headers, $text)
	{
		$regex = "/\* ".$text.":(.*?)\\n/";
		if(preg_match_all($regex,$headers,$match)) {
			return $match[1][0];
		}
	}
	
	function getFeedBody($text)
	{
		$getStart = strpos($text, "*/");	
		return substr($text, $getStart + 3);
	}
}
