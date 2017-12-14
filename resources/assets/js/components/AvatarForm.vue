<template>
    <div style="margin-bottom:30px">
        <div class="level" >
            <img :src="avatar" style="margin-right:10px" width="50" height="50">
            <h1 v-text="user.name"></h1>
        </div>
        <image-upload v-if="canUpdate" name="avatar" @loaded="onLoad"></image-upload>
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue';

    export default {
        props: ['user'],
        components: {ImageUpload},
        data(){
            return {
                avatar: this.user.avatar_path
            }
        },
        computed:{
            canUpdate(){
                return this.authorize(user => user.id == this.user.id)
            }
        },
        methods:{
            onLoad(avatar){
                console.log(avatar);
                this.avatar = avatar.src;

                this.persist(avatar.file);
            },
            persist(avatar){
                let data = new FormData();
                data.append('avatar', avatar);

                axios.post('/api/users/'+this.user.id+'/avatar',data)
                    .then(()=>{
                        console.log('flash message');
                    });
            }
        }
    }
</script>