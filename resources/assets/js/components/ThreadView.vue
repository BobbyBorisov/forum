<script>
    export default {
        props:['data'],
        data(){
            return {
                repliesCount: this.data.replies_count,
                locked:this.data.isLocked,
            }
        },
        methods:{
            toggleLock(){
                console.log('toggle lock');
                axios[this.locked ? 'delete' : 'patch']('/lock-thread/'+this.data.slug+'/lock')
                this.locked = !this.locked;
                console.log('locked '+this.locked);
            }
        }
    }
</script>