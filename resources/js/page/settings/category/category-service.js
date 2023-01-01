
export class CategoryService {

    constructor(http) {
        this.$http = http;
    }

    get() {
        return this.$http.get("/settings/category");
    }

    toggle(id) {
        return this.$http.get("settings/category/"+id+"/toggle");
    }

    remove(id) {
        return this.$http.delete("settings/category/"+id);
    }

    post(data) {
        return this.$http.post("/settings/category",data);
    }

    update(data, id){
        data.set("_method","PUT");
        return this.$http.post("/settings/category/"+id,data);
    }


}
