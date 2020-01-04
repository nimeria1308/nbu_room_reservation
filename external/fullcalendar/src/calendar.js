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
        events: '/content/services/events.php',
    });

    calendar_ready_callback(calendar);
});
