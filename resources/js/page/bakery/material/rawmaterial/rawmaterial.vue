<template src="./rawmaterial.html"></template>

<script>




import textbox from "../../../../components/html/textbox.vue";
import button from "../../../../components/html/button.vue";
import textarea from "../../../../components/html/textarea.vue";
import file from "../../../../components/html/file.vue";
import select from "../../../../components/html/select.vue";
import {RawmaterialService} from "../rawmaterial-service";
import {RawmaterialtypeService} from "../rawmaterialtype-service";

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
            columns: ['name','description','type' ,'status', 'created_at', 'action'],
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
            update_controls: ['update_name','update_description','update_materialtype_id'],
            new_controls: ["name","description","materialtype_id"],
            rawmaterialtypes : []
        }
    },


    computed: {
        materialService() {
            return new RawmaterialService(this.$api)
        },

        updateModal()
        {
            return  new bootstrap.Modal(document.getElementById('editMaterial'), {})
        },

        rawmaterialtypeservice() {
            return new RawmaterialtypeService(this.$api)
        }

    },


    mounted() {
        this.rawmaterialtypeservice.get().then((response) => {
            this.rawmaterialtypes = response.data.data;
        });
        this.get()
    },


    methods: {

        edit(rawmaterial) {

            this.update = rawmaterial;

            for (let key in rawmaterial) {

               if(typeof this.$refs["update_" + key] !== "undefined"){

                   this.$refs["update_" + key].setValue(rawmaterial[key]);

               }
            }

            this.updateModal.show();

        },

        updateMaterial() {
            if (this.$helper.validateSingle(this.$refs, this.update_controls) === false) {

                this.$refs.update_button.toggleProcessing();

                const storedata = new FormData;

                for (let key of this.update_controls) {

                    if (typeof this.$refs[key].valid !== "undefined") {

                        storedata.set(key.replace("update_",""), this.$refs[key].getValue());

                    }

                }

                this.materialService.update(storedata, this.update.id)
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
            this.$refs.material_table.setLoadingState(true);
            this.materialService.toggle(id).then((response) => {
                this.get();
            })
        },

        delete_row(id) {
            this.$refs.material_table.setLoadingState(true);
            this.materialService.remove(id).then((response) => {
                this.get();
            })
        },

        get() {
            this.$refs.material_table.setLoadingState(true);
            this.materialService.get().then((response) => {
                this.tableData = response.data.data;
                this.$refs.material_table.setLoadingState(false);
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

                this.materialService.post(storedata)
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
