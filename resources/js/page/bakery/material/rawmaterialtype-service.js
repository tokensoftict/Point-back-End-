
export class RawmaterialtypeService {

    constructor(http) {
        this.$http = http;
    }

    get() {
        return this.$http.get("bakeryManager/rawmaterial/materialtype");
    }


}
