function open_new_event(info) {
    var new_event_url = '/new_event?room_id=' + room_id;
    if (info) {
        new_event_url +=
                '&start=' + encodeURIComponent(info.startStr) +
                '&end=' + encodeURIComponent(info.endStr);
    }
    open_ajax_popup(new_event_url);
}

function on_repeat_change(el) {
    var repeat_value = el.value;
    var repeat_details = $('#repeat_details');
    var weekly_repeat = $('#weekly_repeat');

    // hide all elements
    repeat_details.hide();
    weekly_repeat.hide();

    switch (repeat_value) {
        case 'never':
            // nothing to show
            break;
        case 'weekly':
            repeat_details.show();
            weekly_repeat.show();
            break;
        case 'monthly':
            repeat_details.show();
            break;
        default:
            alert('unknown repeat value ' + repeat_value);
            break;
    }
}

function on_repeat_end_change(r) {
    var end_type = r.value;

    var repeat_end_count = $('#repeat_end_count');
    var repeat_end_date = $('#repeat_end_date');

    // first hide all elements
    repeat_end_count.hide();
    repeat_end_date.hide();

    // now show them selectively
    switch (end_type) {
        case 'never':
            // nothing to show
            break;
        case 'count':
            repeat_end_count.show();
            break;
        case 'date':
            repeat_end_date.show();
            break;
        default:
            alert('unknown repeat end type ' + end_type);
            break;
    }
}

function on_event_submit() {
    // get the form data
    var form_elements = $('#event_form').serializeArray();

    // now prepare the elements for the AJAX post
    var post_data = { };
    form_elements.forEach(function(item) {
        if (item['name'] in post_data) {
            // append
            post_data[item['name']] += ' ' + item['value'];
        } else {
            post_data[item['name']] = item['value'];
        }
    });

    function request_failure() {
        $.fancybox.open('Сървърна грешка');
    }

    function bad_input_data(data) {
        $.fancybox.open('<h2>Грешка</h2><p>' + data.error + '</p>');
    }

    $.post("/content/services/new_event.php", post_data)
        .done(function (data) {
            if (data.status != "ok") {
                bad_input_data(data);
                return;
            }

            // update the events
            global_calendar.refetchEvents();

            close_popup();
        })
        .fail(request_failure);
}

function select_event(ch) {
    var tr = $(ch).closest('tr');

    if (ch.checked) {
        tr.addClass('selected');
    } else {
        tr.removeClass('selected');
    }
}

function confirm_delete_events() {
    // get a list of all checked events
    var all_checked = $('#events_form input:checkbox[name=event\\[\\]]:checked');

    // require confirm or cancel if none were checked
    return !!all_checked.length && confirm("Сигурни ли сте, че желаете да изтриете избраните заявки?");
}

function confirm_delete_event() {
    return confirm("Сигурни ли сте, че желаете да изтриете тази заявка?");
}