<template src="./supplier.html"></template>

<script>

import textbox from "../../../components/html/textbox.vue";
import button from "../../../components/html/button.vue";
import textarea from "../../../components/html/textarea.vue";
import file from "../../../components/html/file.vue";
import {SupplierService} from "./supplier-service";


export default {


    components: {
        'app-textbox': textbox,
        'app-button': button,
        'app-textarea': textarea,
        'app-file': file
    },

    data() {
        return {
            name: "",
            columns: ['name','email','phonenumber','address' ,'status', 'action'],
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
            update_controls: ["update_name","update_email","update_address","update_phonenumber"],
            new_controls: ["name","email","address","phonenumber"]
        }
    },


    computed: {
        supplierService() {
            return new SupplierService(this.$api)
        },

        updateModal()
        {
            return  new bootstrap.Modal(document.getElementById('editSupplier'), {})
        },
        newModal()
        {
            return  new bootstrap.Modal(document.getElementById('newSupplier'), {})
        }


    },


    mounted() {

        this.get()
    },


    methods: {

        edit(supplier) {

            this.update = supplier;

            for (let key in supplier) {

               if(typeof this.$refs["update_" + key] !== "undefined"){

                   this.$refs["update_" + key].setValue(supplier[key]);

               }
            }

            this.updateModal.show();

        },

        updateSupplier() {
            if (this.$helper.validateSingle(this.$refs, this.update_controls) === false) {

                this.$refs.update_button.toggleProcessing();

                const storedata = new FormData;

                for (let key of this.update_controls) {

                    if (typeof this.$refs[key].valid !== "undefined") {

                        storedata.set(key.replace("update_",""), this.$refs[key].getValue());

                    }

                }

                this.supplierService.update(storedata, this.update.id)
                    .then((response) => {
                        this.$refs.update_button.toggleProcessing();
                        this.get();
                        this.$refs.updateform.reset();
                        this.updateModal.hide();
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
            this.$refs.supplier_table.setLoadingState(true);
            this.supplierService.toggle(id).then((response) => {
                this.get();
            })
        },

        delete_row(id) {
            this.$refs.supplier_table.setLoadingState(true);
            this.supplierService.remove(id).then((response) => {
                this.get();
            })
        },

        get() {
            this.$refs.supplier_table.setLoadingState(true);
            this.supplierService.get().then((response) => {
                this.tableData = response.data.data;
                this.$refs.supplier_table.setLoadingState(false);
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

                this.supplierService.post(storedata)
                    .then((response) => {
                        this.$refs.submit_button.toggleProcessing();
                        this.get();
                        this.$refs.newform.reset();
                        this.newModal.hide();
                        this.$notify({
                            title: "Point",
                            type: "success",
                            text: "Supplier added Successfully"
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
