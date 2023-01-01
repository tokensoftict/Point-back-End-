
export class StoreService {

    constructor(http) {
        this.$http = http;
    }

    get() {
        return this.$http.get("/settings/store");
    }

    post(data) {
        data.set("_method","PUT");
        return this.$http.post("/settings/store/update",data);
    }
}
