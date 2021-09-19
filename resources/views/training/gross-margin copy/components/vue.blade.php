<?php $hasJquery1 = $hasJquery??true; ?>
@if($hasJquery1)
<script>
    function AjaxSearch(btnName, page=1){

        var btnHtml = btnName.html();
        btnName.html('Loading...');

        jQuery('[name="page"]').val(page);
        var form = jQuery('#cart-search');
        // console.log(form.attr('action'));
        jQuery.ajax({
            type:'get',
            url:form.attr('action'),
            data:form.serialize(),
            success:function(data){
                jQuery('.cart-table').html(data);
                btnName.html(btnHtml);
            }
            ,errors:function(er){
                console.log(er);
            }
        });
    }

    jQuery(function (){
        jQuery('[name="search"]').click(function (){
            var btnName = jQuery(this);
            AjaxSearch(btnName);
            return false;
        });

        jQuery('[name="clear"]').click(function (){
            jQuery('[name="course_id"], [name="payment_status"], [name="country_id"], [name="session_id"], [name="category_id"], [name="coin_id"], [name="training_option_id"], [name="type_id"]').val(-1);
            jQuery('[name="invoice_number"], [name="user_search"], [name="date_from"], [name="date_to"], [name="session_from"], [name="session_to"], [name="register_from"], [name="register_to"]').val('');
            return false;
        });
    });
</script>
@endif

<script>
    window.sessions = {!!$session_array!!}
    window.choose_value = {!! json_encode(__('admin.choose_value')) !!}
    var vm = new Vue({
        el: '#cart-search',
        data: {
            selected_session_id:-1,
            session_choose_value: window.choose_value,
            sessions: window.sessions,
        },
        methods: {
            courseChange: function (val) {
                vm.session_choose_value = 'loading...';
                axios.get('{{route("training.carts.sessionsJson")}}', {
                    params: {
                        'course_id' : val
                    }
                })
                .then(function(resp){
                    vm.sessions = resp.data;
                    vm.session_choose_value = window.choose_value;
                }.bind(this))
                .catch(function(err){
                    console.log(err);
                });
                // this.selected_training_option_id = parseInt(index);
            },
        }
    });
</script>
