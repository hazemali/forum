<template>
    <div>
        <div class="page-header">
            <div class="level">
                <img :src="avatar" width="50" height="50" class="mr-1"/>

                <h1 v-text="user.name"></h1>
            </div>

            <form v-if="canUpdate" method="post" enctype="multipart/form-data">
                <upload-image name="avatar" @loaded="onLoaded"></upload-image>
            </form>
        </div>
    </div>
</template>

<script>

    import UploadImage from './UploadImage.vue';

    export default {
        components: {UploadImage},

        props: ['user'],

        data() {
            return {
                avatar: this.user.avatar_path
            }
        },
        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            }
        },

        methods: {
            onLoaded(avatar) {

                this.avatar = avatar.src;
                this.persist(avatar.file);
            },

            persist(avatar) {

                let data = new FormData();
                data.append('avatar', avatar);
                axios.post(`/api/users/${this.user.id}/avatar`, data)
                    .then(() => flash('Avatar Uploaded!'));
            }
        }
    }
</script>