<div class="col-md-12">
    <div class="form-group">
        <label>Excerpt </label>
        <input type="text" v-model="title" name="title" class="form-control" placeholder="title">
    </div>
</div>
@{{title}}
<div class="modal-diff-content">

    <div class="col-md-12">
        <div class="form-group">
            <label>Excerpt </label>
            <textarea v-model="excerpt" class="form-control" rows="8" placeholder="Excerpt" maxlength="1000">
            </textarea>
        </div>
    </div>

</div>