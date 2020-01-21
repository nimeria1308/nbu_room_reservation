<?php
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
    // error_log(print_r($event, true));

    # TODO: implement in backend

    return true;
}

function send_email($address,$event_id){
    require 'vendor/autoload.php'; 

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require '../PHP/PHPMailer/src/Exception.php';
    require '../PHP/PHPMailer/src/PHPMailer.php';
    require '../PHP/PHPMailer/src/SMTP.php';

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

    $link="localhost:8080/cancel_event?id=$event_id";
    $delete_link="<a href='https://<?php echo $link' >link</a>";

    $subject= "Направена заявка за използване на зала в библиотека на НБУ";
    $message= "Вие успешно направихте резервация за използване на зала в библиотеката на НБУ.
    Това съобщение е автоматично генерирано, моля не отговаряйте на него.
    Натиснете на линка ако искате да се откажете резервацията:
    $delete_link";
    mail($address,$subject,$message);
}

# $end is not inclusive
function read_calendar_events($id, $start, $end)
{
    # TODO: implement in backend
    // $start_string = $start->format(DateTime::ISO8601);
    // $end_string = $end->format(DateTime::ISO8601);
    // error_log("from: $start_string to: $end_string");

    # Note: ID for all instances of a recurring event stays the same.
    $events = [
        # room 0
        0 => [
            [
                "id" => 1,
                "title" => 'Семинар към курс по Python',
                "start" => new DateTime("monday last week 14:00"),
                "end" => new DateTime("monday last week 17:00"),
            ],
            [
                "id" => 1,
                "title" => 'Семинар към курс по Python',
                "start" => new DateTime("monday this week 14:00"),
                "end" => new DateTime("monday this week 17:00"),
            ],
            [
                "id" => 1,
                "title" => 'Семинар към курс по Python',
                "start" => new DateTime("monday next week 14:00"),
                "end" => new DateTime("monday next week 17:00"),
            ],
            [
                "id" => 2,
                "title" => 'Извънреден семинар по киберсигурност',
                "start" => new DateTime("tuesday this week 9:30"),
                "end" => new DateTime("tuesday this week 14:45"),
            ],
            [
                "id" => 10,
                "title" => 'Семинар по древни езици',
                "start" => new DateTime("wednesday this week 11:00"),
                "end" => new DateTime("wednesday this week 19:00"),
            ],
        ],
        # room 1
        1 => [
            [
                "id" => 13,
                "title" => 'Колективно задание по PHP',
                "start" => new DateTime("thursday this week 10:00"),
                "end" => new DateTime("thursday this week 16:00"),
            ],
            [
                "id" => 19,
                "title" => 'Happy friday',
                "start" => new DateTime("friday last week 17:00"),
                "end" => new DateTime("friday last week 17:15"),
            ],
            [
                "id" => 19,
                "title" => 'Happy friday',
                "start" => new DateTime("friday this week 17:00"),
                "end" => new DateTime("friday this week 17:15"),
            ],
            [
                "id" => 19,
                "title" => 'Happy friday',
                "start" => new DateTime("friday next week 17:00"),
                "end" => new DateTime("friday next week 17:15"),
            ],
        ],
    ];

    return $events[$id];
}
