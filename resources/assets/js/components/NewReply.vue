<template>
    <div>
        <div class="form-group">
            <textarea name="body" v-model="body" id="body" class="form-control" @keydown.enter="publish" placeholder="Have something to say? Press enter to submit"></textarea>
        </div>
        <!--<button type="submit" class="btn btn-default" data-toggle="confirmation"  @click="publish">Publish</button>-->
    </div>
</template>

<script>
    import 'at.js';
    import 'jquery.caret';

    export default {
        props: ['data'],
        data(){
            return {
                body:''
            }
        },
        mounted(){
            $('#body').atwho({
                at: "@",
                delay: 500,
                callbacks: {
                    remoteFilter: function(query, callback) {
                        console.log('called');
                        $.getJSON("/api/users", {name: query}, function(data) {
                            callback(data)
                        });
                    }
                }
            })
        },
        methods:{
            publish(){
                var vm = this;

                axios.post(location.pathname + '/replies',{ body: this.body})
                    .catch(function(error){
                        if(typeof error.response.data === 'string'){
                            flash(error.response.data, 'danger');
                        } else {
                            flash(error.response.data.errors.body[0],'danger');
                        }
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