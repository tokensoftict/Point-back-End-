<template src="./manufacturer.html"></template>

<script>

import textbox from "../../../components/html/textbox.vue";
import button from "../../../components/html/button.vue";
import textarea from "../../../components/html/textarea.vue";
import file from "../../../components/html/file.vue";
import {ManufacturerService} from "./manufacturer-service";
import {shallowRef} from "vue";

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
            columns: ['name', 'status', 'created_at', 'action'],
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
            update_controls: ['update_name'],
            new_controls: ["name"]
        }
    },


    computed: {
        manufacturerService() {
            return new ManufacturerService(this.$api)
        },

        updateModal()
        {
            return  new bootstrap.Modal(document.getElementById('editManufacturer'), {})
        }

    },


    mounted() {

        this.get()
    },


    methods: {

        edit(manufacturer) {

            this.update = manufacturer;

            for (let key in manufacturer) {

               if(typeof this.$refs["update_" + key] !== "undefined"){

                   this.$refs["update_" + key].setValue(manufacturer[key]);

               }
            }

            this.updateModal.show();

        },

        updateManufacturer() {
            if (this.$helper.validateSingle(this.$refs, this.update_controls) === false) {

                this.$refs.update_button.toggleProcessing();

                const storedata = new FormData;

                for (let key of this.update_controls) {

                    if (typeof this.$refs[key].valid !== "undefined") {

                        storedata.set(key.replace("update_",""), this.$refs[key].getValue());

                    }

                }

                this.manufacturerService.update(storedata, this.update.id)
                    .then((response) => {
                        this.$refs.update_button.toggleProcessing();
                        this.get();
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
            this.$refs.manufacturer_table.setLoadingState(true);
            this.manufacturerService.toggle(id).then((response) => {
                this.get();
            })
        },

        delete_row(id) {
            this.$refs.manufacturer_table.setLoadingState(true);
            this.manufacturerService.remove(id).then((response) => {
                this.get();
            })
        },

        get() {
            this.$refs.manufacturer_table.setLoadingState(true);
            this.manufacturerService.get().then((response) => {
                this.tableData = response.data.data;
                this.$refs.manufacturer_table.setLoadingState(false);
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

                this.manufacturerService.post(storedata)
                    .then((response) => {
                        this.$refs.submit_button.toggleProcessing();
                        this.get();
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
