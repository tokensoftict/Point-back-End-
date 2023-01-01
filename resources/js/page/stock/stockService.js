
export class StockService {

    constructor(http) {
        this.$http = http;
    }

    get() {
        return this.$http.get("stockmanager/stock");
    }

    available() {
        return this.$http.get("stockmanager/stock/available");
    }


    disabled() {
        return this.$http.get("stockmanager/stock/disable");
    }


    expired() {
        return this.$http.get("stockmanager/stock/expired");
    }


    toggle(id) {
        return this.$http.get("stockmanager/stock/"+id+"/toggle");
    }

    save(data,id)
    {
        if(id === undefined) return this.post(data);

        if(id) return this.update(data,id);
    }

    post(data) {
        return this.$http.post("stockmanager/stock",data);
    }

    update(data, id){
        data.set("_method","PUT");
        return this.$http.post("stockmanager/stock/"+id,data);
    }


    show(id)
    {
        return this.$http.get("stockmanager/stock/"+id+"/show");
    }


    getRequesiteData()
    {
        return  this.$http.get("stockmanager/stock/requesites");
    }

}
