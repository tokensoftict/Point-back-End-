import { defineStore } from 'pinia'
import environment from './environment';

const env = environment;

export const useUserStore = defineStore({

    id: 'user',
    state: () => ({
        token: false,
        base : env.dev.baseUrl,
        id : '',
        status : false,
    }),

    actions: {
        login(vue, data) {
            let self =this;
            return new Promise(function(resolve, reject){
                vue.$api.post("auth",data)
                    .then(function(response){
                        self.status = true;
                        for(var _key in response.data.data)
                        {
                            self[_key] = response.data.data[_key];
                        }
                        localStorage.setItem("token",response.data.data.token);
                        localStorage.setItem("user",JSON.stringify(response.data.data));
                        resolve(response.data);
                    })
                    .catch(function(error){
                        console.log(error);
                        reject(error.response.data);
                    })
            });
        },

        logout(vue){
            let self =this;
            return new Promise(function(resolve, reject){
               vue.$api.get("logout")
                   .then(function(response){

                       const user_data = JSON.parse(localStorage.getItem("user"));

                       for(var _key in user_data)
                       {
                           self[_key] = ""
                       }

                       self.status = false;

                       localStorage.removeItem("token");
                       localStorage.removeItem("user");
                       resolve(response.data)
                   }).catch(function (error){
                    reject(error.response.data);
                })
            });
        },

        refresh(){
            let data = localStorage.getItem("user");
            let token  = localStorage.getItem("token");

            data = JSON.parse(data);

            if(token)
            {
                this.status = true;

                for(let _key in data)
                {
                    this[_key] = data[_key];
                }
            }

            return data;
        },
        islogin(){

            let user = localStorage.getItem("user");
            let token  = localStorage.getItem("token");
            return !!(user && token);

        }
    }
});
