export default {

    data(){
        return {
            items: []
        }

    },
    methods: {
        remove (id) {

            this.items = this.items.filter(function (data) {

                if (data.id != id) {
                    return data;
                }
            });


            this.$emit('removed', id);
        },

        add(item){
            item.Authorized = true;
            this.items.push(item);
            this.$emit('added');
        }
    }

}