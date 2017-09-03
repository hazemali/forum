<script>


    import Replies from '../components/replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';

    export default {

        props: ['initialRepliesCount'],
        components: {Replies , SubscribeButton},

        data(){
            return {
                repliesCount: this.initialRepliesCount
            }
        }


    }

</script>