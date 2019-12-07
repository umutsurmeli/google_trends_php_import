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

        function create_datatable() {
		if($this->connection === false) {
			exit('bağlantı yok');
		}
		$sql = 'CREATE TABLE datatable (
                        id INT(11) UNSIGNED NOT NULL,
                        channel_title VARCHAR(255) NULL,
                        channel_description VARCHAR(255) NULL,
			item_title VARCHAR(255) NOT NULL,
                        item_traffic VARCHAR(20) NOT NULL,
                        item_description VARCHAR(255) NOT NULL,
                        item_pubDate VARCHAR(50) NOT NULL,
                        item_picture VARCHAR(255) NULL,
                        item_picture_source VARCHAR(255) NULL,
                        news_title VARCHAR(255) NOT NULL,
                        news_snippet VARCHAR(255) NOT NULL,
                        news_url VARCHAR(255) NOT NULL,
                        news_source VARCHAR(50) NOT NULL,
                        tarih TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY(id)
                        ) CHARSET=utf8 COLLATE utf8_general_ci ENGINE=InnoDB;';
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
