<template src="./customer.html"></template>

<script>

import textbox from "../../../components/html/textbox.vue";
import button from "../../../components/html/button.vue";
import textarea from "../../../components/html/textarea.vue";
import select from "../../../components/html/select.vue";
import file from "../../../components/html/file.vue";
import {CustomerService} from "./customer-service";


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
            columns: ['firstname','lastname','company_name' ,'email', 'address','phone_number', 'action'],
            tableData: [],
            options: {
                perPage: 10,
                perPageValues: [100, 500, 1000, 3000],
                skin: 'table',
                columnsClasses: {actions: 'actions text-center'},
                pagination: {nav: 'scroll', chunk: 5},
                texts: {
                    count: 'Showing {from} to {to} of {count}',
                    filter: '',
                    filterPlaceholder: 'Search...',
                    limit: 'Results:',
                },
                sortable: ['id', 'type', 'action'],
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
            update_controls: ['update_firstname','update_lastname','update_company_name' ,'update_email', 'update_address','update_phone_number'],
            new_controls: ['firstname','lastname','company_name' ,'email', 'address','phone_number'],
        }
    },


    computed: {
        customerService() {
            return new CustomerService(this.$api)
        },

        updateModal()
        {
            return  new bootstrap.Modal(document.getElementById('editCustomer'), {})
        }
        ,
        newModal()
        {
            return  new bootstrap.Modal(document.getElementById('newCustomer'), {})
        }

    },


    mounted() {
        this.get()
    },


    methods: {

        edit(customer) {

            this.update = customer;

            for (let key in customer) {

                if(typeof this.$refs["update_" + key] !== "undefined"){

                    this.$refs["update_" + key].setValue(customer[key]);

                }
            }

            this.updateModal.show();

        },

        updateCustomer() {
            if (this.$helper.validateSingle(this.$refs, this.update_controls) === false) {

                this.$refs.update_button.toggleProcessing();

                const storedata = new FormData;

                for (let key of this.update_controls) {

                    if (typeof this.$refs[key].valid !== "undefined") {

                        storedata.set(key.replace("update_",""), this.$refs[key].getValue());

                    }

                }

                this.customerService.update(storedata, this.update.id)
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
            this.$refs.customer_table.setLoadingState(true);
            this.customerService.toggle(id).then((response) => {
                this.get();
            })
        },

        delete_row(id) {
            this.$refs.customer_table.setLoadingState(true);
            this.customerService.remove(id).then((response) => {
                this.get();
            })
        },

        get() {
            this.$refs.customer_table.setLoadingState(true);
            this.customerService.get().then((response) => {
                this.tableData = response.data.data;
                this.$refs.customer_table.setLoadingState(false);
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

                this.customerService.post(storedata)
                    .then((response) => {
                        this.$refs.submit_button.toggleProcessing();
                        this.get();
                        this.newModal.hide();
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
