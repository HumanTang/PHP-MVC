$(document).ready(function () {
    console.log("loaded");

    $('#saveAnswerBtn').on('click', function () {
        // AJAX request to form.php
        var value = $("#newAnswer").val().trim();
        var index = $(".answers-cards").length + 1;
        $.ajax({
            url: 'http://127.0.0.1:8080/checkbox',
            type: 'POST',
            data: {answer: value, index: index},
            dataType: 'html',
            success: function (response) {
                // Append the received HTML to the form
                $('#dynamicElementsContainer').append(response);
            },
            error: function () {
                console.log('Error in AJAX request');
            }
        });
    });
});

