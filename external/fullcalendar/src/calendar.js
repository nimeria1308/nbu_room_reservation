import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import enLocale from '@fullcalendar/core/locales/en-gb'
import bgLocale from '@fullcalendar/core/locales/bg'

require('@fullcalendar/core/main.css');
require('@fullcalendar/daygrid/main.css');
require('@fullcalendar/timegrid/main.css');
require('@fullcalendar/list/main.css');

document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar(calendarEl, {
        plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
        locales: [bgLocale, enLocale],
        locale: bgLocale,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        nowIndicator: true,
        // defaultDate: '2018-01-12',
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: '/content/services/events.php', /*[
            {
                title: 'All Day Event',
                start: '2018-01-01',
            },
            {
                title: 'Long Event',
                start: '2018-01-07',
                end: '2018-01-10'
            },
            {
                id: 999,
                title: 'Repeating Event',
                start: '2018-01-09T16:00:00'
            },
            {
                id: 999,
                title: 'Repeating Event',
                start: '2018-01-16T16:00:00'
            },
            {
                title: 'recurring 2',
                startTime: '11:00:00',
                daysOfWeek: [ '2', '4' ],
                color: 'red'
            },
            {
                title: 'Conference',
                start: '2018-01-11',
                end: '2018-01-13'
            },
            {
                title: 'Meeting',
                start: '2018-01-12T10:30:00',
                end: '2018-01-12T12:30:00'
            },
            {
                title: 'Lunch',
                start: '2018-01-12T12:00:00'
            },
            {
                title: 'Meeting',
                start: '2018-01-12T14:30:00'
            },
            {
                title: 'Happy Hour',
                start: '2018-01-12T17:30:00'
            },
            {
                title: 'Dinner',
                start: '2018-01-12T20:00:00'
            },
            {
                title: 'Birthday Party',
                start: '2018-01-13T07:00:00'
            },
            {
                title: 'Click for Google',
                url: 'http://google.com/',
                start: '2018-01-28'
            }
        ]*/
    });

    calendar.render();
    window.calendar = calendar;
});
