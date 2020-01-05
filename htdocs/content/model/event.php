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
        ],
        # room 1
        1 => [
            [
                "id" => 1,
                "title" => 'XRepeating Event',
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
        ]
    ];

    return $events[$id];
}
