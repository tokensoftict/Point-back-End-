
export class BankService {

    constructor(http) {
        this.$http = http;
    }

    get() {
        return this.$http.get("/settings/bank");
    }

    toggle(id) {
        return this.$http.get("settings/bank/"+id+"/toggle");
    }

    remove(id) {
        return this.$http.delete("settings/bank/"+id);
    }

    post(data) {
        return this.$http.post("/settings/bank",data);
    }

    update(data, id){
        data.set("_method","PUT");
        return this.$http.post("/settings/bank/"+id,data);
    }


}
