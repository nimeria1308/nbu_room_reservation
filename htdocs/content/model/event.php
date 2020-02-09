<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

# returns true on successfully updating the event
# otherwise false, which will revert it in the frontend
# $event is as follows
#  {
#    id,
#    startDelta,
#    endDelta,
#    old: {
#      start,
#      end,
#    },
#    new: {
#      start,
#      end,
#    }
#  }
# Where:
# * startDelta/endDelta are { years, months, days, milliseconds }
# * start/end are DateTime in the local zone
function update_calendar_event($event)
{
    require_once "database.php";
		$old_start=$event->old->start->format('Y/m/d H:i:s');
		$old_end=$event->old->end->format('Y/m/d H:i:s');

		$new_start=$event->new->start->format('Y/m/d H:i:s');
		$new_end=$event->new->end->format('Y/m/d H:i:s');

		$id=(int)$event->id;

		//Check for existing event
    $find_event=$db->query("SELECT * FROM events WHERE type_id=$id AND start_date='$old_start' AND end_date='$old_end'");
    if(mysqli_num_rows($find_event)!=0){
        //Check for free time slot
        $find_time_slot=$db->query("SELECT event_id FROM events WHERE '$new_start'>=start_date AND '$new_end'<=end_date");
        if(mysqli_num_rows($find_time_slot)==0){
            //Update the event if timeslot if free
            $update=$db->query("UPDATE events SET start_date='$new_start',end_date='$new_end' WHERE type_id=$id AND start_date='$old_start' AND end_date='$old_end'");
            if($update){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
     error_log(print_r($event, true));
    return false;
}

function update_existing_event($updated_event,$start,$end){
	require "database.php";
	$ip=find_ip();
	$time = date("Y/m/d H:i:s");

	//If the current date is not 2 days before the creation of the event
	//Then the event will not be created
	$valid_date= date('Y-m-d',strtotime($time. "+2 days"));
	if($valid_date>$start){
		return false;
	}

	$update_existing_event="UPDATE events SET title='$updated_event[name]', start_date='$start',end_date= '$end',
	room_id_num= $updated_event[room_id],creator_name='$updated_event[user]',email='$updated_event[email]',telephone=$updated_event[phone],
	organizer='$updated_event[organizer]',ip='$ip',creation_time='$time'";

	if(isset($updated_event['multimedia'])){
		$update_existing_event.=",multimedia=$updated_event[multimedia] WHERE event_id=$updated_event[event_id]";
	}else{
		$update_existing_event.="WHERE event_id=$updated_event[event_id]";
	}

	//If the event is create successfully
	if($db->query($update_existing_event)===TRUE){
		send_email($updated_event['email'],$updated_event['event_id']);
		return true;
	}else{
		return false;
	}
}

function edit_event($updated_event,$room){
	if(!is_room_open($room,$updated_event)){
		$arr=[
			'status'=>false,
			'error'=>"Невалиден час. Залата не е отворена",
		];
		return $arr;
	}
	
	
	//Check for existing event
	$start_date=$updated_event['date'].' '.$updated_event['start_time'];
	$end_date=$updated_event['date'].' '.$updated_event['end_time'];
	if(!check_time_slot($start_date,$end_date,$updated_event['room_id'])){
		$arr=[
			'status'=>false,
			'error'=>"Невалиден час",
		];
		return $arr;
	}

	if(!update_existing_event($updated_event,$start_date,$end_date)){
		$arr=[
			'status'=>false,
			'error'=>"Попълнената информация е навалидна",
		];
		return $arr;
	}

	$event=get_event_by_id($updated_event['event_id']);
	if( $event['multimedia']=='+техника'){
		$updated_event['multimedia']=1;
	}else{
		$updated_event['multimedia']=0;
	}
	
	return repeat_event($updated_event);
}

function get_event_by_id($id){
	require "database.php";

	$find_event=$db->query("SELECT * FROM events WHERE event_id=$id");
	if(mysqli_num_rows($find_event)!=0){
		$row=$find_event->fetch_array();

		$start_date=date('m/d/Y',strtotime($row['start_date']));
		$start_hour= date('H:i', strtotime($row['start_date']));
		$end_hour= date('H:i', strtotime($row['end_date']));


		if( $row['multimedia']==1){
			$multimedia='+техника';
		}else{
			$multimedia='без техника';
		}
		
		$data[]=[
			"id" => $row['type_id'],
			"real_id" => $row['event_id'],
			"title" => $row['title'],
			"start_date" => $start_date,
			"start_hour" => $start_hour,
			"end_hour" => $end_hour,
			"organizer" => $row['organizer'],
			"multimedia" => $multimedia,
			"user" => $row['creator_name'],
			"phone" => $row['telephone'],
			"email" => $row['email'],
			"other" => $row['description'],
			"creation_time" => $row['creation_time']];

			return $data[0];
	}
	
}

function is_room_open($room,$event){
	//Extracts only the day from the starts day in int variable
	$date=date_create($event['date']);
	$start_day= date_format($date,"N");

	if($start_day>0 && $start_day<6){
		if($room['workday_open']<=$event['start_time']&& $room['workday_close']>=$event['end_time'] && $event['start_time']<$event['end_time']){
			return true;
		}
	}else{
		if($room['weekend_open']<=$event['start_time']&& $room['weekend_close']>=$event['end_time']&& $event['start_time']<$event['end_time']){
			return true;
		}
	}
	return false;
}

//Find if the current time slot is free
function check_time_slot($start_date,$end_date,$room_id){
	require "database.php";

	$find_time_slot=$db->query("SELECT event_id FROM events WHERE '$start_date'>=start_date AND '$end_date'<=end_date AND room_id_num=$room_id");
	//If a result is found then there is another event in that time slot
	if(mysqli_num_rows($find_time_slot)!=0){
		return false;
	}
	return true;
}

// $day is a 3 letter representation of the repeating day
// $start_date is string in format Y//m/d H:i:s
function find_next_day($day,$start_date){
	//Creates a date variable
	$date=date_create($start_date);
	//Extracts only the day from the starts day in int variable
	$start_day= date_format($date,"N");
	//Extracts the int day from the variable $day which is stored in 3-letter string
	$repeat_day= date("N",strtotime($day));
	
	//If the starting day if before the repeating day go to next week
	if($start_day>$repeat_day){
		//Find the day for the next week
		$diff=$repeat_day-$start_day+7;
	}else if($start_day==$repeat_day){
		$diff=7;
	}else{ //If the starting day is after the repeating day stay in the current week
		$diff=abs($start_day-$repeat_day);
	}
	
	$string= "+$diff days";
	$next_date= date('Y-m-d',strtotime($start_date. $string));
	
	//Returns the day the event must repeat
	return $next_date;
}

//Finds the ip of the current user
function find_ip(){
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$ip = gethostbyname($hostname);
	return $ip;
}

//Finds an event id by looking its start date, end date and room
function find_event_id($start,$end,$room_id){
	require "database.php";

	$find_id=$db->query("SELECT event_id FROM events WHERE start_date='$start' AND end_date='$end' AND room_id_num=$room_id");
	if(mysqli_num_rows($find_id)!=0){
		$row=$find_id->fetch_array();
		return $row['event_id'];
	}
}

function create_new_event($event,$start,$end){
	require "database.php";
	$ip=find_ip();
	$time = date("Y/m/d H:i:s");

	//If the current date is not 2 days before the creation of the event
	//Then the event will not be created
	$valid_date= date('Y-m-d H:i',strtotime($time. "+2 days"));
	if($valid_date>$start){
		return false;
	}

	$create_new_event="INSERT INTO events
	(title, description, start_date, end_date, room_id_num,creator_name, email,telephone,organizer,multimedia,ip, creation_time) VALUES
	('$event[name]', '$event[other]', '$start', '$end', $event[room_id], '$event[user]', '$event[email]', $event[phone],'$event[organizer]',$event[multimedia] ,'$ip','$time')";

	//If the event is create successfully
	if($db->query($create_new_event)===TRUE){
		//Find its id and set value to type_id which is used it case of repeating events
		$id=find_event_id($start,$end,$event['room_id']);
		$update_type_id=$db->query("UPDATE events SET type_id = $id WHERE event_id=$id");
		send_email($event['email'],$id);
		return true;
	}else{
		return false;
	}
}

function create_new_repeating_event($event,$start,$end){
	require "database.php";
	$ip=find_ip();
	$time = date("Y/m/d H:i:s");

	$create_new_event="INSERT INTO events
	(title, description, start_date, end_date, room_id_num,type_id,creator_name, email,telephone,organizer,multimedia,ip, creation_time) VALUES
	('$event[name]', '$event[other]', '$start', '$end', $event[room_id], $event[event_id], '$event[user]', '$event[email]', $event[phone],'$event[organizer]',$event[multimedia] ,'$ip','$time')";

	if($db->query($create_new_event)===TRUE){
		$id=find_event_id($start,$end,$event['room_id']);
		send_email($event['email'],$id);
		return true;
	}else{
		return false;
	}
}

function repeat_event($event){
		//Check if user has set repeating events
		if($event['repeat']!='never'){
			//The date from which all of the repeating events start
			$start_date = $event['repeat_start'];
			if($start_date<$event['date']){
				$arr=[
					'status'=>false,
					'error'=>"Началната повтаряща дата е по малко от началната дата на събитието"				
				];
				return $arr;
			}

			$event['repeat_day']=explode(" ",$event['repeat_day']);
			//If the repeating is set to weekly the event can repeat at most 4 times at any given days
			if( $event['repeat']=='weekly'){
				//Starts the counter for the number of repeats
				if($event['repeat_end']=='count'){
					$events_created=0;
				}
	
				for($count=0;$count<$event['weekly_repeat'];++$count){
					//Cycle for each of the selected days to repeat
					foreach($event['repeat_day'] as $day){
						//Finds the date for the next day set
						$next_date=find_next_day($day,$start_date);

						//Adds the hour to the date to be inserted into the database
						$next_start=$next_date.' '.$event['start_time'];
						$next_end=$next_date.' '.$event['end_time'];
		
						//Checks from the database if the time slot is free
						if(check_time_slot($next_start,$next_end,$event['room_id']) && create_new_repeating_event($event,$next_start,$next_end)){
							if($event['repeat_end']=='count'){
								++$events_created;
							}
						}	
						if($event['repeat_end']=='count' && $events_created>=$event['repeat_end_count']){
							$arr=['status'=>true,];
							return $arr;
						}
					}
					//After for all of the selected days are set to be repeated
					//Advance the starting repeating date by 7 days
					$start_date= date('Y/m/d',strtotime($start_date. "+7 days"));
					//If the created events have exceeded the maximum number of repeating events
					//No more events are to be created
				}
			}else if($event['repeat_end']=='date'){
				//Repeat while the end date is reached
				$count=0; 
				while($start_date<=$event['repeat_end_date'] && $count<$event['weekly_repeat'])
				{
					foreach($event['repeat_day'] as $day){
						//Finds the date for the next day set
						$next_date=find_next_day($day,$start_date);

						//If the next date for the event is before the end date
						//No more events can be created
						if($next_date>$event['repeat_end_date']){
							$arr=['status'=>true,];
							return $arr;
						}
							
						//Adds the hour to the date to be inserted into the database
						$next_start=$next_date.' '.$event['start_time'];
						$next_end=$next_date.' '.$event['end_time'];
	
						//Checks from the database if the time slot is free
						if(check_time_slot($next_start,$next_end,$event['room_id'])){
							create_new_repeating_event($event,$next_start,$next_end);
						}
					}
					//After for all of the selected days are set to be repeated
					//Advance the starting repeating date by 7 days
					$start_date= date('Y/m/d',strtotime($start_date. "+7 days"));
					++$count;
				}
			//If the repeating is set to monthly 
			}else if($event['repeat']=='monthly'){
				//Advance the date by 1 month
				$next_date= date('Y/m/d',strtotime($start_date. "+1 months"));
				$events_created=0;
				//Cycle for each of the selected days to repeat
				foreach($event['repeat_day'] as $day){
					$next_date=find_next_day($day,$next_date);
	
					//If the next day beyound the end date no further events must be created
						//No more events are to be created
					if($event['repeat_end']=='date' && $next_date>$event['repeat_end_date']){
						$arr=['status'=>true,];
						return $arr;
					}
					
					//Adds the hour to the date to be inserted into the database
					$next_start=$next_date.' '.$event['start_time'];
					$next_end=$next_date.' '.$event['end_time'];
	
					//Checks from the database if the time slot is free
					if(check_time_slot($next_start,$next_end,$event['room_id'])){
						create_new_repeating_event($event,$next_start,$next_end);
						//If the created events have exceeded the maximum number of repeating events
						//No more events are to be created
						if($event['repeat_end']=='count' && $events_created>$event['repeat_end_count']){
							$arr=['status'=>true,];
							return $arr;
						}
					}
				}
			}
		}else{
			$arr=['status'=>true,];
			return $arr;
		}

		$arr=['status'=>true,];
		return $arr;
}

//$event is an array which contains all of the information the user has entered from the form
//$repeat is an array which is empty if an event is not repeatable else it contains all the information from the repeat form
//$event is as follows{
//	start_date - string format Y/m/d
//	start_hour - string format H:i:s
//	end_hour  - string format H:i:s
//	room_id - int The id of the room where the id is located
//	user - string
//	title - string
//	multimedia	- boolean
//	creator	- string
//	phone_number - int
//	email - string
//	description - string
//}
function new_event($event,$room){
	if(!is_room_open($room,$event)){
		$arr=[
			'status'=>false,
			'error'=>"Невалиден час. Залата не е отворена",
		];
		return $arr;
	}


	//Check for existing event
	$start_date=$event['date'].' '.$event['start_time'];
	$end_date=$event['date'].' '.$event['end_time'];
	if(!check_time_slot($start_date,$end_date,$event['room_id'])){
		$arr=[
			'status'=>false,
			'error'=>"Невалиден час",
		];
		return $arr;
	}

	//Create the event
	if(!create_new_event($event,$start_date,$end_date)){
		$arr=[
			'status'=>false,
			'error'=>"Попълнената информация е навалидна",
		];
		return $arr;
	}
	//Save the event id for use in repeating events because the fullcalendar API used same id for repeating events
	$event['id']=find_event_id($start_date,$end_date,$event['room_id']);

	return repeat_event($event);
}

function load_requests_button($room_id){
    require 'database.php';
    
    $sql = "SELECT * FROM events WHERE room_id=$room_id";
    $stmt = mysqli_stmt_init($db);
    $result = mysqli_query($db,$sql);
    $data = array();
    if(mysqli_num_rows($result) > 0){
        $count = 0;
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
            $count++;
        }
    }
    
    return $data;
}

function search($title){
    require 'database.php';
    $data = array();
	
	$find_field=$db->prepare("SELECT * FROM events WHERE  MATCH(title) 
	AGAINST(?)");
	$find_field->bind_param('s',$title);
	$find_field->execute();

	$result=$find_field->get_result();
    if(mysqli_num_rows($result)==0){
		//TO DO SOME ERROR
		error_log(print_r($title, true));	
    }
    else{
        while($row = mysqli_fetch_assoc($result)){
			$new_start =  new DateTime($row['start_date']);
            $new_end = new DateTime($row['end_date']);
            
			if( $row['multimedia']==1){
				$multimedia='+техника';
			}else{
			$multimedia='без техника';
			}

            $data[]=[
				"id" => $row['type_id'],
				"real_id" => $row['event_id'],
				"title" => $row['title'],
                "start" => $new_start,
                "end" => $new_end,
                "organizer" => $row['organizer'],
                "multimedia" => $multimedia,
                "user" => $row['creator_name'],
                "phone" => $row['telephone'],
                "email" => $row['email'],
				"other" => $row['description'],
				"creation_time" => $row['creation_time']];
		}
	}
    return $data;
}

function requests($id,$start,$end){
    require 'database.php';
    $sql = "SELECT * FROM events WHERE room_id_num = ?;";
    $stmt = mysqli_stmt_init($db);
    if(!mysqli_stmt_prepare($stmt, $sql)){
		//TO DO return some error;
		error_log(print_r($id, true));
    }
    else{
        mysqli_stmt_bind_param($stmt,"i", $id);
        mysqli_stmt_execute($stmt);
        $data = array();
        $result = mysqli_stmt_get_result($stmt);
        while(($row = mysqli_fetch_assoc($result))){
            $new_start =  new DateTime($row['start_date']);
            $new_end = new DateTime($row['end_date']);
            if($new_start >= $start && $new_end <= $end){
				if( $row['multimedia']==1){
					$multimedia='+техника';
				}else{
				$multimedia='без техника';
				}

                $data[]=[
					"id" => $row['type_id'],
					"real_id" => $row['event_id'],
                    "title" => $row['title'],
                    "start" => $new_start,
                    "end" => $new_end,
                    "organizer" => $row['organizer'],
                    "multimedia" => $multimedia,
                    "user" => $row['creator_name'],
                    "phone" => $row['telephone'],
                    "email" => $row['email'],
					"other" => $row['description'],
					"creation_time" => $row['creation_time']];
                }
            }
        }
        return $data;
}
    
function send_email($address,$event_id){
    //Load required files from PHPMailer library
    require 'vendor/autoload.php'; 

    //Settings for sending email
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->SMTPDebug  = 0;  
    $mail->SMTPSecure = "tls";
    $mail->Mailer = "smtp";
    $mail->Host= "smtp.gmail.com";
    $mail->SMTPAuth   = true;
    //Username and password for the project's email                                   
    $mail->Username   = 'nbuHallReservation';                     
    $mail->Password   = '5bd4fr3VitaN';                               
    $mail->SMTPSecure = 'ssl';         
    $mail->Port       = 465;

    $link="https://localhost:8080/cancel_event?id=$event_id";

    $subject= "Направена заявка за използване на зала в библиотека на НБУ";
    $message= "Вие успешно направихте резервация за използване на зала в библиотеката на НБУ.
    Това съобщение е автоматично генерирано, моля не отговаряйте на него.
    Натиснете на линка ако искате да се откажете резервацията:
    $link";
    
    $mail->setFrom('nbuHallReservation@gmail.com', "Don't reply");
    $mail->addAddress($address);
    $mail->Subject = $subject;
    $mail->Body =$message;
    $mail->send();
}

# $end is not inclusive
function read_calendar_events($id, $start, $end)
{
    return requests($id,$start,$end);
}
//delete events binded to rooms in order to free room(cancel requests)
function delete_reservation($event_id, $room_id){
    require 'database.php';
	$sql = "DELETE FROM events WHERE event_id=$event_id AND room_id_num = $room_id;";
    $stmt = mysqli_stmt_init($db);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //TO DO return some error;
    }

    else{
//         mysqli_stmt_bind_param($stmt,"i", $id);
        mysqli_stmt_execute($stmt);
//         $count = 0;
//         $result = mysqli_stmt_get_result($stmt);
//         while(($row = mysqli_fetch_assoc($result))){
//             $new_start =  new DateTime($row['start_date']);
//             $new_end = new DateTime($row['end_date']);
//             if($new_start >= $start && $new_end <= $end)
// {
//                 $data[]=[   
//                     "id" => $row['type_id'],
//                     "title" => $row['title'],
//                     "start" => $new_start,
//                     "end" => $new_end];
//                 $count++;
//                 }
//             }
        }
//         return $data;
    }

function get_events_sorted($field)
{   //Load database
    require_once "database.php";

    //SQL querry
    $search_id_result = $db->query("SELECT * FROM events ORDER BY $field;");
   
$stmt = mysqli_stmt_init($db);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //TO DO return some error;
    }

    else{
        mysqli_stmt_bind_param($stmt,"i", $id);
        mysqli_stmt_execute($stmt);
        $data = array();
        $count = 0;
        $result = mysqli_stmt_get_result($stmt);
        while(($row = mysqli_fetch_assoc($result))){
            $new_start =  new DateTime($row['start_date']);
            $new_end = new DateTime($row['end_date']);
            if($new_start >= $start && $new_end <= $end)
{
                $data[]=[   
                    "id" => $row['type_id'],
                    "title" => $row['title'],
                    "start" => $new_start,
                    "end" => $new_end];
                $count++;
                }
            }
        }
        return $data;
    }

