<?php
	include_once("connection.php");
	class DataBase{
		
		public function execute_query_return_result($query){
			$result=mysql_query($query);
			return $result;
		}
		public function execute_query($query){
			mysql_query($query);
		}
		
	}//class

?>