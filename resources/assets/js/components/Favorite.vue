<template>
    <div>
        <button @click="toggle" class="btn btn-xs" :class="classes">Favorite</button>
    </div>
</template>

<script>
    export default {
        props:['data'],
        data(){
            return {
                id:this.data.id,
                isFavorited:this.data.isFavorited,
                endpoint:''
            }
        },
        computed:{
          classes(){
             return this.isFavorited ? 'btn-danger' : 'btn-success';
          }
        },
        methods:{
            toggle(){
                this.isFavorited ? this.unfavorite() : this.favorite();
            },
            favorite(){
                console.log('favorite request');
                var vm = this;

                axios.post('/replies/'+this.id+'/favorites',[]).then(function(){
                    vm.isFavorited=true;
                    flash('You favorited a reply')
                }).catch(function(error){
                    console.log(error);
                    flash('there was an issue');
                });
            },
            unfavorite(){
                console.log('unfavorite request');

                var vm = this;
                axios.delete('/replies/'+this.id+'/favorites').then(function(){
                    vm.isFavorited=false;
                    flash('You unfavorited a reply')
                }).catch(function(error){
                    console.log(error);
                    flash('there was an issue');
                });
            }
        }
    }
</script>
