<template>
    <input ref="textbox"   :name="name" :validate="validate" :type="type" :placeholder="placeholder" :class="{
        'form-control' : design === 'default',
        'form-control form-control-sm' : design === 'sm',
        'form-control form-control-lg' : design === 'lg',
    }"  @input="handleInput"/>
    <small v-if="( valid === false && validate === true)"  class="block text-danger ">{{ errorMsg }}</small>
</template>

<script>




export default {

    data() {
        return  {
            valid : true,
        }

    },

    computed : {

    },

    props : {

        design : {
            type : String,
            default : "default"
        },

        type : {
            type : String,
            default : "text"
        },

        placeholder : {
            type : String,
            default : ""
        },

        model : {
            type : String,
            default : ""
        },

        errorMsg : {
            type : String,
            default : "This field is required!."
        },

        submitted : {
            type : Boolean,
            default : false
        },

        name : {
            type : String,
            default : ""
        },

        validate : {
            type : Boolean,
            default : false
        }


    },



    methods: {
        handleInput(event) {

            this.valid = event.target.value.length > 0;

            this.$parent.$data[this.model] = event.target.value;

            this.$refs.textbox.valid = true;
        },

        is_valid() {
            if(this.validate === false) return true;

            if(this.$refs.textbox.value.length === 0) return false;

            if(this.$refs.textbox.value.length > 0) return true;
        },

        setValue(value) {
            this.$refs.textbox.value = value;
        },
        getValue(){
            return this.$refs.textbox.value;
        }
    },

}



</script>
