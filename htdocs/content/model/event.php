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
    //Check for existing event
    $find_event=$db->query("SELECT * FROM events WHERE type_id=$event[id] AND start_date=$event[old][start] AND end_date=$event[old][end]");
    if(mysqli_num_rows($find_event)!=0){
        //Check for free time slot
        $find_time_slot=$db->query("SELECT event_id FROM events WHERE $event[new][start]>=start_date AND $event[new][end]<=end_date");
        if(mysqli_num_rows($db)==0){
            //Update the event if timeslot if free
            $update=$db->query("UPDATE events SET title='$event[title]',description='$events[description]',start_date=$event[new][start],end_date=$event[new][end] WHERE type_id=$event[id] AND start_date=$event[old][start] AND end_date=$event[old][end]");
            if(mysqli_affected_rows($update) >0){
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
	}else{ //If the starting day is after the repeating day stay in the current week
		$diff=abs($start_day-$repeat_day);
	}
	
	$string= "+$diff days";
	$next_date= date('Y/m/d',strtotime($start_date. $string));
	
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

	//TODO: adds Missings columns
	$create_new_event="INSERT INTO events
	(title, description, start_date, end_date, room_id_num,creator_name, email,telephone,ip, creation_time) VALUES
	('$event[title]', '$event[description]', '$start', '$end', $event[room_id], '$event[creator]', '$event[email]', $event[phone_number], '$ip','$time')";

	//If the event is create successfully
	if($db->query($create_new_event)===TRUE){
		//Find its id and set value to type_id which is used it case of repeating events
		$id=find_event_id($start,$end,$event['room_id']);
		$update_type_id=$db->query("UPDATE events SET type_id = $id WHERE event_id=$id");
		return true;
	}else{
		return false;
	}
}

function create_new_repeating_event($event,$start,$end){
	require "database.php";
	$ip=find_ip();
	$time = date("Y/m/d H:i:s");

	//TODO: adds Missings columns
	$create_new_event="INSERT INTO events
	(title, description, start_date, end_date, room_id_num,type_id,creator_name, email,telephone,ip, creation_time) VALUES
	('$event[title]', '$event[description]', '$start', '$end', $event[room_id], $event[id], '$event[creator]', '$event[email]', $event[phone_number], '$ip','$time')";

	if($db->query($create_new_event)===TRUE){
		return true;
	}else{
		return false;
	}
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
//$repeat is as follows{
//	weekly - only if selected
//	monthly	- only if selected
//	count - int range from 1 to 4
//	start_repeat_day - string format Y/m/s The day from which the event repeats
//	days{
//	0 -> selected day ex. Mon,Tue,Wed,Thu,Fri,Sat or Sun
//	...
// 	6 -> selected day ex. Mon,Tue,Wed,Thu,Fri,Sat or Sun
//}
//}
function new_event($event,$repeat=array()){
	require_once "database.php";
	//Check for existing event
	$start_date=$event['start_date'].' '.$event['start_hour'];
	$end_date=$event['start_date'].' '.$event['end_hour'];
	if(!check_time_slot($start_date,$end_date,$event['room_id'])){
		return false;
	}

	//Create the event
	create_new_event($event,$start_date,$end_date);
	//Save the event id for use in repeating events because the fullcalendar API used same id for repeating events
	$event['id']=find_event_id($start_date,$end_date,$event['room_id']);

	var_dump($repeat);

	//Check if user has set repeating events
	if(!empty($repeat)){
		//The date from which all of the repeating events start
		$start_date = $repeat['start_repeat_day'];
		//If the repeating is set to weekly the event can repeat at most 4 times at any given days
		if(!empty($repeat['weekly'])&& $repeat['weekly']==true){
			//Starts the counter for the number of repeats
			for($count=0;$count<$repeat['count'];++$count){
				//Cycle for each of the selected days to repeat
				foreach($repeat['days'] as $day){
					//Finds the date for the next day set
					$next_date=find_next_day($day,$start_date);
					
					//Adds the hour to the date to be inserted into the database
					$next_start=$next_date.' '.$event['start_hour'];
					$next_end=$next_date.' '.$event['end_hour'];

					//Checks from the database if the time slot is free
					if(check_time_slot($next_start,$next_end,$event['room_id'])){
						create_new_repeating_event($event,$next_start,$next_end);
					}	
				}
				//After for all of the selected days are set to be repeated
				//Advance the starting repeating date by 7 days
				$start_date= date('Y/m/d',strtotime($start_date. "+7 days"));

			}
		//If the repeating is set to monthly 
		}else if($repeat['monthly']==true && !empty($repeat['monthly'])){
			//Advance the date by 1 month
			$next_date= date('Y/m/d',strtotime($start_date. "+1 months"));
			//Cycle for each of the selected days to repeat
			foreach($repeat['days'] as $day){
				$next_date=find_next_day($day,$next_date);
				
				//Adds the hour to the date to be inserted into the database
				$next_start=$next_date.' '.$event['start_hour'];
				$next_end=$next_date.' '.$event['end_hour'];

				//Checks from the database if the time slot is free
				if(check_time_slot($next_start,$next_end,$event['room_id'])){
					create_new_repeating_event($event,$next_start,$next_end);
				}
			}
		}
	}

	return true;
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
    $found = array();
    
    $sql = "SELECT * FROM events WHERE title = ?;";
    $stmt = mysqli_stmt_init($db);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        //TO DO SOME ERROR
    }
    
    else{
        mysqli_stmt_bind_param($stmt, "s", $title);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $count = 0;
        while($row = mysqli_fetch_assoc($result)){
            $found[] = $row;
            $count++;
        }
    }
    
    return $found;
}

function requests($id,$start,$end){
    require 'database.php';
    $sql = "SELECT * FROM events WHERE room_id_num = ?;";
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
            if($new_start >= $start && $new_end <= $end){
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
    
function send_email($address,$event_id){
    //Load required files from PHPMailer library
    require 'vendor/autoload.php'; 
    
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

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

    $link="https://localhost:8080/cancel_event?id=$id";

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
    $sql = "DELETE * FROM events WHERE event_id=$event_id AND room_id_num = $room_id;";
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

