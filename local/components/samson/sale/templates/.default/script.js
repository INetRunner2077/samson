$(window).on('load', function() {
    $(".give__sales").submit(function (e) {
        e.preventDefault();
        BX.ajax.runComponentAction("samson:sale", "getSales", {
            mode: "class",
            data: {
                "USER_ID": $('input[name="USER_ID"]').val(),
                "LOGIN": $('input[name="LOGIN"]').val(),
            }
        }).then(function (response) {
            $('.give__sales label').remove();
            var giveForm = $('.give__sales');

            giveForm.append($('<label>', {
                text: response.data.RESULT.COUPON,
                class: 'give__label',
            }))
            giveForm.append($('<label>', {
                text: `Скидка ${response.data.RESULT.SALE}%`,
                class: 'give__label',
            }))
        });
    });

    $(".check__sales").submit(function (e) {
        e.preventDefault();
        BX.ajax.runComponentAction("samson:sale", "checkSale", {
            mode: "class",
            data: {
                "USER_ID": $('input[name="USER_ID"]').val(),
                "LOGIN": $('input[name="LOGIN"]').val(),
                "COUPON": e.target.elements.COUPON.value,

            }
        }).then(function (response) {
            $('.check__sales label').remove();
            var checkSales = $('.check__sales');
            if (typeof response.data.RESULT.SALE !== 'undefined') {

                checkSales.append($('<label>', {
                    text: `Скидка ${response.data.RESULT.SALE}%`,
                    class: 'give__label',
                }))
            } else {
                checkSales.append($('<label>', {
                    text: response.data.RESULT.ERROR,
                    class: 'give__label',
                }))
            }
        });
    });
});