<template src="./store.html"></template>


<script>

import  {StoreService} from "./store-service";


import textbox from "../../../components/html/textbox.vue";
import button  from "../../../components/html/button.vue";
import textarea from "../../../components/html/textarea.vue";
import file from "../../../components/html/file.vue";


export default {

    components:{
        'app-textbox' : textbox,
        'app-button' : button,
        'app-textarea' : textarea,
        'app-file' : file
    },

    data() {
        return {
            name : "",
            branch_name : "",
            vat  : "",
            address_1 : "",
            address_2 : "",
            contact_number : "",
            receipt_notes : "",
            logo : undefined,

        }
    },


    computed: {
        storeService() {
            return new StoreService(this.$api);
        }
    },

    methods : {

        save () {

            if(this.$helper.validate(this.$refs) === false)
            {

                this.$refs.submit_button.toggleProcessing();

                const storedata = new FormData;

                for(let key in this.$refs)
                {
                    if(typeof this.$refs[key].valid !== "undefined"){

                        storedata.set(key,this.$refs[key].getValue());

                    }

                }


                this.storeService.post(storedata)
                    .then((response) => {
                        this.$refs.submit_button.toggleProcessing();
                        this.$notify({
                            title : "Point",
                            type: "success",
                            text : "Store Data Saved Successfully"
                        });
                    })
                    .catch((response)=>{
                        this.$refs.submit_button.toggleProcessing();
                    })
            }

        }

    },

    mounted() {
        this.storeService.get().then((response)=> {
            for(let _key in response.data.data)
            {
               if(typeof this.$refs[_key].setValue === "function"){

                   this.$refs[_key].setValue(response.data.data[_key]);
               }
            }

        });

    }

}

</script>
