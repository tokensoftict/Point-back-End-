import { createApp } from 'vue';
import App from './App.vue';
import router from './layouts/app/router';

const app = createApp(App);

import { createPinia } from 'pinia'

import Notifications from '@kyvg/vue3-notification'

import VueProgressBar from "@aacassandra/vue3-progressbar";

import { useUserStore } from './store/user'

export const pinia = createPinia()


// bootstrap
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// modals

// perfect scrollbar
import PerfectScrollbar from 'vue3-perfect-scrollbar';
import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css';

//vue-meta
import { createHead } from '@vueuse/head';
const head = createHead();

//Sweetalert
import Swal from 'sweetalert2';
window.Swal = Swal;


// vue input mask
import Maska from 'maska';

// smooth scroll
import { registerScrollSpy } from 'vue3-scroll-spy/dist/index';
registerScrollSpy(app, { offset: 118 });


// datatables
import {ServerTable, ClientTable, EventBus} from 'v-tables-3';

import _ from 'lodash';

// json to excel
import vue3JsonExcel from 'vue3-json-excel';

import api from './interceptor/interceptors'

import helper from  "./interceptor/validation"

//vue-wizard
import VueFormWizard from 'vue3-form-wizard';
import 'vue3-form-wizard/dist/style.css';

import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

import Autocomplete from 'vue3-autocomplete'
import 'vue3-autocomplete/dist/vue3-autocomplete.css'



import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';




app
    .use(router)
    .use(PerfectScrollbar)
    .use(Maska)
    .use(ClientTable)
    .use(vue3JsonExcel)
    .use(VueFormWizard)
    .use(pinia)
    .use(head)
    .use(VueProgressBar, {
        color: 'rgb(143, 255, 199)',
        failedColor: 'red',
        height: '2px'
    })
    .use(Notifications);

    app.config.globalProperties.$api = api;
    app.config.globalProperties.$_ = _;
    app.config.globalProperties.$user = useUserStore();
    app.config.globalProperties.$helper = helper;

    app.config.globalProperties.$currency = function(value) {
    let val = (value / 1).toFixed(2).replace(".", ",");
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

    app.component("flatPickr",flatPickr);
    app.component('Autocomplete', Autocomplete)
    app.component('v-select', vSelect)
    app.mount('#app');

