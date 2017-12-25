<template>
    <div class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
        <div class="panel-heading level-end">
            <div>
                {{owner.name}} said
                {{created_at}} ago...
            </div>
            <div class="gotoend level">
                <div v-if="authorize('owns', thread)" class="mr-3">
                    <button class="btn btn-xs btn-primary" v-if="!isBest" @click="markAsBestReply">Best reply?</button>
                </div>
                <favorite :data="reply"></favorite>
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

        <div class="panel-footer" v-if="authorize('owns', reply)">
            <button class="btn btn-xs" @click="editing = true">Edit</button>
            <button class="btn btn-xs btn-danger" @click="destroy">Delete</button>
        </div>
    </div>
</template>
<script>
    export default {
        props:['reply'],
        data() {
            return {
                editing:false,
                id: this.reply.id,
                body: this.reply.body,
                owner: this.reply.owner,
                thread: this.reply.thread,
                created_at: this.reply.created_at,
                isBest: this.reply.isBest,
            }
        },
        created(){
          window.events.$on('best-reply', id => {
              this.isBest = (id === this.id);
          });
        },
        methods:{
            update(){
                var vm = this;

                axios.patch('/replies/'+this.reply.id, {
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
                axios.delete('/replies/' + this.reply.id)
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