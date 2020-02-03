function open_new_event(info) {
    var new_event_url = '/new_event?room_id=' + room_id;
    if (info) {
        new_event_url +=
                '&start=' + encodeURIComponent(info.startStr) +
                '&end=' + encodeURIComponent(info.endStr);
    }
    open_ajax_popup(new_event_url);
}

function open_event_repeat() {
    var event_date = $('#date')[0];
    var repeat_event_url = '/event_repeat?'
            + 'repeat_start=' + encodeURIComponent(event_date.value)
            + '&min_date=' + encodeURIComponent(event_date.min);
    open_ajax_popup(repeat_event_url);
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

function submit_repeat() {
    // collect all values
    var repeat_type = $('#repeat_form select[name=repeat]').val();
    var weekly_repeat = $('#repeat_form input[name=weekly_repeat]').val();
    var repeat_start = $('#repeat_form input[name=repeat_start]').val();
    var repeat_end = $('#repeat_form select[name=repeat_end]').val();
    var repeat_end_count = $('#repeat_form input[name=repeat_end_count]').val();
    var repeat_end_date = $('#repeat_form input[name=repeat_end_date]').val();

    // collect the days into an array
    var repeat_day = [];
    $("#repeat_form input:checkbox[name=repeat_day]:checked").each(function(){
        repeat_day.push($(this).val());
    });

    // TODO: validate the form

    // now fill in the data into the original form
    $('#event_form input[name=repeat]').val(repeat_type);
    $('#event_form input[name=weekly_repeat]').val(weekly_repeat);
    $('#event_form input[name=repeat_day]').val(repeat_day.join(' '));
    $('#event_form input[name=repeat_start]').val(repeat_start);
    $('#event_form input[name=repeat_end]').val(repeat_end);
    $('#event_form input[name=repeat_end_count]').val(repeat_end_count);
    $('#event_form input[name=repeat_end_date]').val(repeat_end_date);
    close_popup();
}
