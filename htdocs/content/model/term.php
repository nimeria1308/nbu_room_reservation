<?php
	function get_terms($room_id){
		require "database.php";
		
		$row=[];

		$find_terms=$db->query("SELECT term_name FROM terms WHERE room_id_num=$room_id");
		if(mysqli_num_fields($find_terms)!=0){
			$row=$find_terms->fetch_all();
			return $row;
		}
		return $row;
	}
?>