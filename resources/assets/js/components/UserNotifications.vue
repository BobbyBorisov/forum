<template>
    <li class="dropdown" v-if="notifications.length>0">
        <a href="#" class="dropdown-toggle glyphicon glyphicon-bell" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
             <span class="caret"></span>
        </a>

        <ul class="dropdown-menu">
            <li v-for="notification in notifications">
                <a :href="notification.data.link" :key="notification.id" v-text="notification.data.message" @click="markAsRead(notification.id)"></a>
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        data(){
            return {
                notifications: []
            }
        },
        created(){
            var vm = this;

            axios.get('/profile/'+window.App.user.id+'/notifications')
                .then(function(response){
                    vm.notifications = response.data;
                });

        },
        methods:{
            markAsRead(id){
                axios.post('/profile/'+window.App.user.id+"/notifications/"+id,[]);
                this.notifications.splice(id,1);
            }
        }
    }
</script>