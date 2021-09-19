<script>
    window.sessions = null;

    window.choose_value = {!! json_encode(__('admin.choose_value')) !!}
    var vm = new Vue({

        el: '#cart-search',
        data: {
            selected_session_id  :  -1,
            session_choose_value : window.choose_value,
            sessions             : window.sessions,
            course_value         : '',
        },
        methods: {
            courseChange: function (val) {
                // alert(val);
                //this.session_choose_value = 'loading...';
                axios.get('{{route("training.carts.sessionsJson")}}', {

                    params: {
                        'course_id' : val
                    }
                })
                .then(function(resp){

                    this.sessions = resp.data;
                    console.log(this.sessions);
                    this.session_choose_value = window.choose_value;

                }.bind(this))
                .catch(function(err){
                    console.log(err);
                });
                // this.selected_training_option_id = parseInt(index);

            },
        },
        created() {

            this.courseChange(this.course_value);
        }
    });
</script>
