import { createRouter, createWebHistory } from 'vue-router';

import Auth from "../../page/auth/auth.vue"
import AppLayout from "./app-layout.vue";
import { useUserStore } from '../../store/user'
import Dashboard from "../../page/dashboard/dashboard.vue";
import store from "../../page/settings/store/store.vue"
import manufacturer from "../../page/settings/manufacturer/manufacturer.vue";
import category from "../../page/settings/category/category.vue";
import Expensestype from "../../page/settings/expensestype/expensestype.vue";
import paymentmethod from "../../page/settings/paymentmethod/paymentmethod.vue";
import supplier from "../../page/settings/supplier/supplier.vue";
import bank from "../../page/settings/bank/bank.vue";
import customer from "../../page/customermanager/customer/customer.vue";
import newpurchase from "../../page/bakery/purchase/new/newpurchase.vue";
import listpurchase from "../../page/bakery/purchase/list/listpurchase.vue";
import showpurchase from "../../page/bakery/purchase/show/showpurchase.vue";
import rawmaterial from "../../page/bakery/material/rawmaterial/rawmaterial.vue";
import available from "../../page/bakery/material/availablematerial/available.vue";
import listStock from "../../page/stock/list/listStock.vue";
import newStock from "../../page/stock/new/newStock.vue";
import AvailableStock from "../../page/stock/available/availableStock.vue";
import disabledStock from "../../page/stock/disabled/disabledStock.vue";
import newproduction from "../../page/bakery/production/new/newproduction.vue";


const routes = [

    { path: '/', name: 'Login', component: Auth },

    {
        path: '/dashboard',
        name : "AppHome",
        component : AppLayout,
        children : [
            {
                path : "",
                name : "Dashboard",
                title : "Dashboard",
                component : Dashboard
            },

        ],
        meta: { requiresAuth: true }
    },
    {
        path: '/settings',
        name : "Settings",
        component : AppLayout,
        children : [
            {
                path : "store",
                name : "Store",
                title : "Store Settings",
                component : store
            },
            {
                path : "manufacturer",
                name : "Manufacturer",
                title : "Manufacturer",
                component : manufacturer
            },
            {
                path : "productcategory",
                name : "productcategory",
                title : "Product Category",
                component : category
            },
            {
                path : "expensestype",
                name : "expensestype",
                title : "Expenses Type",
                component : Expensestype
            },
            {
                path : "payment_method",
                name : "payment_method",
                title : "Payment Method",
                component : paymentmethod
            },
            {
                path : "suppliers",
                name : "supplier",
                title : "Supplier",
                component : supplier
            },
            {
                path : "banks",
                name : "banks",
                title : "Bank",
                component : bank
            },
        ],
        meta: { requiresAuth: true }
    },
    {
        path: '/bakeryManager',
        name : "BakeryManager",
        component : AppLayout,
        children : [
            {
                path : "rawmaterials",
                name : "rawmaterialsManager",
                title : "Raw Material Manager",
                component : rawmaterial
            },
            {
                path : "rawmaterials/availability",
                name : "rawmaterialsAvailability",
                title : "Available Raw Material",
                component : available
            },
            {
                path : "purchase/list",
                name : "list-purchase-Material",
                title : "List Purchase Material",
                component : listpurchase
            },
            {
                path : "purchase/add",
                name : "new-purchase-Material",
                title : "New Purchase Material",
                component : newpurchase
            },
            {
                path : "purchase/:id/show",
                name : "show-purchase-Material",
                title : "Show Purchase Material",
                props: true,
                component : showpurchase
            },
            {
                path : "purchase/:id/edit",
                name : "edit-purchase-Material",
                title : "Edit Purchase Material",
                props: true,
                component : newpurchase

            },
            {
                path : "production/new",
                name : "newproduction",
                title : "New Production",
                component : newproduction
            },

        ],
        meta: { requiresAuth: true }
    },
    {
        path: '/customerManager',
        name : "CustomerManager",
        component : AppLayout,
        children : [
            {
                path : "customers",
                name : "customerManager",
                title : "Customer Manager",
                component : customer
            },
        ],
        meta: { requiresAuth: true }
    },
    {
        path: '/stock',
        name : "StockManager",
        component : AppLayout,
        children : [
            {
                path : "list",
                name : "list-stock",
                title : "List Stock",
                component : listStock
            },
            {
                path : "new",
                name : "new-stock",
                title : "New Stock",
                component : newStock
            },
            {
                path : "available",
                name : "available-stock",
                title : "Available Stock",
                component : AvailableStock
            },
            {
                path : "disabled",
                name : "disabled-stock",
                title : "Disabled Stock",
                component : disabledStock
            },
            {
                path : ":id/edit",
                name : "edit-stock",
                title : "Edit Stock",
                props: true,
                component : newStock

            },
        ],
        meta: { requiresAuth: true }
    }

];

const router = new createRouter({
    history: createWebHistory(),
    linkExactActiveClass: 'active',
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { left: 0, top: 0 };
        }
    },
});

router.beforeEach((to, from) => {

    const user = useUserStore();

    if (to.meta.requiresAuth && !user.islogin()) {
        return {
            path: '/',
        }
    }
})

export default router;
