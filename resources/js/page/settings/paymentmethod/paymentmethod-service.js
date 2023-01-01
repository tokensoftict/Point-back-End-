
export class Paymentmethod {

    constructor(http) {
        this.$http = http;
    }

    get() {
        return this.$http.get("/settings/payment_method");
    }

    toggle(id) {
        return this.$http.get("settings/payment_method/"+id+"/toggle");
    }

    remove(id) {
        return this.$http.delete("settings/payment_method/"+id);
    }

    post(data) {
        return this.$http.post("/settings/payment_method",data);
    }

    update(data, id){
        data.set("_method","PUT");
        return this.$http.post("/settings/payment_method/"+id,data);
    }


}
