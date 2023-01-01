
export class ManufacturerService {

    constructor(http) {
        this.$http = http;
    }

    get() {
        return this.$http.get("/settings/manufacturer");
    }

    toggle(id) {
        return this.$http.get("settings/manufacturer/"+id+"/toggle");
    }

    remove(id) {
        return this.$http.delete("settings/manufacturer/"+id);
    }

    post(data) {
        return this.$http.post("/settings/manufacturer",data);
    }

    update(data, id){
        data.set("_method","PUT");
        return this.$http.post("/settings/manufacturer/"+id,data);
    }


}
