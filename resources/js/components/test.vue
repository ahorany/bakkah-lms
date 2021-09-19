<style>
.search-wrapper .active {
    background: rgb(231,232,242);
    outline: transparent;
}
</style>
<template>
    <div class="search-wrapper">
        <form action="">
            <i class="fas fa-search"></i>
            <input v-model="query" :placeholder="lang=='en' ? 'Search...' : 'ابحث...'" v-on:keyup.down="keydown(0, 'item')" v-on:keyup.up="keydown(-1, 'item')">
            <div class="result-wrapper" v-if="query != ''">
                <ais-index app-id="344AAZSWHX" api-key="e6a5071aa5e4ba8161418cb439d413dd" index-name="products_index" :query="query" :auto-search="false">
                <!-- <ais-search-box :autofocus="true" placeholder="Find products..."></ais-search-box> -->
                    <ais-results>
                        <template slot-scope="{ result, index }">
                            <a :item="'item.' + index" :ref="'item.'+index" v-on:keyup.up="keydownUp(index, 'item', Object.keys(result).length)" v-on:keyup.down="keydownDown(index, 'item', Object.keys(result).length)" :href="result.en_path" :class="myclass(index)" v-if="lang=='en'" :key="result.objectID">
                                <small>Course</small>
                                <h5>
                                <ais-highlight :result="result" attribute-name="en_title"></ais-highlight>
                                </h5>
                                <ais-highlight :result="result" attribute-name="en_short_excerpt"></ais-highlight>
                            </a>
                            <a :item="'item.' + index" :ref="'item.'+index" v-on:keyup.up="keydownUp(index, 'item', Object.keys(result).length)" v-on:keyup.down="keydownDown(index, 'item', Object.keys(result).length)" :href="result.ar_path" :class="myclass(index)" :key="result.objectID" v-else>
                            <!-- <a :href="result.ar_path" :key="result.objectID" class="result-item" v-else> -->
                                <h5>
                                <ais-highlight :result="result" attribute-name="ar_title"></ais-highlight>
                                </h5>
                                <ais-highlight :result="result" attribute-name="ar_short_excerpt"></ais-highlight>
                            </a>
                        </template>
                    </ais-results>
                    <!--<ais-results inline-template>
                        <table>
                            <tbody>
                            <tr v-for="result in results" :key="result.objectID">
                                <td>
                                    <strong>{{ result.en_title }}</strong>
                                    <div>
                                        <small>{{ result.en_excerpt }}</small>
                                        <br>
                                        <br>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </ais-results>-->
                    <!-- <ais-pagination :class-names="{
                    'ais-pagination': 'pagination',
                    'ais-pagination__item': 'page',
                    'ais-pagination__item--active': 'active',
                    }">
                    </ais-pagination> -->
                </ais-index>
                <ais-index app-id="344AAZSWHX" api-key="e6a5071aa5e4ba8161418cb439d413dd" index-name="learning_posts_index" :query="query" :auto-search="false">
                    <!-- <ais-search-box :autofocus="true" placeholder="Find products..."></ais-search-box> -->
                    <ais-results>
                        <template slot-scope="{ result, index }">
                                <a :item="'post.' + index" :ref="'post.'+index" v-on:keyup.up="keydownUp(index, 'post', Object.keys(result).length)" v-on:keyup.down="keydownDown(index, 'post', Object.keys(result).length)" :href="result.path" :class="myclass(index)" :key="result.objectID" v-if="result.locale==lang">
                                <!-- <a :href="result.path" class="result-item" v-if="result.locale==lang"> -->
                                    <small>Knowledge Center</small>
                                    <h5>
                                        <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                                    </h5>
                                    <ais-highlight :result="result" attribute-name="excerpt"></ais-highlight>
                                </a>
                                <a href="" style="display:none;" v-else></a>
                        </template>
                    </ais-results>
                </ais-index>
            </div>
        </form>
    </div>

</template>
<script>
export default {
  data: function() {
    return {
      query: '',
      myindex: -1
    }
  },
    props:['lang'],
    methods: {
        doMath: function (index) {
        return index+9
        },
        keydown(index, name){
            this.myindex = index;
            // console.log(this.myindex);
            var ref = name+'.'+this.myindex;
            // console.log(this.$refs[ref]);
            console.log(this.$refs);
            this.$refs[ref].focus();

            // this.objectId = 'objectId-' + index;
            // alert(this.objectId);
        },
        keydownUp(index, name, length){
            this.myindex = index-1;
            var ref = name+'.'+this.myindex;
            console.log(this.$refs);
            this.$refs[ref].focus();
        },
        keydownDown(index, name, length){
            this.myindex = index+1;
            var ref = name+'.'+this.myindex;
            console.log(this.$refs);
            this.$refs[ref].focus();
        },
        myclass(index){
            if(this.myindex == index){
                return 'result-item active';
            }
            return 'result-item';
        },
        onChange() {
        // Let's warn the parent that a change was made
        this.$emit("input", this.search);

        // Is the data given by an outside ajax request?
        if (this.isAsync) {
            this.isLoading = true;
        } else {
            // Let's search our flat array
            this.filterResults();
            this.isOpen = true;
        }
        },

        filterResults() {
        // first uncapitalize all the things
        this.results = this.items.filter(item => {
            return item.toLowerCase().indexOf(this.search.toLowerCase()) > -1;
        });
        },
        setResult(result, i) {
        this.arrowCounter = i;
        this.search = result;
        this.isOpen = false;
        },
        onArrowDown(ev) {
            ev.preventDefault();
            alert(1);
            if (this.arrowCounter < this.results.length-1) {
                this.arrowCounter = this.arrowCounter + 1;
                this.fixScrolling();
            }
        },
        onArrowUp(ev) {
            ev.preventDefault()
            if (this.arrowCounter > 0) {
                this.arrowCounter = this.arrowCounter - 1;
                this.fixScrolling()
            }
        },
        fixScrolling(){
            const liH = this.$refs.options[this.arrowCounter].clientHeight;
            this.$refs.scrollContainer.scrollTop = liH * this.arrowCounter;
        },
        onEnter() {
            this.search = this.results[this.arrowCounter];
            this.isOpen = false;
            this.arrowCounter = -1;
        },
        showAll() {
            this.isOpen = !this.isOpen;
                (this.isOpen) ? this.results = this.items : this.results = [];
        },
        handleClickOutside(evt) {
            if (!this.$el.contains(evt.target)) {
                this.isOpen = false;
                this.arrowCounter = -1;
            }
        }
  },
  mounted() {
    document.addEventListener("click", this.handleClickOutside);
  },
  destroyed() {
    document.removeEventListener("click", this.handleClickOutside);
  }
}
</script>
