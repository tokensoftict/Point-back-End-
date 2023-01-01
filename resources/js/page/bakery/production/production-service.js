
export class ProductionService {

    constructor(http) {
        this.$http = http;
    }


    get() {
        return this.$http.get("bakeryManager/production");
    }

    remove(id) {
        return this.$http.delete("bakeryManager/production/"+id);
    }

    insert(data) {
        return this.$http.post("bakeryManager/production",data);
    }


    post(data,id){
        if(id === undefined) return this.insert(data);

        if(id !== undefined) return this.update(data,id);

    }

    update(data, id){
        if(data instanceof FormData) {
            data.set("_method", "PUT");
        }
        else {
            data['_method'] = "PUT";
        }

        return this.$http.post("bakeryManager/production/"+id+"/update",data);
    }

}
