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
    require_once "datebase.php";
    $find_event=$db->query("SELECT * FROM events WHERE event_id=$event[id]");
    if(mysqli_num_rows($find_event)!=0){
        $find_time_slot=$db->query("SELECT event_id FROM events WHERE $event[new][start]>=start_date AND $event[new][end]<=end_date");
        if(mysqli_num_rows($db)==0){
            $update=$db->query("UPDATE events SET title='$event[title]',description='$events[description]',start_date=$event[new][start],end_date=$event[new][end] WHERE events_id=$event[id]");
            if(mysqli_affected_rows($update) >0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    // error_log(print_r($event, true));
    return false;
}

function load_requests_button(){
    require 'database.php';
    
    $sql = "SELECT * FROM events";
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
    require 'vendor/autoload.php'; 
    
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->SMTPDebug  = 0;  
    $mail->SMTPSecure = "tls";
    $mail->Mailer = "smtp";
    $mail->Host= "smtp.gmail.com";
    $mail->SMTPAuth   = true;                                   
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
    $events = array();
    $events[] = requests($id,$start,$end);
   
    return $events[0];
}

