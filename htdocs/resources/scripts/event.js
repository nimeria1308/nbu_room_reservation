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

function validate_event_form(elements) {
    // check that all terms are checked
    var terms_size = parseInt(elements['terms_size']);

    var terms = [ ]; // may be 0
    if ('terms' in elements) {
        terms = elements['terms'].split(' ');
    }

    if (terms.length != terms_size) {
        return "Моля, изберете всички полета за съгласие за употреба.";
    }

    function check_empty(name) {
        return !elements[name].trim();
    }

    if (check_empty('organizer')) {
        return "Моля, въведете организатор";
    }

    if (check_empty('name')) {
        return "Моля, въведете име на събитието";
    }

    if (check_empty('user')) {
        return "Моля, въведете лице за контакт";
    }

    if (check_empty('phone')) {
        return "Моля, въведете телефон";
    }

    if (check_empty('email')) {
        return "Моля, въведете електронна поща";
    }

    return true;
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

    // validate the form
    var validate_result = validate_event_form(post_data);
    if (validate_result !== true) {
        // show validation failure and bail out
        $.fancybox.open('<h2>Невалидни данни</h2><p>' + validate_result + '</p>');
        return;
    }

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
