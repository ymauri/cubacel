let CubacelFront = function() {
    let idProduct;

    let validatePhone = function(number) {
        if (number.length != 8) {
            alert("El numero de movil debe tener 8 digitos");
            return false;
        }
        if (number.substr(0, 2) != 52 && number.substr(0, 2) != 53 && number.substr(0, 2) != 54 && number.substr(0, 2) != 55 && number.substr(0, 2) != 56 && number.substr(0, 2) != 58 && number.substr(0, 2) != 59) {
            alert("El numero de movil debe empezar con 52, 53, 54, 55, 56 o 58");
            return false;
        }

        return true;
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
            if (phone.val() && validatePhone(phone.val())) {
                saveCustomizedData(phone);
            } else {
                alert('Error!')
            }
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