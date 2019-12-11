<?php
class trends {
	var $connection=false;
	function baglan($host,$user,$password,$db) {
		$con = new mysqli($host,$user,$password,$db);
		if($con!==false) {
                        $con->set_charset('utf8');
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
                $sql = ' SELECT id FROM '.DBPREFIX.'settings';
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
                    $sql = 'INSERT INTO '.DBPREFIX.'settings (`key`,`value`) VALUES(';
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
                $sql = ' SELECT id FROM '.DBPREFIX.'settings';
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
                    $sql = 'INSERT INTO '.DBPREFIX.'settings (`key`,`value`) VALUES(';
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
        function approx_traffic_toint($approx_traffic) {
                    $item_traffic = str_ireplace( '+', '',$approx_traffic);
                    $item_traffic = str_ireplace(',', '',$item_traffic);
                    $item_traffic = intval($item_traffic);
                    return $item_traffic;
        }
        function add_item($channel_title_id,$channel_description_id,$item) {
		if($this->connection === false) {
			exit('bağlantı yok');
		}
                $con = $this->connection;
                $sql = 'INSERT INTO '.DBPREFIX.'datatable (channel_title_id,channel_description_id,item_title,item_traffic,item_description';
                $sql.= ',item_pubDate,item_picture,item_picture_source,line,news_title,news_snippet,news_url,news_source) VALUES(';
                $sql.= '"'.$channel_title_id.'"';
                $sql.= ',"'.$channel_description_id.'"';
                $sql.= ',"'.(isset($item['title'])?$item['title']:'').'"';
                if(isset($item['ht:approx_traffic'])) {
                    $item_traffic = $this->approx_traffic_toint($item['ht:approx_traffic']);
                    
                }
                else {
                    $item_traffic = 0;
                }
                $sql.= ',"'.$item_traffic.'"';
                $sql.= ',"'.(isset($item['description'])?$item['description']:'').'"';
                $sql.= ',"'.(isset($item['pubDate'])?$item['pubDate']:'').'"';
                $sql.= ',"'.(isset($item['ht:picture'])?$item['ht:picture']:'').'"';
                $sql.= ',"'.(isset($item['ht:picture_source'])?$item['ht:picture_source']:'').'"';
                $sql.= ',"'.(isset($item['line'])?$item['line']:0).'"';
                $sql.= ',"'.(isset($item['ht:news_item']['ht:news_item_title'])?$item['ht:news_item']['ht:news_item_title']:'').'"';
                $sql.= ',"'.(isset($item['ht:news_item']['ht:news_item_snippet'])?$item['ht:news_item']['ht:news_item_snippet']:'').'"';
                $sql.= ',"'.(isset($item['ht:news_item']['ht:news_item_url'])?$item['ht:news_item']['ht:news_item_url']:'').'"';
                $sql.= ',"'.(isset($item['ht:news_item']['ht:news_item_source'])?$item['ht:news_item']['ht:news_item_source']:'').'"';
                $sql.=');';
                $result = $con->query($sql);
                if($con->error) {
                    //print('['.$con->errno.']'.'['.$con->error.']');
                    return false;
                }
                if(!$result) {
                    return false;
                }
                return true;

        }
        function  update_item_traffic($channel_title_id, $channel_description_id,$item) {
            if($this->connection === false) {
                    exit('bağlantı yok');
            }
            $con = $this->connection;
            if(isset($item['ht:approx_traffic'])) {
                $item_traffic = $this->approx_traffic_toint($item['ht:approx_traffic']);

            }
            else {
                return false;
            }
            
            $sql = 'UPDATE '.DBPREFIX.'datatable SET';
            $sql.= ' item_traffic="'.$item_traffic.'"';
            $sql.= ' ,up_date=NOW()';
            $sql.= ' WHERE channel_title_id='.$channel_title_id;
            $sql.= ' AND channel_description_id='.$channel_description_id;
            $sql.= ' AND item_pubDate="'.$item['pubDate'].'"';
            $sql.= ' AND news_url="'.$item['ht:news_item']['ht:news_item_url'].'"';
            $sql.= ' LIMIT 1;';
            if($result=$con->query($sql)) {
                return $con->affected_rows;
            }
            else {
                return 0;
            }
            
        }
        function create_datatable() {
		if($this->connection === false) {
			exit('bağlantı yok');
		}
		$sql = 'CREATE TABLE `'.DBPREFIX.'datatable` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`channel_title_id` INT(11) UNSIGNED NOT NULL,
	`channel_description_id` INT(11) UNSIGNED NOT NULL,
	`item_title` VARCHAR(255) NOT NULL,
	`item_traffic` INT(11) UNSIGNED NOT NULL DEFAULT "0",
	`item_description` VARCHAR(255) NOT NULL,
	`item_pubDate` VARCHAR(50) NOT NULL,
	`item_picture` VARCHAR(255) NULL DEFAULT NULL,
	`item_picture_source` VARCHAR(255) NULL DEFAULT NULL,
	`line` TINYINT(2) UNSIGNED NOT NULL DEFAULT "0",
	`news_title` VARCHAR(255) NOT NULL,
	`news_snippet` VARCHAR(255) NOT NULL,
	`news_url` VARCHAR(255) NOT NULL,
	`news_source` VARCHAR(50) NOT NULL,
	`log_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`up_date` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `channel_title_id` (`channel_title_id`, `channel_description_id`, `item_pubDate`, `news_url`),
	INDEX `cdi` (`channel_description_id`),
	CONSTRAINT `cdi` FOREIGN KEY (`channel_description_id`) REFERENCES `'.DBPREFIX.'settings` (`id`),
	CONSTRAINT `cti` FOREIGN KEY (`channel_title_id`) REFERENCES `'.DBPREFIX.'settings` (`id`)
)
COLLATE="utf8_general_ci"
ENGINE=InnoDB;

';
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

        function show_create($table_name) {
            $sql = 'SHOW CREATE TABLE '.$table_name.';';
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
            $sql = 'CREATE TABLE `'.DBPREFIX.'settings` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`key` CHAR(50) NOT NULL,
	`value` CHAR(255) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `keyvalue` (`key`, `value`)
        )
        COLLATE="utf8_general_ci"
        ENGINE=InnoDB
        ;';
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
