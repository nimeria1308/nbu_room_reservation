<?php
require_once('model/event.php');

# Parse the event from the string provided by the frontend
function read_event_from_request($event)
{
    # Decode from JSON
    $event = json_decode($event);

    $dates = [
        &$event->old->start,
        &$event->old->end,
        &$event->new->start,
        &$event->new->end,
    ];

    $tz = new DateTimeZone(date_default_timezone_get());
    foreach ($dates as &$d) {
        # Note that dates from JSON.stringify() are in RFC3339_EXTENDED, rather than ISO8601
        $d = DateTime::createFromFormat(DateTime::RFC3339_EXTENDED, $d);
        $d->setTimezone($tz);
    }

    return $event;
}

$event = read_event_from_request($_POST['event']);
$update_status = update_calendar_event($event);

header("Content-Type: application/json; charset=UTF-8");
echo json_encode(['status' => ($update_status ? 'ok' : 'fail')]);
