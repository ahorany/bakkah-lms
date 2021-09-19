<template>
  <div>
    <div class="container">
      <ais-instant-search
        :search-client="searchClient"
        index-name="products_index" :stalled-search-delay="1000"
      >

      <!-- :restrictSearchableAttributes="['en_title']"  -->
          <ais-configure :hitsPerPage="4" />
          <!-- <ais-index index-name="learning_posts_index">
              <ais-configure :hitsPerPage="3" />
          </ais-index> -->
          <ais-autocomplete>
          <!-- :suggestions="indicesToSuggestions(indices)" -->

            <template slot-scope="{ currentRefinement, indices, refine }">
                <i class="fas fa-search"></i>
              <vue-autosuggest
                :value="currentRefinement"
                :suggestions="indicesToSuggestions1(indices)"
                @input="refine"
                @selected="onSelect"
                :getSuggestionValue="getSuggestionValue"
                :input-props="{
                  style: 'width: 100%',
                  onInputChange: refine,
                  placeholder: placeholder,
                  id:'autosuggest__input',
                }"
              >
                <template slot-scope="{ suggestion }">
                    <div v-if="suggestion.item.model_name=='Course'">
                        <small class="course">{{course_title}}<br></small>
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
                        </div>
                    </div>
                    <div v-if="suggestion.item.model_name=='Post' && suggestion.item.locale==locale">
                        <small class="post">{{post_title}}<br></small>
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

const algoliaClient = algoliasearch(
  'GZX3ABM7FT',
  'e5a7dae9287765d31f71dc49225990eb'
);

const searchClient = {
  search(requests) {
    const newRequests = requests.map((request)=>{

      // test for empty string and change request parameter: analytics
        if(!request.params.query || request.params.query.length===0) {
            request.params.analytics=false;
        }
        return request
    });
    return algoliaClient.search(newRequests);
  },
};

export default {
  components: { VueAutosuggest },
  data() {
    return {
    //   searchClient,
    };
  },
//   data() {
//     return {
//       searchClient: algoliasearch(
//         'GZX3ABM7FT',
//         'e5a7dae9287765d31f71dc49225990eb'
//       ),
//       query: '',
//     };
//   },
  props:['course_title', 'post_title', 'locale', 'title', 'excerpt', 'placeholder', 'path'],
  methods: {
    onInputChange() {
        console.log(this.query);
    },
    onSelect(selected) {
      if (selected) {
          var path = this.path + selected.item.en_path;
          if(this.locale=='ar')
            path = this.path + selected.item.ar_path;
        // this.query = selected.item.en_title;
        window.location.href = path;
        // window.location.href = 'https://bakkah.com';
        // alert(this.query);
      }
    },
    indicesToSuggestions(indices) {
      return indices.map(({ hits }) => ({ data: hits }));
    },
    indicesToSuggestions1(indices) {
        const task_names = [];
          indices.forEach(element => {
              element.hits.filter(word => word.model_name=='Course').forEach(el => {
                  task_names.push({data:[el]});
              });
              element.hits.filter(word => word.model_name=='Post').forEach(el => {
                  if(el.locale==this.locale){
                    task_names.push({data:[el]});
                  }
              });
          });
        //   console.log(task_names);
          return task_names;
    },
    // renderSuggestion(suggestion) {
    //     if(suggestion.item.locale==this.locale){
    //         return suggestion.item.en_title;
    //     }
    //     return;
    // },
    // shouldRenderSuggestions (size, loading) {
    //     // This is the default behavior
    //     return size >= 0 && !loading
    // },
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

.ais-Highlight-highlighted {
  background: rgb(251 68 0 / .2);
  font-style: normal;
  padding: 0;
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
  background: rgb(231,232,242);
  cursor: pointer;
}

.ais-Hits-item img {
  max-width: 100%;
}
#autosuggest-autosuggest__results {
    z-index: 999;
    position: absolute;
    background: rgb(245,245,250);
    box-shadow: 0 10px 15px -8px rgba(9,9,16,0.1);
    width: 100%;
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
    background-color: #fbfbf8;
}

.ais-Autocomplete i {
    position: absolute;
    bottom: 8px;
    left: 5px;
    font-size: 16px;
    color: #73726c;
}
.ais-PoweredBy {
    display: none;
}
.autosuggest__results-container ul li small .ais-Highlight {
    font-size: .9rem;
}
.autosuggest__results-container ul li small.course {
    color: var(--mainColor);
}
.autosuggest__results-container ul li small.post {
    color: var(--thirdColor);
}
</style>
