<template>


    <li class="dropdown" v-if="data.length">

        <a href="#" class="dropdown-toggle" data-toggle="dropdown">

            <span class="glyphicon glyphicon-bell"></span>
        </a>

        <ul class="dropdown-menu">

            <li v-for="notification in data">

                <a :href="notification.data.link" v-text="notification.data.message"
                   @click="markAsRead(notification)"></a>

            </li>
        </ul>
    </li>


</template>
<script>

    export default {


        data (){

            return {
                data : false
            }
        },


        created(){

            axios.get('/notifications').then(response => this.data = response.data);

        } ,

        methods: {

            markAsRead(notification){

                axios.delete('/notifications/'+notification.id);

            }

        }


    }

</script>