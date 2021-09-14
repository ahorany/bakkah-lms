<div class="card card-info">
    <div class="card-header">
        <h6 class="mb-0 float-left"><i class="far fa-file-alt" aria-hidden="true"></i> Courses Info</h6>
        <h6 class="mb-0 float-right" v-text="cartMaster.registered_at"></h6>
    </div>
    <div class="card-body p-1">
        @include('crm::products_demand.courses')
    </div>
</div>
