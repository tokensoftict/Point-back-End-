<template src="./app-layout.html"></template>

<script>

    import Header from '../../components/layout/header/header.vue'
    import Footer from '../../components/layout/footer/footer.vue'
    import Sidebar from '../../components/layout/sidebar/sidebar.vue'
    import Page from './page'


    export default {
        components : {
            Header,
            Footer,
            Sidebar,
            ...Page
        }

    }
</script>


