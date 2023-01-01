<template src="./showpurchase.html"></template>


<script>

import {PurchaseService} from "../purchase-service";
import {StoreService} from "../../../settings/store/store-service";
import hyperbutton from "../../../../components/html/hyperbutton.vue";
import button from "../../../../components/html/button.vue";


export default {

    components: {
        'app-button': button,
        'app-hyperbutton': hyperbutton,
    },

    computed: {

        purchaseOrderService()
        {
            return new PurchaseService(this.$api);
        },

        storeService()
        {
            return new StoreService(this.$api);
        }
    },

    data(){
        return {
            purchaseItems: [],
            purchase_total : 0,
            date : null,
            supplier : {},
            store : {},
            purchase : {
                id : 0,
                status : {
                    name :""
                }
            }
        }
    },

    props : ['id'],
    methods :{
        print(){
            window.print()
        },

        completeInvoice()
        {
            this.$refs.complete_button.toggleProcessing();
            this.purchaseOrderService.complete(this.purchase.id).then(
                (response)=>{
                    this.getPurchase();
                    this.$refs.complete_button.toggleProcessing();
                    this.$helper.success(this.$notify,"Purchase Order","Purchase Order Was completed successfully")
                })
        },

        getPurchase()
        {

            this.purchaseOrderService.show(this.id)
                .then((response)=>{
                    this.purchaseItems = response.data.data.items
                    this.purchase_total = response.data.data.total
                    this.date =  response.data.data.date
                    this.supplier = response.data.data.supplier
                    this.purchase = response.data.data
                });
        }
    },

    mounted(){

        this.storeService.get().then((response)=>{
            this.store =  response.data.data;
        });

        this.getPurchase();

    }

}

</script>
