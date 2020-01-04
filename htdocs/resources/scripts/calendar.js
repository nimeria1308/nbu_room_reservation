function calendar_ready_callback(calendar) {

    var options = {
        'locale': 'bg',
        'header': {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        'nowIndicator': true,
        'navLinks': true,           // can click day/week names to navigate views
        'eventLimit': true,         // allow "more" link when too many events
        'editable': true
    };

    Object.keys(options).forEach(function (key) {
        var value = options[key];
        calendar.setOption(key, value);
    });

    calendar.render();

    global_calendar = calendar;
}
