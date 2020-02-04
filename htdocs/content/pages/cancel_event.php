<?php
		require_once('model/event.php');
		require_once('model/database.php');

    if(isset($_GET['id'])&&!empty($_GET['id'])){
				$event_id=$_GET['id'];
				
				$find_room_id=$db->query("SELECT room_id FROM events WHERE event_id=$event_id");
				if(mysqli_num_rows($find_room_id)!=0){
					$row=$find_room_id->fetch_array();
					delete_reservation($event_id,$row['room_id']);
				}
    }

    header("Location: localhost:8080/rooms");  
?>