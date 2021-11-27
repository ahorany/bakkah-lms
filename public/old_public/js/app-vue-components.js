

Vue.component('form-input',{
    props :['type','name','label','placeholder','value','old_msg','disabled'],

   template: `<div class="form-group">
       <label v-text="label"></label>
       <input :value="getValue" :disabled="disabled" v-on:input="$emit('input', $event.target.value)" :type="type" :name="name" class="form-control" :placeholder="placeholder" >
   </div>`,
    computed : {
      getValue : function () {
          if (old_msg[this.name]){
              // alert(this.value)
            return  old_msg[this.name];
          }
          return  this.value ;

      }
    },
    created(){
        // console.log(this.old_msg)
    }
});


Vue.component('form-select',{
    data: function(){
          return  {
              select2_class: "js-example-basic-single"
          }
    },
    props: {
        name: String,
        label: String,
        viewText: String,
        change: String,
        optionsData: Array,
        oprionKey:String,
        isTextJson:String,
        lang:String,
        loading:String,
        selectValue:String,
        disabled:String,
        old_msg:Array,
    },

    template: `<div class="form-group">
        <template v-if="isTextJson == 'true'">
            <label v-text="label"></label>
            <template v-if="change == 'true'">
                <select :disabled="disabled"  v-on:change="$emit('on-change',$event.target.value);" class="form-control" :name="name">
                    <option v-if="loading == true" selected value="-1">Loading...</option>
                    <option value="-1">Choose value</option>
                    <option v-for="option in optionsData" :selected="getValue == option[oprionKey]"  :value="option[oprionKey]" v-text="convertJSON(option[viewText])" ></option>
                </select>
            </template>

            <template v-else>
                <select :disabled="disabled"  class="form-control" :name="name">
                    <option v-if="loading == true" selected value="-1">Loading...</option>
                    <option value="-1">Choose value</option>
                    <option v-for="option in optionsData" :selected="getValue == option[oprionKey]" :value="option[oprionKey]" v-text="option[viewText]" ></option>
                </select>
            </template>
        </template>

        <template v-else>
            <label v-text="label"></label>
                  <template v-if="change == 'true'">
                      <select :disabled="disabled"  v-on:change="$emit('on-change',$event.target.value);" class="form-control" :name="name">
                          <option v-if="loading == true" selected value="-1">Loading...</option>
                          <option value="-1">Choose value</option>
                          <option v-for="option in optionsData"  :selected="getValue == option[oprionKey]" :value="option[oprionKey]" v-text="option[viewText]" ></option>
                      </select>
                  </template>

                <template v-else>
                    <select :disabled="disabled"  class="form-control" :name="name">
                        <option v-if="loading == true" selected value="-1">Loading...</option>
                        <option value="-1">Choose value</option>
                        <option v-for="option in optionsData" :selected="getValue == option[oprionKey]"  :value="option[oprionKey]" v-text="option[viewText]" ></option>
                    </select>
                </template>

        </template>

              </div>`,
    methods:{
        convertJSON : function(value){
          value =  JSON.parse(value)[this.lang]
            return value
        }
    },
    computed : {
        getValue : function () {
            if (old_msg[this.name]){
                return  old_msg[this.name];
            }
            return  this.selectValue ;
        }
    },
    created(){
        // console.log(this.old_msg)
    }
});


Vue.component('group-buttons',{
    props :['action','backHref','backBtnText','actionBtnText'], // action => update | publish

    template: `
        <div class="card card-default">
            <div class="card-body">
                <div  class="BtnGroupForm">
                    <a name="back" :href="backHref" title="Back admin." class="btn btn-sm btn-default">
                        <i class="fa fa-arrow-left"></i>{{backBtnText}}</a>
                    <button v-on:click.prevent="$emit('on-click','publish');" v-if="action == 'publish'" type="submit" name="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i>
                        {{actionBtnText}}</button>
                    <button v-on:click.prevent="$emit('on-click','update');" v-else-if="action == 'update'" type="submit" name="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i>
                        {{actionBtnText}}</button>
                </div>
            </div>
        </div>`,
});








