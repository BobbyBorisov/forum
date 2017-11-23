<script>
    export default {
        props:['data'],
        data(){
            return {
                repliesCount: this.data.replies_count
            }
        }
    }
</script>