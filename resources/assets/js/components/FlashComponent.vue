<template>
    <div
          class="alert alert-style"
          :class="'alert-'+level"
          role="alert"
          v-show="show"
          v-text="body">
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: this.message,
                level: '',
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

            window.events.$on('flash', data => {
                this.flash(data);
            });
        },
        methods:{
            flash(data){
                this.body = data.message;
                this.level = data.level;
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
