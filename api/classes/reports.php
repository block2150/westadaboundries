<?php

class Reports {
	
    public $limit = 25;
	public $report = array();
	public $report_str = "";
	
    public function UserByDay()
    {
        global $connection;

        $sql = "CALL UserByDay();";
		//echo $sql;
        $result = mysql_query($sql, $connection);
        while($obj = mysql_fetch_assoc($result)) {   
            $this->report[$obj['date']] = $obj['total'];
        }
		
		return json_encode($this->report);
    }	
	
    public function LoginsByDay()
    {
        global $connection;

        $sql = "CALL LoginsByDay();";
		//echo $sql;
        $result = mysql_query($sql, $connection);
        while($obj = mysql_fetch_assoc($result)) {   
            $this->report[$obj['date']] = $obj['logins'];
        }
		
		return json_encode($this->report);
    }	
	
    public function QuickStats()
    {
        global $connection;

        $sql = "select 
					(select count(*) from users where status = 1) TotalUsers,
					(SELECT COUNT(*) FROM users WHERE lastlogin BETWEEN CONCAT(CURDATE(), ' ', '00:00:00') AND CONCAT(CURDATE(), ' ', '23:59:59')) as LoginsToday,
					(select count(*) from portfolio_images) as TotalPhotos";
		//echo $sql;
        $result = mysql_query($sql, $connection);
        while($obj = mysql_fetch_assoc($result)) {   
            $this->report[] = $obj;
        }
		
		return json_encode($this->report);
    }		
	
    public function UserTypes()
    {
        global $connection;

        $sql = "SELECT 
					round(((count(*) * 100) / (SELECT count(*) FROM profiles))) as value,    
					t.type as label
				from
					profiles p
					inner join profile_types t on p.type = t.id
				group by 
					p.type";
		//echo $sql;
        $result = mysql_query($sql, $connection);
        while($obj = mysql_fetch_assoc($result)) {   
            $this->report[] = $obj;
        }
		
		return json_encode($this->report);
    }	
	
	
}