<template src="./dashboard.html"></template>

<style src="./dashboard.scss"></style>

<script>

import dashboard_service from "./dashboard-service";



export default {

    mounted() {
        dashboard_service.api = this;

    }


}

</script>
