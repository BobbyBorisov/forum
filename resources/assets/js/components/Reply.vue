<script>
    export default {
        props:['attributes'],
        data() {
            return {
                editing:false,
                body: this.attributes.body
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