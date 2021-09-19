<div class="card">
    <div class="card-header">
        <h6 class="float-left mb-0 pt-2"><i class="fas fa-user"></i> User Info &nbsp;</h6>
        <h6 class="float-left text-secondary mb-0 pt-2">(@{{cartMaster.created_at}})
            <span v-if="cartMaster.wp_migrate==1" class="badge badge-dark">
                OLD DATA
            </span>
        </h6>
        {{-- <h6 class="float-right" v-html="cartMaster_edit.registered_at"></h6> --}}
        <div class="float-right">
            <button v-if="active" style="float: right" @click.prevent="Switch(cartMaster)" class="btn btn-warning btn-sm"  type="submit" ><li class="fa fa-edit"></li> <span style="font-family: 'Lato-Regular';"> Edit</span></button>
            <button v-else style="float: right" @click.prevent="Switch(cartMaster)" class="btn btn-primary btn-sm"><li class="fa fa-save"></li><span style="font-family: 'Lato-Regular';">  Update</span></button>
            <button v-if="!active" style="float: right" @click.prevent="moode=false; active=true" class="btn btn-secondary btn-sm"  type="submit" ><li class="fa fa-ban"></li> <span style="font-family: 'Lato-Regular';"> Cancel</span></button>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-striped table-hover table-total-info" :key="cartMaster.id">
        {{-- <tr class="text-center">
            <td colspan="2"><b>User Info</b>
                <button class="" v-if="active" style="float: right" @click.prevent="Switch(cartMaster)" class="btn btn-primary btn-sm"  type="submit" ><li class="fa fa-edit"></li> <span style="font-family: 'Lato-Regular';"> edit</span></button>
                <button v-else style="float: right" @click.prevent="Switch(cartMaster)" class="btn btn-warning btn-sm"><li class="fa fa-save"></li><span style="font-family: 'Lato-Regular';">  update</span></button>
            </td>
        </tr> --}}
        <tr>
            <td class="field-title">{{__('admin.name')}}</td>
            <td>
                <div v-if="moode" style="display: flex;">
                    <input type="text" class="form-control col-sm-6" placeholder="English" v-model="cartMaster_edit.en_name">
                    <input type="text" class="form-control col-sm-6" placeholder="Arabic" v-model="cartMaster_edit.ar_name">
                </div>
                <div v-else >
                        <span class="text-secondary col-sm-6 pl-0">En: @{{cartMaster_edit.en_name}}</span>
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
        <tr v-if="cartMaster_edit.retrieved_code">
            <td class="field-title">Retrieved Code</td>
            <td>
                <input v-if="moode" type="text" class="form-control w-50 text-danger d-inline" v-model="cartMaster_edit.retrieved_code" readonly>
                <span v-else class="text-danger" v-text="cartMaster_edit.retrieved_code"></span>
                <small class="text-primary d-inline pl-3">To be used by candidate</small>
            </td>
        </tr>
        <tr v-if="cartMaster_edit.balance">
            <td class="field-title">Balance</td>
            <td>
                <input v-if="moode" type="number" class="form-control w-50 text-primary d-inline" v-model="cartMaster_edit.balance" readonly>
                <span v-else class="text-primary" v-text="cartMaster_edit.balance"></span>
                <strong><small class="text-primary" v-text="converJson(cartMaster.coin.name).en"></small></strong>
            </td>
        </tr>
        <tr>
            <td class="field-title">Reminder Date</td>
            <td>
                {{-- {!!Builder::Date('reminder_date', 'empty',null,[ 'col'=>'col-md-6', 'attr'=>'v-model="cartMaster_edit.reminder_date"',])!!} --}}

                <input v-if="moode" maxlength="155" type="text" name="reminder_date" data-date="date" autocomplete="off" placeholder="Reminder Date" value="" class="form-control hasDatepicker" id="reminder_date" v-model="cartMaster_edit.reminder_date">

                <span v-else class="text-secondary" v-text="cartMaster_edit.reminder_date"></span>
            </td>
        </tr>
        </table>
    </div>
</div>
    {{-- 2222222222222222222 --}}
    <div class="card card-info"  v-if="cartMaster_edit.payment_rep_id">
        <div class="card-header pt-3 pb-3" style="border-bottom: 2px solid rgb(251, 68, 0);"  onclick="myFunction()">
           <h6 class="mb-0 float-left"><i aria-hidden="true" class="far fa-file-alt"></i> Checkout Payment Info</h6>
           <h6 class="mb-0 float-right">@{{ moment(String(paymentDetails.requested_on)).format('YYYY/MM/DD hh:mm:ss') }}</h6>
        </div>
        <div class="card-body p-0" id="myDIV">
           <table class="table table-bordered table-striped table-hover table-total-info">
              <tbody>
                <tr>
                    <td class="title">Payment ID</td>
                    <td class="value text-secondary">@{{ paymentDetails.id }}</td>
                </tr>
                <tr class="" :class="paymentDetails.status=='Captured'?'bg-success text-white':'bg-danger text-white'">
                    <td class="title">Status</td>
                    <td class="value">@{{ paymentDetails.status }}</td>
                </tr>
                <tr>
                    <td class="title">Amount</td>
                    <td class="value text-secondary">@{{ parseFloat(paymentDetails.amount/100).toFixed(2) }} @{{ paymentDetails.currency }}</td>
                </tr>
                <tr>
                    <td class="title">Card</td>
                    <td class="value text-secondary">@{{ paymentDetails.source.scheme??paymentDetails.source.type }}</td>
                </tr>
                <tr>
                    <td class="title">Issuer</td>
                    <td class="value text-secondary">@{{ paymentDetails.source.issuer }}</td>
                </tr>
                <tr>
                    <td class="title">Issuer Country</td>
                    <td class="value text-secondary">@{{ paymentDetails.source.issuer_country }}</td>
                </tr>
                <tr>
                    <td class="title">Order Data</td>
                    <td class="value text-secondary">@{{ paymentDetails.metadata.courses }}</td>
                </tr>
              </tbody>
           </table>
        </div>
     </div>
     <script>
        var x = document.getElementById("myDIV");
        x.style.display = "none";
        function myFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
     </script>
    {{-- 2222222222222222222 --}}
