
<template src="./newStock.html"></template>

<script>




import textbox from "../../../components/html/textbox.vue";
import button from "../../../components/html/button.vue";
import textarea from "../../../components/html/textarea.vue";
import file from "../../../components/html/file.vue";
import select from "../../../components/html/select.vue";
import {StockService} from "../stockService";
import {useUserStore} from "../../../store/user";

export default {

    components: {
        'app-textbox': textbox,
        'app-button': button,
        'app-textarea': textarea,
        'app-file': file,
        'app-select': select,
    },


    data(){
        return {
            categories : [],
            manufacturers : [],
            classifications : [],
            groups : [],
            brands : [],
            suppliers : []
        }
    },

    computed :{

        stockService(){

            return new StockService(this.$api)
        },

        systemSettings()
        {
            return useUserStore().refresh().settings.store
        }

    },

    props: ["id"],

    methods : {

        getRequisites()
        {
            this.stockService.getRequesiteData().then((response) => {
                const data = response.data.data;

                this.categories = data.categories;
                this.manufacturers = data.manufacturers;
                this.classifications = data.classifications;
                this.groups = data.groups;
                this.brands = data.brands
                this.suppliers = data.suppliers
            })
        },


        saveData()
        {
           if(this.$helper.validate(this.$refs) === false)
           {
               this.$refs.save_button.toggleProcessing();

               const storedata = new FormData;

               for(let key in this.$refs)
               {
                   if(typeof this.$refs[key].valid !== "undefined" && this.$refs[key].getValue() !== ""){

                       storedata.set(key,this.$refs[key].getValue());

                   }

               }

               this.stockService.save(storedata, this.id).then( (response)  =>{

                   this.$refs.save_button.toggleProcessing();

                   if(this.id === undefined)
                   {
                       document.getElementById("myform").reset();
                       this.$helper.success(this.$notify,"Stock","New Stock has been created successfully!");
                   }
                   else
                   {
                       this.$helper.success(this.$notify,"Stock","Stock has been updated successfully!");
                   }

               });
           }
        }

    },


    mounted() {

        this.getRequisites();

        if(this.id !== undefined)
        {
            this.stockService.show(this.id).then((response)=>{
                const data = response.data.data;

                for(let key in this.$refs)
                {
                    if(typeof this.$refs[key].valid !== "undefined"){

                        this.$refs[key].setValue(data[key])
                    }

                }

            });
        }

    }
}

</script>
