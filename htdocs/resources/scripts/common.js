function unset_all_cookies() {
    // for debug purposes only
    var cookie_names = Object.keys(cookie.all());
    cookie.remove(cookie_names);
    cookie.removeSpecific(cookie_names, { 'path': '/' });
}

console.log(cookie.all());
// unset_all_cookies();

document.addEventListener('DOMContentLoaded', function () {
    var form = $("#login_form");

    form.submit(function (e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var method = form.attr('method');
        var url = form.attr('action');

        $.post(url, form.serialize())
            .done(function (data) {
                if (data.status != "ok") {
                    alert('Грешно име или парола');
                    return;
                }
                $.fancybox.getInstance().close();
                setTimeout(function () {
                    location.reload(true);
                }, 1000);
            }).fail(function (resp) {
                alert("Сървърна грешка");
            });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var logout = $("#logout");
    console.log(logout);

    // submitting to login without credentials will log us out.
    var url = '/login';

    logout.on("click", function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        $.post(url)
            .done(function (data) {
                location.reload(true);
            }).fail(function (resp) {
                alert("Сървърна грешка");
            });
    });
});
