let CubacelFront = function() {
    let idProduct;
    let alertBox = $('.alert_notice').first().clone();
    let alertDefaultHTML = alertBox.html("");

    //Make a copy of alert container for this module notifications
    $('.alert_notice').first().parent().append(alertBox);

    /**
     * Validate cubacel phone number
     * @param string phone
     * @returns boolean
     */
    let validatePhone = function(phone) {
        var regex = /^(52|53|54|55|57|56|58|59)([0-9]{6})$/;
        return regex.test(phone);
    }

    /**
     * Validate email format
     * @param string email
     * @returns boolean
     */
    let validateEmail = function(email) {
        var regex = /^\w+([\.-]?\w+)*@nauta.com.cu$/;
        return regex.test(email);
    }

    /**
     * Make requesto for creating customization field
     * 
     * @param object field 
     * @returns void
     */

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
                showAlertMessage(response.msg, 'danger');
            }
        });
        return;
    }

    /**
     * Add to cart custom button 
     * @returns void
     */
    let addToCart = function() {
        $('a.add-custom').click(function(e) {
            e.preventDefault();
            idProduct = $(this).data('id');
            let phone = $('#phone-' + idProduct);
            let email = $('#email-' + idProduct);
            let isValidAccount = false;
            if (phone.length > 0 && phone.val() && validatePhone(phone.val())) {
                saveCustomizedData(phone);
                isValidAccount = true;
            }
            if (email.length > 0 && email.val() && validateEmail(email.val())) {
                saveCustomizedData(email);
                isValidAccount = true;

            } else if (!isValidAccount) {
                let message = phone.length ? "<i class='fa fa-phone'></i>Revise que el número de móvil tenga <b>8 dígitos</b>" :
                    email.length ? "<i class='fa fa-envelope'></i>La cuenta de internet debe terminar en <b>@nauta.com.cu</b>" :
                    "<i class='fa fa-info'></i> Formato no válido";
                showAlertMessage(message, 'danger');
            }
        });
    }

    /**
     * Show templante alert in a wrong case  
     * @param string message 
     * @param string type {'info', 'danger', 'warning'}
     */
    let showAlertMessage = function(message, type) {
        let content = `<p class="alert_no_item alert alert-${type}">${message}</p>`;
        alertBox.html(content).addClass('active');

        setTimeout(function() {
            alertBox.removeClass('active').html(alertDefaultHTML);
        }, 3000)
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