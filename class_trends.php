<?php
class trends {
	var $connection=false;
	function baglan($host,$user,$password,$db) {
		$con = new mysqli($host,$user,$password,$db);
		if($con!==false) {
			$this->connection = $con;
			return $con;
		}
		else {
			return false;
		}
		
	}
        function get_channel_title_id($channel_title) {
		if($this->connection === false) {
			exit('bağlantı yok');
		}
                $con = $this->connection;
                $sql = ' SELECT id FROM settings';
                $sql .= ' WHERE ';
                $sql .= ' `key` ="channel_title"';
                $sql .= ' AND `value`="'.$channel_title.'" LIMIT 1;';
                
                $result = $con->query($sql);

                if(!$result) {
                    return false;
                }
                
                if($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $id= $row['id'];
                    return $id;
                }
                return false;
        }
        function set_channel_title_id($channel_title) {
		if($this->connection === false) {
			exit('bağlantı yok');
		}
                $con = $this->connection;
                $channel_title_id = $this->get_channel_title_id($channel_title);
                if($channel_title_id===false) {
                    $sql = 'INSERT INTO settings (`key`,`value`) VALUES(';
                    $sql .= '"channel_title","'.$channel_title.'");';
                    $result = $con->query($sql);
                    if($result!==false) {
                        $channel_title_id = $this->get_channel_title_id($channel_title);
                        return $channel_title_id;
                    }
                    else {
                        return false;
                    }
                }
                return $channel_title_id;
                
                
        }
        function get_channel_description_id($channel_description) {
		if($this->connection === false) {
			exit('bağlantı yok');
		}
                $con = $this->connection;
                $sql = ' SELECT id FROM settings';
                $sql .= ' WHERE ';
                $sql .= ' `key` ="channel_description"';
                $sql .= ' AND `value`="'.$channel_description.'" LIMIT 1;';
                
                $result = $con->query($sql);

                if(!$result) {
                    return false;
                }
                
                if($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $id= $row['id'];
                    return $id;
                }
                return false;
        }
        function set_channel_description_id($channel_description) {
		if($this->connection === false) {
			exit('bağlantı yok');
		}
                $con = $this->connection;
                $channel_description_id = $this->get_channel_description_id($channel_description);
                if($channel_description_id===false) {
                    $sql = 'INSERT INTO settings (`key`,`value`) VALUES(';
                    $sql .= '"channel_description","'.$channel_description.'");';
                    $result = $con->query($sql);
                    if($result!==false) {
                        $channel_description_id = $this->get_channel_description_id($channel_description);
                        return $channel_description_id;
                    }
                    else {
                        return false;
                    }
                }
                return $channel_description_id;
                
                
        }
        function create_datatable() {
		if($this->connection === false) {
			exit('bağlantı yok');
		}
		$sql = 'CREATE TABLE `datatable` (
                        `id` int(11) unsigned NOT NULL,
                        `channel_title_id` int(11) unsigned NOT NULL,
                        `channel_description_id` int(11) unsigned NOT NULL,
                        `item_title` varchar(255) NOT NULL,
                        `item_traffic` varchar(20) NOT NULL,
                        `item_description` varchar(255) NOT NULL,
                        `item_pubDate` varchar(50) NOT NULL,
                        `item_picture` varchar(255) DEFAULT NULL,
                        `item_picture_source` varchar(255) DEFAULT NULL,
                        `news_title` varchar(255) NOT NULL,
                        `news_snippet` varchar(255) NOT NULL,
                        `news_url` varchar(255) NOT NULL,
                        `news_source` varchar(50) NOT NULL,
                        `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8';
			//exit($sql);
		$con = $this->connection;
		//echo var_dump($con);exit();
		if(($res=$con->query($sql))===false) {
			echo $con->error;
		}
		else {
			echo 'datatable oluşturuldu.';
		}
        }

        function show_create_datatable() {
            $sql = 'SHOW CREATE TABLE datatable;';
		if($this->connection === false) {
			exit('bağlantı yok');
		}
		$con = $this->connection;
		//echo var_dump($con);exit();
		if(($res=$con->query($sql))===false) {
			echo $con->error;
		}
		else {
			$row = $res->fetch_assoc();
                        echo '<pre>';
                        echo($row['Create Table']);
                        //echo var_dump($row);
                        echo '</pre>';
		}
        }
        
        function create_settings() {
            $sql = 'CREATE TABLE `settings` (
                    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
                    ,`key` CHAR(50) NOT NULL
                    ,`value` CHAR(255) NOT NULL
                    ,PRIMARY KEY(id)
                    )
                    CHARSET=utf8 COLLATE utf8_general_ci ENGINE=InnoDB;
                    ';
		if($this->connection === false) {
			exit('bağlantı yok');
		}
		$con = $this->connection;
		//echo var_dump($con);exit();
		if(($res=$con->query($sql))===false) {
			echo $con->error;
		}
                else {
                    echo 'settings tablosu oluşturuldu';
                }
        }
        
        
	
}
