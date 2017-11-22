<template>
    <div class="panel panel-default">
        <div class="panel-heading level-end">
            <div>
                {{owner.name}} said
                {{created_at}} ago...
            </div>
            <div class="gotoend">
                favorite
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
            <div v-else v-text="body">
            </div>
        </div>

        <div class="panel-footer">
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
                body: this.data.body,
                owner: this.data.owner,
                created_at: this.data.created_at
            }
        },
        methods:{
            update(){
                var vm = this;

                axios.patch('/replies/'+this.attributes.id, {
                    body: this.body
                }).then(function(){
                    vm.editing = false;
                    flash('updated reply');
                }).catch(function (){
                    flash('error');
                });
            },
            destroy(){
                var vm = this;
                axios.delete('/replies/' + this.attributes.id).then(function(){
                    $(vm.$el).fadeOut(300, () => {
                        flash('reply deleted');
                    });
                });


            }
        }
    }
</script>