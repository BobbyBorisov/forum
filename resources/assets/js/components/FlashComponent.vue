<template>
    <div class="alert alert-success alert-style" role="alert" v-show="show">
        <strong>Success!</strong> {{body}}
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: this.message,
                show: false
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            if (this.message){
                this.flash(this.message);
            }

            window.events.$on('flash', message => {
                this.flash(message);
            });
        },
        methods:{
            flash(message){
                this.body = message;
                this.show = true;

                this.hide();
            },
            hide(){
                setTimeout(()=>{
                    this.show=false;
                },3000);
            }
        }
    }
</script>

<style>
    .alert-style{
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>
