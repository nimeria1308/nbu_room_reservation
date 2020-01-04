<?php
require_once('libraries/isadmin.php');

# This implements the API required by full calendar
# See: https://fullcalendar.io/docs/events-json-feed

$events_start = new DateTime($_GET['start']);
$events_end = new DateTime($_GET['end']);

# $end is not inclusive
function read_events_from_backend($start, $end)
{

    # TODO: move this to backend
    $start_string = $start->format(DateTime::ISO8601);
    $end_string = $end->format(DateTime::ISO8601);
    // error_log("from: $start_string to: $end_string");

    # Note: ID for all instances of a recurring event stays the same.
    return [
        [
            "id" => 1,
            "title" => 'Repeating Event',
            "start" => new DateTime("monday this week 14:00"),
            "end" => new DateTime("monday this week 17:00"),
        ],
        [
            "id" => 1,
            "title" => 'Repeating Event',
            "start" => new DateTime("monday next week 14:00"),
            "end" => new DateTime("monday next week 17:00"),
        ],
        [
            "id" => 1,
            "title" => 'Repeating Event',
            "start" => new DateTime("tuesday this week 16:00"),
            "end" => new DateTime("tuesday this week 19:00"),
        ],
    ];
}

$events = read_events_from_backend($events_start, $events_end);

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
