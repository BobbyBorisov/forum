<template>
    <div>
        <button
                class="btn btn-default"
                :class="isSubscribed ? 'btn-danger' : 'btn-success'"
                    @click="toggle"
        >Subscribe</button>
    </div>
</template>

<script>
    export default {
        props: ['active'],
        data(){
            return {
                isSubscribed:this.active
            }
        },
        methods:{
            toggle(){
                this.isSubscribed ? this.unsubscribe() : this.subscribe();
            },
            subscribe(){
                axios.post(location.pathname+"/subscriptions",[]);
                this.isSubscribed = true;
            },
            unsubscribe(){
                axios.delete(location.pathname+"/subscriptions",[]);
                this.isSubscribed = false;
            }
        }
    }
</script>