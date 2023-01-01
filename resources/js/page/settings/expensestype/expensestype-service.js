
export class ExpensestypeService {

    constructor(http) {
        this.$http = http;
    }

    get() {
        return this.$http.get("settings/expenses_type");
    }

    toggle(id) {
        return this.$http.get("settings/expenses_type/"+id+"/toggle");
    }

    remove(id) {
        return this.$http.delete("settings/expenses_type/"+id);
    }

    post(data) {
        return this.$http.post("settings/expenses_type",data);
    }

    update(data, id){
        data.set("_method","PUT");
        return this.$http.post("settings/expenses_type/"+id,data);
    }


}
