<template>

    <div :id="'reply-'+id" class="panel panel-default" v-if="existence">
        <div class="panel-heading">

            <div class="level">
                <h5 class="flex">
                    <a :href="profileLink" v-text="this.data.owner.name"></a> said
                    <span v-text="ago"></span>
                </h5>

                <div>

                    <favorite v-show="signedIn" :reply="this.data"></favorite>

                </div>

            </div>
        </div>

        <div class="panel-body">


            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>

                    <div class="level">
                        <button class="btn btn-xs btn-primary">update</button>

                        <button type="button" class="btn btn-xs btn-link" @click="editing = false;body = original_body;">cancel
                        </button>
                    </div>
                </form>
            </div>


            <div v-else v-html="body"></div>

        </div>

        <div class="panel-footer level" v-show="canUpdate">


            <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>

            <button type="submit" @click="destroy" class="btn btn-danger btn-xs">
                Delete reply
            </button>
        </div>
    </div>


</template>
<script>

    import favorite from './favorite.vue';
    import moment from 'moment';

    export default {

        components: {favorite},

        props: ["data"],

        data() {
            return {
                editing: false,
                body: this.data.body,
                original_body: this.data.body,
                existence: true,
                id: this.data.id,
            };
        },
        computed: {
            profileLink: function () {
                return '/profiles/' + this.data.owner.name;
            },
            signedIn: function () {
                return window.app.signedIn;
            },

            canUpdate: function () {

                if (this.authorized) {
                    return true;
                }

                return this.authorize(user => user.id ==  this.data.user_id);
            },

            authorized: function () {

                if (typeof(this.data.Authorized) != undefined) {
                    return this.data.Authorized;
                }
                return false;
            },
            ago: function () {
                return moment(this.data.created_at).fromNow() + '...';
            }

        },
        methods: {
            update() {

                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                }).then(response => {

                    this.editing = false;
                    this.original_body = this.body;

                    flash('the reply was updated!');
                }).catch(error => {

                    this.editing = true;

                    flashError(error.response.data, 'error');

                });


            },

            destroy() {

                axios.delete('/replies/' + this.id);

                this.$emit('deleted', this.id);

            }


        }
    }
</script>