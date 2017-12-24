<template>
    <div class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
        <div class="panel-heading level-end">
            <div>
                {{owner.name}} said
                {{created_at}} ago...
            </div>
            <div class="gotoend level">
                <div v-if="authorize('markAsBestReply', thread)" class="mr-3">
                    <button class="btn btn-xs btn-primary" v-if="!isBest" @click="markAsBestReply">Best reply?</button>
                </div>
                <favorite :data="data"></favorite>
            </div>
        </div>

        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea v-model="body" class="form-control"></textarea>
                </div>
                <button class="btn btn-xs btn-success" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-html="body">
            </div>
        </div>

        <div class="panel-footer" v-if="authorize('updateReply', reply)">
            <button class="btn btn-xs" @click="editing = true">Edit</button>
            <button class="btn btn-xs btn-danger" @click="destroy">Delete</button>
        </div>
    </div>
</template>
<script>
    export default {
        props:['data'],
        data() {
            return {
                editing:false,
                id: this.data.id,
                body: this.data.body,
                owner: this.data.owner,
                thread: this.data.thread,
                created_at: this.data.created_at,
                isBest: this.data.isBest,
                reply: this.data
            }
        },
        created(){
          window.events.$on('best-reply', id => {
              this.isBest = (id === this.id);
              console.log('in '+this.id+'reply: isbest for '+id+' is '+(id === this.id));
          });
        },
        methods:{
            update(){
                var vm = this;

                axios.patch('/replies/'+this.data.id, {
                    body: this.body
                }).then(function(){
                    vm.editing = false;
                    flash('updated reply');
                }).catch(error => {
                    flash(error.response.data, 'danger');
                });
            },
            destroy(){
                var vm = this;
                axios.delete('/replies/' + this.data.id)
                     .then(function(){
                        vm.$emit('deleted', vm.id);
                     });
            },
            markAsBestReply(){
                axios.post('/replies/'+this.id+'/best')
                    .then(() => {
                        window.events.$emit('best-reply', this.id);
                    })
            }
        }
    }
</script>