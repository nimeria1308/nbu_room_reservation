<?php
require_once('libraries/forceadmin.php');
require_once('model/event.php');

# This implements the API required by full calendar
# See: https://fullcalendar.io/docs/events-json-feed

$events_start = new DateTime($_GET['start']);
$events_end = new DateTime($_GET['end']);
$room_id = $_GET['room_id'];

$events = read_calendar_events($room_id, $events_start, $events_end);

# Convert the start/end datetimes to ISO8601,
# being the format fullcalendar expects
$events = array_map(function ($event) {
    return array_map(function ($v) {
        return ($v instanceof DateTime) ? $v->format(DateTime::ISO8601) : $v;
    }, $event);
}, $events);

# Finally, output the events in JSON
header("Content-Type: application/json; charset=UTF-8");
echo json_encode($events);
