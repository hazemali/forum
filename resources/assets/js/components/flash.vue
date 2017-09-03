<template>

    <div :class="['alert  alert-flash',classes]" role="alert" v-show="show">

        <strong v-if="error == false">Success!</strong>
        <strong v-else>Error!</strong>

        {{ body }}
    </div>

</template>

<script>
    export default {

        props: ['message'],

        computed:{

            classes: function(){

                return this.error == true? 'alert-danger' : 'alert-success';

            }


        },

        data(){

            return {
                body: '',
                show: false,
                error:false
            }
        },

        created(){
            if (this.message) {
                this.flash(this.message);
            }

            window.events.$on('flash', message => this.flash(message));

            window.events.$on('flashError', message => this.flashError(message));

        },

        methods: {

            flashError (message) {

                this.error = true;
                this.body = message;
                this.show = true;
                this.hide();
            },

            flash (message) {
                this.error = false;
                this.body = message;
                this.show = true;
                this.hide();
            },

            hide () {

                setTimeout(() => {
                    this.show = false;
                }, 4000);

            }
        }

    }
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>

