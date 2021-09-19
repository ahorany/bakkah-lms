<template>
<div class="col-md-12">
    <div class="form-group">
        <label>{{title}}</label>
        <select class="form-control" name="name" @change="getSessions($event)">
            <option value="-1">Choose Value</option>
            <option v-for="option in options" :key="option.id" :value="option.id">
                {{JSON.parse(option.title).en}}
            </option>
        </select>
        <br>

        <!-- {{sessions}} -->
        <table class="table table-condensed" style="width:100%;">
            <thead>
                <tr>
                    <th></th>
                    <th>Session</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th style="width:100px;">Value%</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="session in sessions" :key="session.id">
                    <td>
                        <input type="checkbox" class="form-control">
                    </td>
                    <td v-html="session.session_time"></td>
                    <td v-html="session.date_from"></td>
                    <td v-html="session.date_to"></td>
                    <td>
                        <input type="text" class="form-control" style="width:100px;">
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" @click="onChange($event)">aaaaa</button>
    </div>
</div>
</template>
<script>
export default {
    data() {
        return {
            sessions:[],
            errors: [],
        }
    },
    props:['title', 'name', 'options', 'route'],
    methods:{
        onChange:function(event){
            console.log(this.route);
            console.log(event.target.value);
        },
        getSessions:function(event){
            var vm = this;
            var course_id = event.target.value;
            axios.get(vm.route, {
                params:{
                    course_id: course_id,
                }
            })
            .then(response => {
                // JSON responses are automatically parsed.
                // console.log(response);
                vm.sessions = response.data;
                vm.passedthroughvar = 'aaaaaaaaaaaaa';
                // console.log(vm.sessions);
            })
            .catch(e => {
                vm.errors.push(e)
            });
        }
    }
}
</script>
