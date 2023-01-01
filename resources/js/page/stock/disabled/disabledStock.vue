
<template src="./disabledStock.html"></template>

<script>



import textbox from "../../../components/html/textbox.vue";
import button from "../../../components/html/button.vue";
import textarea from "../../../components/html/textarea.vue";
import select from "../../../components/html/select.vue";
import file from "../../../components/html/file.vue";
import {StockService} from "../stockService";
import {useUserStore} from "../../../store/user";

export default {

    components: {
        'app-textbox': textbox,
        'app-button': button,
        'app-textarea': textarea,
        'app-file': file,
        'app-select': select
    },

    data() {
        return {
            name: "",
            columns: [],
            tableData: [],
            options: {
                perPage: 10,
                perPageValues: [100, 500, 1000, 3000],
                skin: 'table',
                columnsClasses: {actions: 'actions text-center'},

                classes: {
                    wrapper: 'table-responsive',
                    table: 'table table-striped',
                    formControl: 'form-control',
                    sort: {
                        none: 'fa fa-sort',
                        ascending: 'fa fa-sort-asc',
                        descending: 'fa fa-sort-desc',
                    }
                },

                pagination: {nav: 'scroll', chunk: 5},
                texts: {
                    count: 'Showing {from} to {to} of {count}',
                    filter: '',
                    filterPlaceholder: 'Search...',
                    limit: 'Results:',
                },
                sortable: [],
                sortIcon: {
                    base: 'sort-icon-none',
                    up: 'sort-icon-asc',
                    down: 'sort-icon-desc',
                },
                resizableColumns: true,
            },
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

    mounted() {

        this.stockService.disabled().then((response) => {
            this.columns =  response.data.data.columns;
            this.tableData = response.data.data.data;

        });

    }

}

</script>
