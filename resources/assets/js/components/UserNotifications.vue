<template>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
            Notifications <span class="caret"></span>
        </a>

        <ul class="dropdown-menu">
            <li v-for="notification in notifications">
                <a :href="notification.data.link" v-text="notification.data.message"></a>
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

        }
    }
</script>