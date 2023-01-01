<template src="./bank.html"></template>

<script>

import textbox from "../../../components/html/textbox.vue";
import button from "../../../components/html/button.vue";
import textarea from "../../../components/html/textarea.vue";
import select from "../../../components/html/select.vue";
import file from "../../../components/html/file.vue";
import {BankService} from "./bank-service";
import {CommercialBankService} from "./commercial-service";


export default {


    components: {
        'app-textbox': textbox,
        'app-button': button,
        'app-textarea': textarea,
        'app-file': file,
        'app-select' : select
    },

    data() {
        return {
            name: "",
            columns: ['bank','account_number','account_name' ,'status', 'created_at', 'action'],
            tableData: [],
            options: {
                perPage: 10,
                perPageValues: [100, 500, 1000, 300, -1],
                skin: 'table',
                columnsClasses: {actions: 'actions text-center'},
                pagination: {nav: 'scroll', chunk: 5},
                texts: {
                    count: 'Showing {from} to {to} of {count}',
                    filter: '',
                    filterPlaceholder: 'Search...',
                    limit: 'Results:',
                },
                sortable: ['id', 'name', 'age', 'action'],
                sortIcon: {
                    base: 'sort-icon-none',
                    up: 'sort-icon-asc',
                    down: 'sort-icon-desc',
                },
                resizableColumns: false,
            },
            update: {
                id: ""
            },
            update_name: "",
            update_controls: ['update_account_number','update_account_name','update_bank_id'],
            new_controls: ["account_number","account_name","bank_id"],
            commercialBanks : []
        }
    },


    computed: {
        bankService() {
            return new BankService(this.$api)
        },

        updateModal()
        {
            return  new bootstrap.Modal(document.getElementById('editBank'), {})
        },

        commercialbankservice() {
            return new CommercialBankService(this.$api)
        }

    },


    mounted() {
        this.commercialbankservice.get().then((response) => {
            this.commercialBanks = response.data.data;
        });
        this.get()
    },


    methods: {

        edit(bank) {

            this.update = bank;

            for (let key in bank) {

               if(typeof this.$refs["update_" + key] !== "undefined"){

                   this.$refs["update_" + key].setValue(bank[key]);

               }
            }

            this.updateModal.show();

        },

        updateBank() {
            if (this.$helper.validateSingle(this.$refs, this.update_controls) === false) {

                this.$refs.update_button.toggleProcessing();

                const storedata = new FormData;

                for (let key of this.update_controls) {

                    if (typeof this.$refs[key].valid !== "undefined") {

                        storedata.set(key.replace("update_",""), this.$refs[key].getValue());

                    }

                }

                this.bankService.update(storedata, this.update.id)
                    .then((response) => {
                        this.$refs.update_button.toggleProcessing();
                        this.get();
                        this.updateModal.hide();
                        this.$refs.updateform.reset()
                        this.$notify({
                            title: "Point",
                            type: "success",
                            text: "Manufacturer updated Successfully"
                        });
                    })
                    .catch((response) => {
                        this.$refs.update_button.toggleProcessing();
                    })

            }

        },

        toggle(id) {
            this.$refs.bank_table.setLoadingState(true);
            this.bankService.toggle(id).then((response) => {
                this.get();
            })
        },

        delete_row(id) {
            this.$refs.bank_table.setLoadingState(true);
            this.bankService.remove(id).then((response) => {
                this.get();
            })
        },

        get() {
            this.$refs.bank_table.setLoadingState(true);
            this.bankService.get().then((response) => {
                this.tableData = response.data.data;
                this.$refs.bank_table.setLoadingState(false);
            });
        },

        save() {

            if (this.$helper.validateSingle(this.$refs, this.new_controls) === false) {

                this.$refs.submit_button.toggleProcessing();

                const storedata = new FormData;

                for (let key of this.new_controls) {

                    if (typeof this.$refs[key].valid !== "undefined") {

                        storedata.set(key, this.$refs[key].getValue());

                    }

                }

                this.bankService.post(storedata)
                    .then((response) => {
                        this.$refs.submit_button.toggleProcessing();
                        this.get();
                        this.$refs.newform.reset()
                        this.$notify({
                            title: "Point",
                            type: "success",
                            text: "Manufacturer added Successfully"
                        });
                    })
                    .catch((response) => {
                        this.$refs.submit_button.toggleProcessing();
                    })

            }

        }

    }
}


</script>
