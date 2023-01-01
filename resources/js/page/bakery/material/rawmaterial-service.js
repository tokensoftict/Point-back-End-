
export class RawmaterialService {

    constructor(http) {
        this.$http = http;
    }

    get() {
        return this.$http.get("bakeryManager/rawmaterial/");
    }

    getAvailable() {
        return this.$http.get("bakeryManager/available_rawmaterial/");
    }

    toggle(id) {
        return this.$http.get("bakeryManager/rawmaterial/"+id+"/toggle");
    }

    remove(id) {
        return this.$http.delete("bakeryManager/rawmaterial/"+id);
    }

    post(data) {
        return this.$http.post("bakeryManager/rawmaterial/",data);
    }

    update(data, id){
        data.set("_method","PUT");
        return this.$http.post("bakeryManager/rawmaterial/"+id,data);
    }

    search(data){
        return this.$http.post("bakeryManager/rawmaterial/find/",data);
    }

    availableSearch(data){
        return this.$http.post("bakeryManager/rawmaterial/findAvailable/",data);
    }

}
