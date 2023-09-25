(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    $(function () {

        // Originally inspired by  David Walsh (https://davidwalsh.name/javascript-debounce-function)
        // Returns a function, that, as long as it continues to be invoked, will not
        // be triggered. The function will be called after it stops being called for
        // `wait` milliseconds.
        const debounce = (func, wait) => {
            let timeout;

            // This is the function that is returned and will be executed many times
            // We spread (...args) to capture any number of parameters we want to pass
            return function executedFunction(...args) {

                // The callback function to be executed after
                // the debounce time has elapsed
                const later = () => {
                    // null timeout to indicate the debounce ended
                    timeout = null;

                    // Execute the callback
                    func(...args);
                };
                // This will reset the waiting every function execution.
                // This is the step that prevents the function from
                // being executed because it will never reach the
                // inside of the previous setTimeout
                clearTimeout(timeout);

                // Restart the debounce waiting period.
                // setTimeout returns a truthy value (it differs in web vs Node)
                timeout = setTimeout(later, wait);
            };
        };

        function cc_display_errors(message) {
            $('#cc-error').html(message);
            $('#cc-error').fadeIn();
        }

        let env = $("input:radio[name='connect_co_api_environment']:checked").val()

        $("input:radio[name='connect_co_api_environment']").on('change', function (){
           if($(this).val() == 'test'){
               $('.cc-test-api').show();
           }else{
               $('.cc-test-api').hide();
           }
        });

        let today = new Date();

        $("#cc_scheduled_date").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: today,
        });


        function cc_check_delivery_methods_availability() {
            let cc_city = $('#cc_city option:selected').val();
            let cc_delivery_type = $('#cc_delivery_type option:selected').val();
            // This does the ajax request
            $.ajax({
                url: connect_co_ajax.ajax_url,
                type: 'post',
                data: {
                    'action': 'check_delivery_methods_availability',
                    'ajax': true,
                    'nonce': connect_co_ajax.nonce,
                    'cc_city': cc_city,
                    'cc_delivery_type': cc_delivery_type,
                },
                dataType: "json",
                beforeSend: function () {
                    $('#cc-error').fadeOut();
                    $('#cc-success').fadeOut();
                    $('#connect-co-submit').attr('disabled', true);

                },
                success: function (response) {
                    if (response) {
                        if (response.status == 'error') {
                            cc_display_errors(response.message);
                            alert(response.message);
                            $('#cc_delivery_type').val(1);
                            $('.cc-delivery-date').hide();
                            $('.cc-time-window').hide();
                        }
                        if (response.status == 'success') {
                            $('#cc-error').fadeOut();
                        }
                        $('#connect-co-submit').attr('disabled', false);

                    } else {
                        cc_display_errors('Something went wrong. Please try again.');
                    }
                },
                error: function (errorThrown) {
                    cc_display_errors('Something went wrong. Please try again.');
                    console.log(errorThrown);
                }
            });
        }

        function cc_check_cash_on_delivery_availability() {
            let cc_city = $('#cc_city option:selected').val();

            // This does the ajax request
            $.ajax({
                url: connect_co_ajax.ajax_url,
                type: 'post',
                data: {
                    'action': 'check_cash_on_delivery_availability',
                    'ajax': true,
                    'nonce': connect_co_ajax.nonce,
                    'cc_city': cc_city,
                },
                dataType: "json",
                beforeSend: function () {
                    $('#cc-error').fadeOut();
                    $('#cc-success').fadeOut();
                    $('#connect-co-submit').attr('disabled', true);

                },
                success: function (response) {
                    if (response) {
                        if (response.status == 'error') {
                            cc_display_errors(response.message);
                            alert(response.message);
                            $('#cc_payment_type').val(1);
                        }
                        if (response.status == 'success') {
                            $('#cc-error').fadeOut();
                        }
                        $('#connect-co-submit').attr('disabled', false);

                    } else {
                        cc_display_errors('Something went wrong. Please try again.');
                    }
                },
                error: function (errorThrown) {
                    cc_display_errors('Something went wrong. Please try again.');
                    console.log(errorThrown);
                }
            });
        }

        //Connect Co Order Submit Function
        $("#connect-co-submit").click(function (e) {
            e.preventDefault();
            let confirmation = confirm("Submit order to Connect Co.?");
            if (confirmation) {
                let nonce = connect_co_ajax.nonce;
                let cc_pickup_location = $('#cc_pickup_location option:selected').val();
                let cc_payment_type = $('#cc_payment_type option:selected').val();
                let cc_delivery_type = $('#cc_delivery_type option:selected').val();
                let cc_package_weight = $('#cc_package_weight').val();
                let cc_package_size = $('#cc_package_size option:selected').val();
                let cc_notes = $('#cc_notes').val();
                let cc_scheduled_date = $('#cc_scheduled_date').val();
                let cc_time_window = $('#cc_time_window option:selected').val();
                let cc_city = $('#cc_city option:selected').val();
                let order_id = $('#cc_order_id').val();

                $.ajax({
                    url: connect_co_ajax.ajax_url,
                    type: 'post',
                    data: {
                        'action': 'submit_order_to_connect_co',
                        'ajax': true,
                        'nonce': nonce,
                        'cc_pickup_location': cc_pickup_location,
                        'cc_payment_type': cc_payment_type,
                        'cc_package_weight': cc_package_weight,
                        'cc_package_size': cc_package_size,
                        'cc_delivery_type': cc_delivery_type,
                        'cc_notes': cc_notes,
                        'cc_city': cc_city,
                        'cc_scheduled_date': cc_scheduled_date,
                        'cc_time_window': cc_time_window,
                        'order_id': order_id
                    },
                    dataType: "json",
                    beforeSend: function () {
                        $('#cc-error').fadeOut();
                        $('#cc-success').fadeOut();
                        $('#connect-co-submit').attr('disabled', true);
                    },
                    success: function (response) {
                        $('#connect-co-submit').attr('disabled', false);
                        if (response) {
                            if (response.status == 'error') {
                                cc_display_errors(response.message);
                            }
                            if (response.status == 'success') {
                                $('#cc-success').text(response.message);
                                $('#cc-success').fadeIn();
                                alert(response.message);
                                setTimeout(function() {
                                    location.reload();
                                }, 2000);

                            }
                        } else {
                            cc_display_errors('Something went wrong. Please try again.');
                        }
                    },
                    error: function (errorThrown) {
                        cc_display_errors('Something went wrong. Please try again.');
                        $('#connect-co-submit').attr('disabled', false);
                        console.log(errorThrown);
                    }
                });
            }
        });

        //Calculate Delivery Cost START
        $('#cc_package_weight').on('keyup', debounce(function () {
            // the following function will be executed every half second
            cc_calculate_delivery_cost();
        }, 500));

        //Connect CO Calculate Delivery Cost Function
        $('#cc_package_weight').on('change', function (e) {
           cc_calculate_delivery_cost();
        });

        function cc_calculate_delivery_cost() {
            let cc_city = $('#cc_city').val();
            let cc_delivery_type = $('#cc_delivery_type').val();
            let cc_package_weight = $('#cc_package_weight').val();

            // This does the ajax request
            $.ajax({
                url: connect_co_ajax.ajax_url,
                type: 'post',
                data: {
                    'action': 'calculate_connect_co_delivery_cost',
                    'ajax': true,
                    'nonce': connect_co_ajax.nonce,
                    'cc_city': cc_city,
                    'cc_delivery_type': cc_delivery_type,
                    'cc_package_weight': cc_package_weight,
                },
                dataType: "json",
                beforeSend: function () {
                    $('#cc-error').fadeOut();
                    $('#cc-success').fadeOut();
                },
                success: function (response) {
                    if (response) {
                        if (response.status == 'error') {
                            cc_display_errors(response.message);
                        }
                        if (response.status == 'success') {
                            $('#cc-delivery-cost').fadeOut();
                            $('#cc-delivery-cost').text(response.message);
                            $('#cc-delivery-cost').fadeIn();
                        }
                    } else {
                        cc_display_errors('Something went wrong. Please try again.');
                    }
                },
                error: function (errorThrown) {
                    cc_display_errors('Something went wrong. Please try again.');
                    console.log(errorThrown);
                }
            });
        }

        $('.connect-co-container').ready(function () {
           cc_calculate_delivery_cost();
        });
        //Calculate Delivery Cost END


        $('#cc_delivery_type').on('change', function () {
            let cc_delivery_type =  $('#cc_delivery_type option:selected').val();
            if (cc_delivery_type == 3) { //scheduled
                $('.cc-delivery-date').show();
                $('.cc-time-window').show();
                cc_check_delivery_methods_availability();
            } else {
                $('.cc-delivery-date').hide();
                $('.cc-time-window').hide();
                if (cc_delivery_type == 2) { //same-day
                      cc_check_delivery_methods_availability();
                    $('.cc-delivery-date').show();
                }
            }
            cc_calculate_delivery_cost();
        });

        $('#cc_payment_type').on('change', function () {
            let  cc_payment_type =  $('#cc_payment_type option:selected').val();;
            if(cc_payment_type == 2) { //cash on delivery
                cc_check_cash_on_delivery_availability();
            }
        });

        $('#cc_city').on('change', function () {
            let cc_payment_type = $('#cc_payment_type option:selected').val();
            let cc_delivery_type = $('#cc_delivery_type option:selected').val();

            if(cc_payment_type == 2) { //cash on delivery
                cc_check_cash_on_delivery_availability();
            }
            if(cc_delivery_type == 2 || cc_delivery_type == 3){
                cc_check_delivery_methods_availability()
            }
            cc_calculate_delivery_cost();
        });

    });

})(jQuery);
