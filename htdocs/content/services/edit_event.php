<?php

# Parse the event from the string provided by the frontend
# The resulting event is as follows
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
function read_event($event)
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

# returns true on successfully updating the event
# otherwise false, which will revert it in the frontend
function update_event_on_backend($event)
{
    // error_log(print_r($event, true));

    # TODO

    return true;
}

$event = read_event($_POST['event']);
$update_status = update_event_on_backend($event);

header("Content-Type: application/json; charset=UTF-8");
echo json_encode(['status' => ($update_status ? 'ok' : 'fail')]);
