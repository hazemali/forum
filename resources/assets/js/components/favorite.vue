<template>
    <button type="button" :class="classes" @click="toggle">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="favoritesCount"></span>
    </button>
</template>
<script>

    export default {

        props: ["reply"],

        data(){
            return {
                data: this.reply,
                favoritesCount: this.reply.FavoritesCount,
                isFavorited: this.reply.IsFavorited,
            }
        },

        computed: {

            classes (){
                return ['btn', this.isFavorited ? 'btn-primary' : 'btn-default'];
            } ,

            endpoint(){

                return "/replies/" + this.reply.id + "/favorites";
            }
        },

        methods: {

            toggle (){

                if (this.isFavorited) {
                    return this.destroy();

                } else {

                    this.create();
                }

            },

            create(){

                axios.post(this.endpoint);

                this.isFavorited = true;
                this.favoritesCount++;

                flash('A reply was favorited!');
            },
            destroy(){

                axios.delete(this.endpoint);


                    this.isFavorited = false;
                    this.favoritesCount--;
                    flash('A reply was un favorited!');



            }

        }

    }

</script>