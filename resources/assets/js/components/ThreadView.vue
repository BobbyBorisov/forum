<script>
    export default {
        props:['data'],
        data(){
            return {
                repliesCount: this.data.replies_count,
                locked:this.data.isLocked,
                editing:false,
                title: this.data.title,
                body: this.data.body,
                form: {}
            }
        },
        created(){
            this.resetForm();
        },
        methods:{
            toggleLock(){
                axios[this.locked ? 'delete' : 'patch']('/lock-thread/'+this.data.slug+'/lock')
                this.locked = !this.locked;
            },
            update(){
                let url = '/threads/'+this.data.channel.slug+'/'+this.data.slug;
                axios.patch(url, this.form).then(()=>{
                   this.editing = false;
                   this.title = this.form.title;
                   this.body = this.form.body;
                });
            },
            resetForm(){
                this.form = {
                    title: this.data.title,
                    body: this.data.body
                };

                this.editing = false;
            }
        }
    }
</script>