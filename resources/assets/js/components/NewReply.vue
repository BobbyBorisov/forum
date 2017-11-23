<template>
    <div>
        <div class="form-group">
            <textarea name="body" v-model="body" id="body" class="form-control" placeholder="Have something to say?"></textarea>
        </div>
        <button type="submit" class="btn btn-default" @click="publish">Publish</button>
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
                    .then(function(response){
                        console.log(response);
                        vm.$emit('created', response.data);
                        vm.body='';
                    });
            }
        }
    }
</script>