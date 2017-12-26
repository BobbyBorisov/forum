<template>
    <div>
        <div v-for="(reply,index) in replies" :key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <p class="level-center mt-10" v-if="$parent.locked">This thread is locked by the administrator.</p>
        <new-reply v-else @created="add"></new-reply>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                dataSet: [],
                replies: [],
            };
        },
        created(){
          this.fetch()

        },
        methods:{
            remove(index){
                this.replies.splice(index,1);
                this.$emit('remove');
            },
            add(item){
                this.replies.push(item);
                this.$emit('add');
            },
            fetch(page){
                axios.get(this.url(page)).then(this.refresh);
            },
            url(page){
                if (!page){
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }

                return `${location.pathname}/replies?page=${page}`;
            },
            refresh({data}) {
                this.dataSet = data;
                this.replies = data.data;
                window.scrollTo(0, 0);
            }
        }
    }
</script>