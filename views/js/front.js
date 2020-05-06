let CubacelFront = function() {
    let idProduct;

    let validatePhone = function(number) {
        var regex = /^(52|53|54|55|57|56|58|59)([0-9]{6})$/;
        return regex.test(number);
    }

    let validateEmail = function(email) {
        var regex = /^\w+([\.-]?\w+)*@nauta.com.cu$/;
        return regex.test(email);
    }

    let saveCustomizedData = function(field) {
        var formActionAttribute_url = $("form#customization-" + idProduct).attr('action');
        var data = {};
        data['id'] = idProduct;
        data['textField'] = $(field).val();
        data['submitCustomizedData'] = $('#textField-' + idProduct).val();
        data['ajax'] = 1;
        $.post(formActionAttribute_url, data, function(response) {
            response = JSON.parse(response)
            if (response.status == 'success') {
                $('#product_customization_id-' + idProduct).val(response.id_customization);
                $('.add-' + idProduct).trigger('click');
            } else {
                alert(response.msg);
            }
        });
        return false;
    }

    let addToCart = function() {
        $('.add-custom').click(function(e) {
            e.preventDefault();
            idProduct = $(this).attr('data-id');
            let phone = $('#phone-' + idProduct);
            let email = $('#email-' + idProduct);
            if (phone.length > 0 && phone.val() && validatePhone(phone.val())) {
                saveCustomizedData(phone);
            }
            if (email.length > 0 && email.val() && validateEmail(email.val())) {
                saveCustomizedData(email);
            }
            return;
        });
    }

    return {
        init: function() {
            addToCart();
        },
    };

}();

jQuery(document).ready(function() {
    CubacelFront.init();
});