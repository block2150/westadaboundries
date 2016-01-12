<?php

class Community {
	
    public $logins = array();

    public function recentLogins()
    {
        global $connection;

        $sql = "SELECT 
					u.uuid, u.username, 
					IFNULL((SELECT i.file_name FROM portfolio_images i WHERE i.user_image = 1 AND user_id = u.uuid), 'icon_image.png') as file_name
				from 
					users u 
				where
					u.status = 1
				order by 
					lastlogin desc				
				limit 20";
        //echo $sql . "<br />";
        $result = mysql_query($sql, $connection);

        while($obj = mysql_fetch_object($result)) {
            $this->logins[] = $obj;
        }
    }
}