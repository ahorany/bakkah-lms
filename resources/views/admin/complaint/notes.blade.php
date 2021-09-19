<div class="card mb-4" id="notes">
    <div class="card-header">
        {{-- @dd($eloquent->id) --}}
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0"><i class="far fa-comments" aria-hidden="true"></i> Notes</h6>
            <button class="btn btn-danger btn-sm" @click.prevent="deleteItemsFunc" :disabled="deleteItems.length==0">Delete Selected</button>
        </div>
    </div>
    <div class="card-body p-0" style="max-height: 380px; overflow-y: auto;">
        <ul id="note_list" class="list-unstyled m-0">
            <li class="media border-bottom p-2" v-for="note in notes" :class="note.user.id == {{auth()->user()->id}} ? 'bg-light' : ''">
              <div style="width:22px">
                <input type="checkbox" v-if="note.user.id == {{auth()->user()->id}}" class="m-1" v-model="deleteItems" :value="note.id">
            </div>
              <div class="media-body">
                <h6 style="font-size: .9rem" class="mt-0 mb-1">@{{JSON.parse(note.user.name).en}} <br> <a :href="'mailto:' + note.user.email">@{{note.user.email}}</a> <br> @{{note.created_at}} </h6>
                <p class="mb-0">@{{note.comment}}</p>
              </div>
            </li>
          </ul>
    </div>
    <div class="card-footer">
        <textarea class="form-control mb-3" v-model="comment" @keydown.enter.exact.prevent @keyup.enter.exact="addComment"></textarea>
        <button class="btn btn-success btn-sm" @click.prevent="addComment" :disabled="comment.length==0">Add Note</button>
    </div>
    @push('vue')
        <script>
            // window.notes = {}
            window.notes = {!!json_encode($eloquent->notes??[])!!}
            var notes = new Vue({
                el:'#notes',
                data:{
                    comment:'',
                    deleteItems: [],
                    notes: window.notes,
                },
                methods: {
                    addComment: function(){
                        axios.get("{{route('admin.complaint.comment', $complaintId)}}", {
                        params:{
                                comment: this.comment
                            }
                        })
                        .then(response => {
                            this.comment = '';
                            this.notes.push(response.data[0]);
                            //this.getNotes();
                        })
                        .catch(e => {
                            // vm.errors.push(e)
                        });
                    },
                    deleteItemsFunc: function() {
                        if(confirm('Are you sure?')) {
                            axios.get("{{route('admin.complaint.delete', $complaintId)}}", {
                            params:{
                                    ids: this.deleteItems
                                }
                            })
                            .then(response => {
                                this.notes = this.notes.filter(item => !this.deleteItems.includes(item.id));
                                this.deleteItems = [];
                            })
                            .catch(e => {
                                // vm.errors.push(e)
                            });
                        }
                    },
                    getNote: function() {
                        console.log('Noteeeeeeee');
                    }
                },
            });
        </script>
    @endpush
</div>
