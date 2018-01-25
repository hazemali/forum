<template>

    <div v-if="signedIn">

        <div class="form-group">
               <textarea name="body"
                         placeholder="Have something to say?"
                         id="body"
                         rows="5"
                         class="form-control"
                         v-model="body"
                         required></textarea>
        </div>

        <button type="submit" class="btn btn-default"
                @click="addReply">post
        </button>

    </div>
    <div v-else>

        <p class="text-center">Please <a href="/login">sign in</a> to participate in this
            discussion</p>

    </div>

</template>

<script>

    import 'jquery.caret';
    import 'at.js';

    export default
    {

        props:['endpoint'],

        data(){
            return {
                body: null
            }
        },

        mounted(){

            $('#body').atwho({
                at: "@",
                delay: 300,
                callbacks: {

                    remoteFilter: function(query, callback) {

                        $.getJSON("/Api/users", {name: query}, function(data) {
                            callback(data)
                        });
                    }
                }
            });

        },
        computed: {
            signedIn: function () {
                return window.app.signedIn;
            },

        },

        methods: {
            addReply: function () {
                axios.post(this.endpoint, {body: this.body}).then(response => {

                        this.body = '';
                        flash('your reply has been posted');

                        this.$emit('created', response.data);


                }).catch(error => {

                    flashError(error.response.data,'error');
                });
            }
        }
    }
</script>