<?php

function get_rooms()
{   //Load database
    require_once "database.php";

    //SQL querry
    $search_id_result = $db->query("SELECT * FROM room");

    //Check if the querry is successful
    if(mysqli_num_rows($search_id_result)!=0){
        //Load the rows into array one by one
        while( ($row = $search_id_result->fetch_array())!=0){
            $room[$row['room_id']]=[
                "title" => $row['room_name'],
                "img_url" => $row['image_path'],
                "color" => $row['color'],
            ];
        }
        return $room;
    }
}

function get_room($id) { 
    //Load database   
    require_once "database.php";

    //SQL querry
    $search_id_result = $db->query("SELECT * FROM room WHERE room_id=$id");
    //Check if the querry is successful
    if(mysqli_num_rows($search_id_result)!=0){
        //Load the row into array
        $row = $search_id_result->fetch_array();
        $room = [
            $row['room_id'] => [
                "title" => $row['room_name'],
                "img_url" => $row['image_path'],
                "color" => $row['image_path'],
            ]
        ];
        
        return $room[$id];
    }

    //return get_rooms()[$id];
}

function delete_room($id){
    require_once "database.php";

    $delete_id = "DELETE FROM room WHERE room_id=$id";
    if(!mysqli_query($db,$delete_id)){
        error_log("Given id: $id is invalid");
    }
}
