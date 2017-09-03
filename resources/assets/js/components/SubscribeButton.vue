<template>
    <div v-if="signedIn">
        <button class="btn"
                :class="classes"
                @click="subscribeOrUnsubscribe"
                v-text="buttonText"></button>
    </div>
</template>

<script>

    export default {

        computed: {

            signedIn () {
                return window.app.signedIn;
            },

            classes (){
                return ['btn', this.active ? 'btn-primary' : 'btn-default'];
            },
            buttonText (){
                return this.active ? 'Un subscribe' : 'Subscribe';
            }
        },


        props: ['active'],

        methods: {

            subscribeOrUnsubscribe(){

                if(!this.active)
                    return this.subscribe();

                return this.unsubscribe();

            },
            subscribe() {

                let vm = this;

                axios.post(location.pathname + '/subscriptions').then(function () {

                    vm.active = true;
                    flash('Subscribed!');

                });


            },

            unsubscribe(){

                let vm = this;

                axios.delete(location.pathname + '/subscriptions').then(function () {

                    vm.active = false;
                    flash('Un subscribed!');

                });
            }

        }

    }
</script>