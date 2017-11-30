<template>
    <div>
        <div class="form-group">
            <textarea name="body" v-model="body" id="body" class="form-control" @keydown.enter="publish" placeholder="Have something to say? Press enter to submit"></textarea>
        </div>
        <!--<button type="submit" class="btn btn-default" data-toggle="confirmation"  @click="publish">Publish</button>-->
    </div>
</template>

<script>
    export default {
        props: ['data'],
        data(){
            return {
                body:''
            }
        },
        methods:{
            publish(){
                var vm = this;

                axios.post(location.pathname + '/replies',{ body: this.body})
                    .catch(function(error){
                        flash(error.response.data.errors.body[0],'danger');
                    })
                    .then(function(response){
                        vm.$emit('created', response.data);
                        vm.body='';
                        flash('Your reply is saved','success');
                    });
            }
        }
    }
</script>