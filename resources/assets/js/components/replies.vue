<template>
    <div>
        <div v-for="(Reply,index) in items">

            <reply :data="Reply" @deleted="remove" :key="Reply.id"></reply>

        </div>

        <paginator :dataSet="dataSet" @updated="fetch"></paginator>

        <new-reply :endpoint="endpoint" @created="add"></new-reply>

    </div>

</template>


<script>

    import Reply from './reply.vue';
    import newReply from '../components/newReply.vue';

    import collection from '../mixins/Collection';

    export default{

        components: {Reply, newReply},

        mixins: [collection],


        data(){
            return {
                dataSet: false,
                endpoint: location.pathname + '/replies'
            }
        },


        created(){

            var page = location.search.match(/page=(\d+)/) ;

             page = page ? page[1] : 1;

            this.fetch(page);
        },
        methods: {

            url(page){

                return `${location.pathname}/replies?page=${page}`;
            },
            fetch (page){

                axios.get(this.url(page))
                    .then(this.refresh);

            },
            refresh (response){

                this.dataSet = response.data;
                this.items = this.dataSet.data;

                window.scroll(0,0);

            }
        }


    }
</script>