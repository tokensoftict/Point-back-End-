import axios from 'axios';
import { useUserStore } from '../store/user'
import App from "../App.vue";
import { notify } from "@kyvg/vue3-notification";


const instance = axios.create()

instance.interceptors.request.use(function (request) {

    let user =  useUserStore();

    if(user.token === false) {
         user.refresh();
    }

    if(user.token !== false) {

        request.headers.Authorization =  'Bearer ' +user.token;
    }

    request.baseURL = user.base;

    request.transformRequest = [function (data, headers) {

        let form_data = new FormData();

        if(!(data instanceof FormData))
        {
            for (let key in data ) {
                if(typeof  data[key] == "object"){

                    form_data.append(key, JSON.stringify(data[key]));

                }else{
                    form_data.append(key, data[key]);
                }

            }
        }
        else
        {
            form_data = data
        }

        return form_data;
    }];
    return request;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});

instance.interceptors.response.use((response) => response, (error) => {
    // whatever you want to do with the error

   const  error_codes = ['500']

    if(error.response.data.message !== undefined && error.response.data.message === "Unauthenticated." && error.response.status !== 422 ){

        notify({
            title: "Authorization",
            text: "Your session have expired, please login to continue",
            type: 'error',
            duration: 50000,
        });
        setTimeout(()=>{
            location.href = window.base_url;
        },2000);

        return Promise.reject(error);

    }else if(error_codes.includes(error.response.status)){

        notify({
            title: "Server Error",
            text: "Internal Server Error occured, Please try again",
            type: 'error',
            duration: 30000,
        });

        return Promise.resolve(error)
    }

    return Promise.reject(error);
});


export  default instance;


