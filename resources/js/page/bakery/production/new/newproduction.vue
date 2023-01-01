<template src="./newproduction.html"></template>

<style src="./newproduction.css"></style>

<script>


import _ from "lodash";
import textbox from "../../../../components/html/textbox.vue";
import button from "../../../../components/html/button.vue";
import textarea from "../../../../components/html/textarea.vue";
import file from "../../../../components/html/file.vue";
import select from "../../../../components/html/select.vue";
import {ProductionService} from "../production-service";

import Multiselect from '@suadelabs/vue3-multiselect';
import '@suadelabs/vue3-multiselect/dist/vue3-multiselect.css';
import {RawmaterialService} from "../../material/rawmaterial-service";



export default {


    components: {
        'app-textbox': textbox,
        'app-button': button,
        'app-textarea': textarea,
        'app-file': file,
        'app-select': select,
        Multiselect : Multiselect
    },

    computed: {

        materialService() {
            return new RawmaterialService(this.$api)
        },
        productService() {
            return new ProductionService(this.$api);
        },
        material_total(){
            let purchase_total = 0;
            this.materialItems.forEach((item,index) => {
                purchase_total += (item.quantity * item.cost_price);
            });
            return purchase_total;
        },
        prepare_data(){
            return {
                date : this.date,
                time: this.time,
                items : this.materialItems
            }
        }
    },

    data(){
        return {
            time : new Date().getTime(),
            date : new Date().toISOString().slice(0, 10),
            timeConfig : {
                enableTime: true,
                noCalendar: true,
                dateFormat: "U",
                altInput: true,
                altFormat: "h:i K",
            },
            selected: null,
            results: [],
            isLoading: false,
            materialItems : []
        }
    },

    methods : {
        async getItems(search) {
            if(search.length > 3) {
                this.isLoading = true
                this.search(search, this);
            }
        },

        search: _.debounce((search, vm) => {
            vm.materialService.availableSearch({query:search},).then((response) => {
                vm.results = response.data.data
                vm.isLoading = false
            });
        }, 350),

        removeItem(index){
            this.purchaseItems.splice(index,1);
        },

        addItem(){
            let existing = false;
            this.materialItems.forEach((item,index) => {
                if(item.id === this.selected.id){
                    existing = true;
                }
                this.material_total += (item.quantity * item.cost_price);
            });

            if(existing === true)
            {
                this.$notify(
                    {
                        title:"Error",
                        text:"Material Already exist in the list",
                        type: 'error',
                        duration: 2000,
                    }); return ;
            }


            if (this.$helper.validateSingle(this.$refs, ['quantity']) === false) {
                const item = {
                    id: this.selected.id,
                    cost_price: this.selected.cost_price,
                    quantity: this.$refs.quantity.getValue(),
                    name: this.selected.name,
                }

                this.$refs.quantity.valid = true;

                this.$refs.quantity.setValue("");

                this.materialItems.push(item);

                this.selected = null;

            }
        },

    },

   mounted() {

   }


}

</script>
