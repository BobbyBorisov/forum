<template>
    <div>
        <div v-for="(reply,index) in replies" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <new-reply @created="add"></new-reply>
    </div>
</template>

<script>
    export default {
        props:['data'],
        data(){
            return {
                replies: this.data
            };
        },
        methods:{
            remove(index){
                this.replies.splice(index,1);
                this.$emit('remove');
            },
            add(item){
                this.replies.push(item);
                this.$emit('add');
            }
        }
    }
</script>