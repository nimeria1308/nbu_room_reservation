function calendar_ready_callback(calendar) {

    var locale = 'bg';

    var options = {
        'locale': locale,
        'customButtons': {
            'new_reservation': {
                'text': {
                    'bg': 'нова резервация',
                    'en': 'new booking'
                }[locale],
                'click': function () {
                    alert('clicked the custom button!');
                }
            }
        },
        'header': {
            left: 'prev,next today new_reservation',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        'height': 'auto',           // don't want any scrollbars
        'nowIndicator': true,       // a line indicator for time of day
        'navLinks': true,           // can click day/week names to navigate views
        'eventLimit': true,         // allow "more" link when too many events
        'eventOverlap': false,      // reservations cannot overlap
        'selectable': true,         // can be selected
        'allDaySlot': false,        // not displaying allday
        'eventConstraint': {
            'start': new Date(),    // cannot move events to the past
        },
        'editable': true
    };

    Object.keys(options).forEach(function (key) {
        var value = options[key];
        calendar.setOption(key, value);
    });

    calendar.on('select', function (info) {
        alert('select');
        console.log(info);
    });

    function updateEvent(info, event_data) {
        function fail(resp) {
            alert('Failed updating the event');
            info.revert();
            console.log(resp);
        }

        $.post("/content/services/edit_event.php", {
            'event': JSON.stringify(event_data)
        }).done(function (data) {
            if (data.status != "ok") {
                fail(data);
            }
        }).fail(fail);
    }

    // handlers for drop/resize,
    // so that the changed event info can be send to the back end
    calendar.on('eventDrop', function (info) {
        var event_data = {
            'id': info.event.id,
            'startDelta': info.delta, // as it is being moved, not resized, both are the same
            'endDelta': info.delta,
            'old': {
                'start': info.oldEvent.start,
                'end': info.oldEvent.end
            },
            'new': {
                'start': info.event.start,
                'end': info.event.end
            }
        };

        updateEvent(info, event_data);
    });

    calendar.on('eventResize', function (info) {
        var event_data = {
            'id': info.event.id,
            'startDelta': info.startDelta,
            'endDelta': info.endDelta,
            'old': {
                'start': info.prevEvent.start,
                'end': info.prevEvent.end
            },
            'new': {
                'start': info.event.start,
                'end': info.event.end
            }
        };

        updateEvent(info, event_data);
    });

    calendar.render();

    global_calendar = calendar;
}
