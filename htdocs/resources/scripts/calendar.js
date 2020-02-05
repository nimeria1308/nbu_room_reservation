function calendar_ready_callback(calendar) {

    var locale = cookie.get('locale', 'bg');
    var is_admin = cookie.get('is_admin', false);

    // Работно време на библиотеката
    var business_hours = [
        {
            // Weekdays
            daysOfWeek: [1, 2, 3, 4, 5],
            startTime: '08:00',
            endTime: '21:00'
        },
        {
            // Weekends
            daysOfWeek: [6, 0],
            startTime: '09:00',
            endTime: '17:30'
        }
    ];

    var buttons = {
        'new_reservation': {
            'text': {
                'bg': 'нова резервация',
                'en': 'new booking'
            }[locale],
            'click': function () {
                open_new_event();
            }
        }
    };

    if (is_admin) {
        // add those buttons only if in admin mode
        buttons['events'] = {
            'text': {
                'bg': 'заявки',
                'en': 'events'
            }[locale],
            'click': function () {
                location.href = '/rooms/' + room_id + '/events';
            }
        };

        buttons['edit_room'] = {
            'text': {
                'bg': 'редактирай',
                'en': 'edit'
            }[locale],
            'click': function () {
                open_ajax_popup('/edit_room?room_id=' + room_id);
            }
        };

        buttons['delete_room'] = {
            'text': {
                'bg': 'изтрии',
                'en': 'delete'
            }[locale],
            'click': delete_room
        };
    }

    var options = {
        'locale': locale,
        'customButtons': buttons,
        'header': {
            left: 'prev,next today new_reservation edit_room delete_room events',
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
        'businessHours': business_hours,
        'minTime': '08:00:00',
        'maxTime': '21:00:00',
        'eventConstraint': business_hours,
        'selectConstraint': business_hours,
        'selectMirror': true,       // this make selections look like adding new events
        'selectOverlap': false,     // cannot select through existing events
        'editable': is_admin
    };

    Object.keys(options).forEach(function (key) {
        var value = options[key];
        calendar.setOption(key, value);
    });

    calendar.setOption('eventAllow', function(info) {
        // do not allow moving events into the past
        return info.start >= new Date();
    });

    calendar.setOption('selectAllow', function (info) {
        // only allow selections on time grid
        // and ones that are not in the past
        return calendar.view.type.startsWith("timeGrid")
                && info.start >= new Date();
    });

    calendar.on('select', function (info) {
        open_new_event(info);
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

    calendar.on('eventClick', function(info) {
        open_show_event(room_id, info.event.id);
    });

    calendar.addEventSource('/content/services/events.php?room_id=' + room_id);

    calendar.render();

    global_calendar = calendar;
}

function delete_room() {
    if (confirm("Сигурни ли сте, че желаете да изтриете календара на залата заедно с всичките му заявки?") &&
            confirm("Моля потвърдете отново, че искате да изтриете календара")) {
        location.replace('/delete_room?room_id=' + room_id);
    }
}
