<!-- Large modal -->
{{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">--}}
{{--    Large modal--}}
{{--</button>--}}
<style>
    .modal-lg {
        max-width: 90% !important;
    }
</style>
<div class="modal fade admin-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('admin.en_title')}}</label>
                                <input v-bind:value="accordion_en_title" type="text" class="form-control" placeholder="{{__('admin.en_title')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('admin.ar_title')}}</label>
                                <input v-bind:value="accordion_ar_title" type="text" class="form-control" placeholder="{{__('admin.ar_title')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('admin.en_details')}}</label>
                                <textarea v-bind:value="accordion_en_details" class="form-control tinymce" placeholder="{{__('admin.en_details')}}"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('admin.ar_details')}}</label>
                                <textarea v-bind:value="accordion_ar_details" class="form-control tinymce" placeholder="{{__('admin.ar_details')}}"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" v-on:click="SaveAccordion()"  class="btn btn-primary">{{__('admin.save')}}</button>
                        <button type="button" v-on:click="ClearAccordion()" class="btn btn-secondary">{{__('admin.clear')}}</button>
                    </div>
                </form>
                <table class="table table-hover table-condensed table-bordered table-striped">
                    <thead>
                    <th class="img-table d-none d-sm-table-cell">{{__('admin.index')}}</th>
                    <th class="d-none d-sm-table-cell">{{__('admin.title')}}</th>
                    <th class="img-table">{{__('edit')}}</th>
                    </thead>
                    <tbody>
                    <tr v-for="(accordion, index) in accordions">
                        <td>@{{ ++index }}</td>
                        <td>
                            @if(App::isLocale('en'))
                                @{{ JSON.parse(accordion.title).en }}
                            @else
                                @{{ JSON.parse(accordion.title).ar }}
                            @endif
                        </td>
                        <td>
                            <button v-on:click="EditAccordion(accordion)" class="btn btn-outline-primary btn-xs" type="button" data-id="@{{ index }}">
                                {{__('admin.edit')}}
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
