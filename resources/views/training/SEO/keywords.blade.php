<div class="col-md-12">
    @if($lang=='en')
      <input v-on:keydown.enter.prevent="addTag" v-model="keywordTxt" class="form-control" type="text" name="seo_keywords" placeholder="{{__('admin.keywords')}}">
      <button type="button" class="btn btn-primary badge-primary" v-for="(keyword, index) in keywords">
        <input name="seo_keywords[]" type="hidden" :value="keyword">
        @{{keyword}} <span class="badge badge-light" @click="deleteTag(index)">&times;</span>
      </button>
    @elseif($lang=='ar')
        <input v-on:keydown.enter.prevent="addTag_ar" v-model="keywordTxt_ar" class="form-control" type="text" name="seo_keywords_ar" placeholder="{{__('admin.keywords')}}">
        <button type="button" class="btn btn-info badge-info" v-for="(keyword_ar, index) in keywords_ar">
            <input name="seo_keywords_ar[]" type="hidden" :value="keyword_ar">
            @{{keyword_ar}} <span class="badge badge-light" @click="deleteTag_ar(index)">&times;</span>
        </button>
    @endif
</div>
