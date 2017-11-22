<template>
    <div>
        <div v-for="reply in replies">
            <reply :data="reply"></reply>
        </div>
    </div>
</template>

<script>
    export default {
        props:['data'],
        data(){
            return {
                replies: this.data
            };
        }
    }
</script>