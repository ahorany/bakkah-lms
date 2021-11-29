<style>
.badge-primary, .badge-info {
  margin: 5px 2px;
  padding: 2px 5px;
  font-size: 0.9rem;
  font-weight: normal;
}
.badge {
  top: 0;
  left: -5px;
  background: transparent;
  color: #fff;
  font-size: 1rem;
}
.badge-primary, .badge-info {
    text-transform: none !important;
}
</style>
<div id="seo_keywords">
    <?php
    if(isset($eloquent)){
        $post1 = !is_null($eloquent)?$eloquent->seo()->first():null;
    }
    ?>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{__('admin.SEO English')}}</h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">

                    {!!Builder::Input('en_seo_author', 'seo_author', $post1->en_author??null)!!}
                    {!!Builder::Textarea('en_seo_description', 'seo_description', $post1->en_description??null, [
                        'row'=>3,
                        'attr'=>'maxlength="500"',
                    ])!!}

                    @include('crm.SEO.keywords', ['lang'=>'en'])

                </div>
            </div>
        </div>
    </div>

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">{{__('admin.SEO Arabic')}}</h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">

                    {!!Builder::Input('ar_seo_author', 'seo_author', $post1->ar_author??null)!!}
                    {!!Builder::Textarea('ar_seo_description', 'seo_description', $post1->ar_description??null, [
                        'row'=>3,
                        'attr'=>'maxlength="500"',
                    ])!!}

                    @include('crm.SEO.keywords', ['lang'=>'ar'])

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$keywords1 = [];
$keywords1_ar = [];
if(isset($eloquent->postkeywords)){
  foreach($eloquent->postkeywords as $keyword){
      if($keyword->locale=='ar'){
          $keywords1_ar[] = $keyword->seokeyword->title;
      }
      else{
          $keywords1[] = $keyword->seokeyword->title;
      }
  }
}
?>
<script>
window.keywords = {!!json_encode($keywords1??[])!!}
window.keywords_ar = {!!json_encode($keywords1_ar??[])!!}

vm = new Vue({
  el:'#seo_keywords',
  data:{
    keywordTxt:'',
    keywordTxt_ar:'',
    keywords:keywords,
    keywords_ar:keywords_ar,
  },
  methods: {
    addTag: function(){
      if(this.keywordTxt != ''){
        this.keywords.push(this.keywordTxt);
        this.keywordTxt = '';
      }
    },
    addTag_ar: function(){
        if(this.keywordTxt_ar != ''){
            this.keywords_ar.push(this.keywordTxt_ar);
            this.keywordTxt_ar = '';
        }
    },
    deleteTag: function(index){
      index = parseInt(index);
      this.keywords.splice(index, 1);
    },
    deleteTag_ar: function(index){
      index = parseInt(index);
      this.keywords_ar.splice(index, 1);
    }
  },
});
$(document).ready(function() {
    // $('.js-example-basic-multiple').select2();
    // $('.select2-hidden-accessible').select2();
    /*$('.select2-hidden-accessible').select2({
      // tags: true,
      // insertTag: function (data, tag) {
      //   // Insert the tag at the end of the results
      //   data.push(tag);
      // }
    });*/
});
</script>
