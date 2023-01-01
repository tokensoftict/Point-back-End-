
<template src="./listStock.html"></template>

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

        this.stockService.get().then((response) => {

            this.tableData = response.data.data.data;
            this.columns =  response.data.data.columns;
        });

    }

}

</script>
