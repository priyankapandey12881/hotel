$(document).ready(function () {
    $('#search').keyup(function () {
        var searchValue = $(this).val().toLowerCase();
        $('.table-body tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1)
        });
    });
});