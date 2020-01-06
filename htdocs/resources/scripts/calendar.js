function calendar_ready_callback(calendar) {

    var locale = cookie.get('locale', 'bg');
    var is_admin = cookie.get('is_admin', false);

    function new_event(info) {
        if (info) {
            var instance = $.fancybox.open({
                'src': '/content/pages/new_event.php?room_id=' + room_id
                        + '&start=' + encodeURIComponent(info.startStr)
                        + '&end=' + encodeURIComponent(info.endStr),
                'type': 'ajax'
            });
        } else {
            var instance = $.fancybox.open({
                'src': '/content/pages/new_event.php?room_id=' + room_id,
                'type': 'ajax'
            });
        }
    }

    var buttons = {
        'new_reservation': {
            'text': {
                'bg': 'нова резервация',
                'en': 'new booking'
            }[locale],
            'click': function () {
                new_event();
            }
        }
    };

    if (is_admin) {
        buttons['events'] = {
            'text': {
                'bg': 'заявки',
                'en': 'events'
            }[locale],
            'click': function () {
                location.href = '/rooms/' + room_id + '/events';
            }
        };
    }

    var options = {
        'locale': locale,
        'customButtons': buttons,
        'header': {
            left: 'prev,next today new_reservation events',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        'eventColor': room_color,   // event color for the current room
        'height': 'auto',           // don't want any scrollbars
        'nowIndicator': true,       // a line indicator for time of day
        'navLinks': true,           // can click day/week names to navigate views
        'eventLimit': true,         // allow "more" link when too many events
        'eventOverlap': false,      // reservations cannot overlap
        'selectable': true,         // can be selected to add a new event
        'allDaySlot': false,        // not displaying allday
        'minTime': '07:00:00',      // start at 7
        'maxTime': '21:00:00',      // close by 9
        'eventConstraint': {
            'start': new Date()     // cannot move events to the past
        },
        'selectConstraint': {
            'start': new Date()     // cannot create events in the past
        },
        'selectMirror': true,       // this make selections look like adding new events
        'selectOverlap': false,     // cannot select through existing events
        'editable': is_admin
    };

    Object.keys(options).forEach(function (key) {
        var value = options[key];
        calendar.setOption(key, value);
    });

    calendar.setOption('selectAllow', function(info) {
        return calendar.view.type.startsWith("timeGrid");
    });

    calendar.on('select', function (info) {
        new_event(info);
        calendar.unselect();
    });

    function updateEvent(info, event_data) {
        function fail(resp) {
            alert('Неуспешно обновяване на събитието');
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

    calendar.addEventSource('/content/services/events.php?room_id=' + room_id);

    calendar.render();

    global_calendar = calendar;
}
