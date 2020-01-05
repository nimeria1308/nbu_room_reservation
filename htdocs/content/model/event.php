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
