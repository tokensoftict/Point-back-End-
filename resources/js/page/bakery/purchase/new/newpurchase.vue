<template src="./newpurchase.html"></template>

<style src="./newpurchase.css"></style>

<script>


import _ from "lodash";
import textbox from "../../../../components/html/textbox.vue";
import button from "../../../../components/html/button.vue";
import textarea from "../../../../components/html/textarea.vue";
import file from "../../../../components/html/file.vue";
import {PurchaseService} from "../purchase-service";
import {SupplierService} from "../../../settings/supplier/supplier-service";
import select from "../../../../components/html/select.vue";
import {RawmaterialService} from "../../material/rawmaterial-service";





export default {


    components: {
        'app-textbox': textbox,
        'app-button': button,
        'app-textarea': textarea,
        'app-file': file,
        'app-select': select,
    },

    computed: {
        materialService() {
            return new RawmaterialService(this.$api)
        },
        purchaseOrderService()
        {
            return new PurchaseService(this.$api);
        },

        supplierService() {
            return new SupplierService(this.$api);
        },

        purchase_total(){
            let purchase_total = 0;
            this.purchaseItems.forEach((item,index) => {
                purchase_total += (item.quantity * item.cost_price);
            });
            return purchase_total;
        },
        prepare_data(){
            return {
                date_created : this.purchase_date,
                supplier_id : this.$refs.supplier_id.getValue(),
                items : this.purchaseItems
            }
        }
    },

    data(){
        return {
            results : [],
            loaderState: false,
            selected : null,
            purchaseItems : [],
            suppliers : [],
            expiry_date: new Date().toISOString().slice(0, 10),
            ItemSelected : null,
            purchase_date : new Date().toISOString().slice(0, 10)
        }
    },

    props : ['purchase_order','id'],

    methods : {

        clear_data(){
            this.purchase_date = new Date().toISOString().slice(0, 10),
            this.$refs.supplier_id.setValue(""),
            this.purchaseItems = [];
            this.expiry_date = new Date().toISOString().slice(0, 10);
        },

        async getItems(search, loading) {
            if(search.length) {
                loading(true);
                this.search(loading, search, this);
            }
        },

        search: _.debounce((loading, search, vm) => {
            vm.materialService.search({query:search},).then((response) => {
                vm.results = response.data.data
                loading(false)
            });
        }, 350),

        removeItem(index){
            this.purchaseItems.splice(index,1);
        },

        addItem(){
            let existing = false;
            this.purchaseItems.forEach((item,index) => {
                if(item.id === this.selected.id){
                    existing = true;
                }
                this.purchase_total += (item.quantity * item.cost_price);
            });

            if(existing === true)
            {
                this.$notify(
                {
                    title:"Error",
                    text:"Item Already exist in the list",
                    type: 'error',
                    duration: 2000,
                }); return ;
            }

            if(this.expiry_date === "")
            {
                this.$helper.error(this.$notify,"Expiry Data","Please select Expiry Date");

                return;
            }


            if (this.$helper.validateSingle(this.$refs, ['cost_price','quantity']) === false) {
                const item = {
                    id: this.selected.id,
                    cost_price: this.$refs.cost_price.getValue(),
                    quantity: this.$refs.quantity.getValue(),
                    name: this.selected.name,
                    expiry_date : this.expiry_date
                }

                this.$refs.cost_price.valid = true;
                this.$refs.quantity.valid = true;

                this.$refs.cost_price.setValue("");
                this.$refs.quantity.setValue("");

                this.purchaseItems.push(item);

                this.expiry_date = new Date().toISOString().slice(0, 10)

                this.selected = null;

            }
        },

        completePurchase(){
            if (this.$helper.validateSingle(this.$refs,['supplier_id',]) === false)
            {

                if(this.purchase_date === "") {
                    this.$helper.error(this.$notify,"Purchase Date","Please Select a Purchase Date");
                    return;
                }

                if(this.purchaseItems.length === 0){
                    this.$helper.error(this.$notify,"Empty Purchase Items","Please add one or more Purchased Items");
                    return;
                }

                this.$refs.complete_purchase.toggleProcessing();
                this.$refs.draft_purchase.toggleProcessing();


                this.prepare_data['complete_purchase'] = 1;

                this.purchaseOrderService.post(this.prepare_data, this.id).then((response)=>{
                    this.$refs.complete_purchase.toggleProcessing();
                    this.$refs.draft_purchase.toggleProcessing();
                    this.clear_data();

                    this.$helper.success(this.$notify,"Purchase Order","Purchase Order has been Completed Successfully");

                    setTimeout(()=>{
                        this.$router.push({name : 'show-purchase-Material', params: {id: response.data.data.id}});
                    },1000)

                });

            }
        },
        draftPurchase(){
            if (this.$helper.validateSingle(this.$refs,['supplier_id',]) === false)
            {

                if(this.purchase_date === "") {
                    this.$helper.error(this.$notify,"Purchase Date","Please Select a Purchase Date");
                    return;
                }

                if(this.purchaseItems.length === 0){
                    this.$helper.error(this.$notify,"Empty Purchase Items","Please add one or more Purchased Items");
                    return;
                }

                this.$refs.complete_purchase.toggleProcessing();
                this.$refs.draft_purchase.toggleProcessing();


                this.purchaseOrderService.post(this.prepare_data, this.id).then((response)=>{
                    this.$refs.complete_purchase.toggleProcessing();
                    this.$refs.draft_purchase.toggleProcessing();
                    this.clear_data();
                    this.$helper.success(this.$notify,"Purchase Order","Purchase Order has been Drafted Successfully");

                });

            }
        },

        getPurchase()
        {

            this.purchaseOrderService.show(this.id)
                .then((response)=>{
                    console.log(response.data.data.date);
                    this.purchaseItems = response.data.data.items
                    this.purchase_date =  new Date(response.data.data.date).toISOString().slice(0, 10);
                    this.$refs.supplier_id.setValue(response.data.data.supplier.id)
                });
        }


    },

   mounted() {
        this.supplierService.get().then((response)=>{
            this.suppliers = response.data.data;
        })

       if(this.id !== undefined)
       {
           this.getPurchase();
       }
   }


}

</script>
