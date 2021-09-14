<template>
  <div>
    <div class="container">
      <ais-instant-search
        :search-client="searchClient"
        index-name="products_index" :stalled-search-delay="1000"
      >

      <!-- :restrictSearchableAttributes="['en_title']"  -->
          <ais-configure :hitsPerPage="4" />
          <ais-index index-name="learning_posts_index" query="en" :query-parameters="{
                filters: 'user_id:1'
            }">
              <ais-configure :hitsPerPage="3" />
          </ais-index>
          <ais-autocomplete>
            <template slot-scope="{ currentRefinement, indices, refine }">
                <i class="fas fa-search"></i>
              <vue-autosuggest
                :value="currentRefinement"
                :suggestions="indicesToSuggestions(indices)"
                @input="refine"
                @selected="onSelect"
                :getSuggestionValue="getSuggestionValue"
                :input-props="{
                  style: 'width: 100%',
                  onInputChange: refine,
                  placeholder: 'Search here…',
                  id:'autosuggest__input',
                }"
              >
                <template slot-scope="{ suggestion }">
                    <div v-if="suggestion.item.model_name=='Course'">
                        <small>{{course_title}}<br></small>
                        <ais-highlight style="font-weight:bold;"
                            :hit="suggestion.item"
                            :attribute="title"
                        />
                        <!-- v-if="suggestion.item.en_title" -->
                        <div>
                            <small>
                            <ais-highlight
                                :hit="suggestion.item"
                                :attribute="excerpt"
                            />
                            </small>
                            <!-- v-if="suggestion.item.en_short_excerpt" -->
                        </div>
                    </div>
                    <div v-if="suggestion.item.model_name=='Post' && suggestion.item.locale==locale">
                        <small>{{post_title}}<br></small>
                        <ais-highlight style="font-weight:bold;"
                            :hit="suggestion.item"
                            :attribute="title"
                        />
                        <div>
                            <small>
                            <ais-highlight
                                :hit="suggestion.item"
                                :attribute="excerpt"
                            />
                            </small>
                            <!-- v-if="suggestion.item.en_short_excerpt" -->
                        </div>
                    </div>

                </template>

              </vue-autosuggest>

            </template>
          </ais-autocomplete>

          <ais-powered-by />
        <!-- No. of results -->
        <!-- <ais-stats></ais-stats> -->

      </ais-instant-search>
    </div>
  </div>
</template>

<script>
import algoliasearch from 'algoliasearch/lite';
import 'instantsearch.css/themes/algolia-min.css';
import { VueAutosuggest } from 'vue-autosuggest';
export default {
  components: { VueAutosuggest },
  data() {
    return {
      searchClient: algoliasearch(
        '344AAZSWHX',
        'e6a5071aa5e4ba8161418cb439d413dd'
      ),
      query: '',
    };
  },
  props:['course_title', 'post_title', 'locale', 'title', 'excerpt'],
  methods: {
    onInputChange() {
        console.log(this.query);
    },
    onSelect(selected) {
      if (selected) {
          var path = selected.item.en_path;
          if(this.locale=='ar')
            var path = selected.item.ar_path;
        // this.query = selected.item.en_title;
        window.location.href = path;
        // window.location.href = 'https://bakkah.com';
        // alert(this.query);
      }
    },
    indicesToSuggestions(indices) {
      return indices.map(({ hits }) => ({ data: hits }));
    },
    getSuggestionValue(suggestion) {
        return null;
        // return suggestion.item.en_title;
    //   let { en_title, item } = suggestion;
    //   return name == "hotels" ? item.en_title : item.name;
    }
  }
};
</script>

<style>
.ais-PoweredBy-logo {
    height: 0.75rem !important;
    margin-top:5px;
}
body,
h1 {
  margin: 0;
  padding: 0;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica,
    Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
}

.ais-Highlight-highlighted {
  background: cyan;
  font-style: normal;
}

.header {
  display: flex;
  align-items: center;
  min-height: 50px;
  padding: 0.5rem 1rem;
  background-image: linear-gradient(to right, #4dba87, #2f9088);
  color: #fff;
  margin-bottom: 1rem;
}

.header a {
  color: #fff;
  text-decoration: none;
}

.header-title {
  font-size: 1.2rem;
  font-weight: normal;
}

.header-title::after {
  content: ' ▸ ';
  padding: 0 0.5rem;
}

.header-subtitle {
  font-size: 1.2rem;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem;
}

#autosuggest input {
  font: inherit;
}

.autosuggest__results-container {
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
}

.autosuggest__results-container ul {
  margin: 0;
  padding: 0;
}

.autosuggest__results_item {
  border-bottom: 1px solid rgba(0, 0, 0, 0.12);
  list-style-type: none;
  padding: 0.5em;
  display: grid;
  grid-template-columns: 5fr 1fr 1fr;
  align-items: center;
  justify-content: space-between;
}

.autosuggest__results_item img {
  height: 3em;
}

.autosuggest__results-item--highlighted {
  background-color: rgba(0, 0, 0, 0.12) !important;
}

.ais-Hits-item img {
  max-width: 100%;
}
#autosuggest-autosuggest__results {
    z-index: 999;
    position: absolute;
    background: #fff;
    box-shadow: 0 10px 15px -8px rgba(9,9,16,0.1);
}

.autosuggest__results-container ul {
    list-style: none;
}

.autosuggest__results-container ul li {
    padding: 10px;
    border-bottom: 1px solid rgb(231,232,242);
}
.ais-Autocomplete {
    position: relative;
    width: 700px;
}

#autosuggest input {
    padding: 5px 30px;
    border: 0;
    border-bottom: 1px solid #cacaca;
    outline: 0;
}

.ais-Autocomplete i {
    position: absolute;
    bottom: 7px;
    font-size: 16px;
    color: #73726c;
}
</style>
