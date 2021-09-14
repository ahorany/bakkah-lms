<table class="table table-striped table-hover table-total-info" :key="cartMaster.id">
    <tr class="text-center">
        <td colspan="2"><b>User Info</b>
            {{-- @click.prevent="Switch(cartMaster)" --}}
            <button class="" v-if="active" style="float: right" @click.prevent="Switch(cartMaster)" class="btn btn-primary btn-sm"  type="submit" ><li class="fa fa-edit"></li> <span style="font-family: 'Lato-Regular';"> edit</span></button>
            <button v-else style="float: right" @click.prevent="Switch(cartMaster)" class="btn btn-warning btn-sm"><li class="fa fa-save"></li><span style="font-family: 'Lato-Regular';">  update</span></button>
        </td>
    </tr>

    <tr>
        <td class="field-title">{{__('admin.name')}}</td>
        <td>
            <div v-if="moode" style="display: flex;">
                <input type="text" class="form-control col-sm-6"  placeholder="English"  v-model="cartMaster_edit.en_name">
                <input type="text" class="form-control col-sm-6"  placeholder="Arabic"  v-model="cartMaster_edit.ar_name">
            </div>
            <div v-else >
                 <span class="text-secondary col-sm-6">En: @{{cartMaster_edit.en_name}}</span>
                 <div v-show="'en_name' in errors">
                    <span style="color: red;font-size: 13px">@{{ errors.en_name }}</span>
                </div>
                 <span class="text-secondary col-sm-6">Ar: @{{cartMaster_edit.ar_name}}</span>
                 <div v-show="'ar_name' in errors">
                    <span style="color: red;font-size: 13px">@{{ errors.ar_name }}</span>
                </div>
            </div>
        </td>
    </tr>

    <tr>
        <td class="field-title">{{__('admin.email')}}</td>
        <td>
            {{-- <input v-if="moode" type="text" class="form-control" v-model="cartMaster_edit.email"> --}}
            <span class="text-secondary" v-text="cartMaster_edit.email"></span>
        </td>
    </tr>

    <tr>
        <td class="field-title">{{__('admin.mobile')}}</td>
        <td>
            <input v-if="moode" type="number" class="form-control" v-model="cartMaster_edit.mobile">
            <span v-else class="text-secondary" v-text="cartMaster_edit.mobile"></span>
            <div v-show="'mobile' in errors">
                <span style="color: red;font-size: 13px">@{{ errors.mobile }}</span>
            </div>
        </td>
    </tr>

    <tr>
        <td class="field-title">{{__('admin.job_title')}}</td>
        <td>
            <input v-if="moode" type="text" class="form-control" v-model="cartMaster_edit.job_title">
            <span v-else class="text-secondary" v-text="cartMaster_edit.job_title"></span>
            <div v-show="'job_title' in errors">
                <span style="color: red;font-size: 13px">@{{ errors.job_title }}</span>
            </div>
        </td>
    </tr>
    <tr>
        <td class="field-title">{{__('admin.company')}}</td>
        <td>
            <input v-if="moode" type="text" class="form-control" v-model="cartMaster_edit.company">
            <span v-else class="text-secondary" v-text="cartMaster_edit.company"></span>
            <div v-show="'company' in errors">
                <span style="color: red;font-size: 13px">@{{ errors.company }}</span>
            </div>
        </td>
    </tr>

    <tr>
        <td class="field-title">{{__('admin.country')}}</td>
        <td>
            <select class="form-control" v-model="cartMaster_edit.country_id" v-if="moode">
                <option value="-1">-- Select --</option>
                <option v-for="country in countries" :value="country.id">@{{ JSON.parse(country.name).en ?? null}}</option>
            </select>

            <span v-else class="text-secondary" v-text="cartMaster_edit.en_country"></span>
        </td>
    </tr>

    <tr>
        <td class="field-title">{{__('admin.username_lms')}}</td>
        <td>
            <input v-if="moode" type="text" class="form-control" v-model="cartMaster_edit.username_lms">
            <span v-else class="text-secondary" v-text="cartMaster_edit.username_lms"></span>
            <div v-show="'username_lms' in errors">
                <span style="color: red;font-size: 13px">@{{ errors.username_lms }}</span>
            </div>
        </td>
    </tr>
    <tr>
        <td class="field-title">{{__('admin.password_lms')}}</td>
        <td>
            <input v-if="moode" type="text" class="form-control" v-model="cartMaster_edit.password_lms">
            <span v-else class="text-secondary" v-text="cartMaster_edit.password_lms"></span>
            <div v-show="'password_lms' in errors">
                <span style="color: red;font-size: 13px">@{{ errors.password_lms }}</span>
            </div>
        </td>
    </tr>
</table>
