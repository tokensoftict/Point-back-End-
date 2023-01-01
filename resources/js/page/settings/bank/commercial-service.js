
export class CommercialBankService {

    constructor(http) {
        this.$http = http;
    }

    get() {
        return this.$http.get("/settings/bank/commercial");
    }


}
