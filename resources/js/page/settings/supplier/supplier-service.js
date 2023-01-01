
export class SupplierService {

    constructor(http) {
        this.$http = http;
    }

    get() {
        return this.$http.get("/settings/supplier");
    }

    toggle(id) {
        return this.$http.get("settings/supplier/"+id+"/toggle");
    }

    remove(id) {
        return this.$http.delete("settings/supplier/"+id);
    }

    post(data) {
        return this.$http.post("/settings/supplier",data);
    }

    update(data, id){
        data.set("_method","PUT");
        return this.$http.post("/settings/supplier/"+id,data);
    }


}
