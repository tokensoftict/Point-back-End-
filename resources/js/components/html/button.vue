<template>
    <button type="button"  :class="{
                'btn btn-primary' : design === 'primary',
                'btn btn-primary w-100' : design === 'primary-block',
                'btn btn-primary rounded-pill' : design === 'primary-round',
                'btn btn-primary rounded-pill w-100' : design === 'primary-round-block',


                'btn btn-outline-primary' : design === 'primary-outline',
                'btn btn-outline-primary w-100' : design === 'primary-outline-block',
                'btn btn-outline-primary rounded-pill' : design === 'primary-outline-round',
                'btn btn-primary btn-outline-primary rounded-pill w-100' : design === 'primary-outline-round-block',



                'btn btn-info' : design === 'info-outline',
                'btn btn-info w-100' : design === 'info-outline-block',
                'btn btn-info rounded-pill' : design === 'info-outline-round',
                'btn btn-info rounded-pill w-100' : design === 'info-outline-round-block',


                'btn btn-outline-info' : design === 'info-outline',
                'btn btn-outline-info w-100' : design === 'info-outline-block',
                'btn btn-outline-info rounded-pill' : design === 'info-outline-round',
                'btn btn-outline-info rounded-pill w-100' : design === 'info-outline-round-block',



                'btn btn-outline-success' : design === 'success-outline',
                'btn btn-outline-success w-100' : design === 'success-outline-block',
                'btn btn-outline-success rounded-pill' : design === 'success-outline-round',
                'btn btn-outline-success rounded-pill w-100' : design === 'success-outline-round-block',


                'btn btn-success' : design === 'success',
                'btn btn-success w-100' : design === 'success-block',
                'btn btn-success rounded-pill' : design === 'success-round',
                'btn btn-success rounded-pill w-100' : design === 'success-round-block',


                'btn btn-outline-warning' : design === 'warning-outline',
                'btn btn-outline-warning w-100' : design === 'warning-outline-block',
                'btn btn-outline-warning rounded-pill' : design === 'warning-outline-round',
                'btn btn-outline-warning rounded-pill w-100' : design === 'warning-outline-round-block',


                'btn btn-warning' : design === 'warning',
                'btn btn-warning w-100' : design === 'warning-block',
                'btn btn-warning rounded-pill' : design === 'warning-round',
                'btn btn-warning rounded-pill w-100' : design === 'warning-round-block',


                'btn btn-danger' : design === 'danger',
                'btn btn-danger w-100' : design === 'danger-block',
                'btn btn-danger rounded-pill' : design === 'danger-round',
                'btn btn-danger rounded-pill w-100' : design === 'danger-round-block',


                'btn btn-outline-danger' : design === 'danger-outline',
                'btn btn-outline-danger w-100' : design === 'danger-outline-block',
                'btn btn-outline-danger rounded-pill' : design === 'danger-outline-round',
                'btn btn-outline-danger rounded-pill w-100' : design === 'danger-outline-round-block',


                'btn btn-secondary' : design === 'secondary',
                'btn btn-secondary w-100' : design === 'secondary-block',
                'btn btn-secondary rounded-pill' : design === 'secondary-round',
                'btn btn-secondary rounded-pill w-100' : design === 'secondary-round-block',


                'btn btn-outline-secondary' : design === 'secondary-outline',
                'btn btn-outline-secondary w-100' : design === 'secondary-outline-block',
                'btn btn-outline-secondary rounded-pill' : design === 'secondary-outline-round',
                'btn btn-outline-secondary rounded-pill w-100' : design === 'secondary-outline-round-block',


                'btn btn-outline-dark' : design === 'dark-outline',
                'btn btn-outline-dark w-100' : design === 'secondary-outline-dark',
                'btn btn-outline-dark rounded-pill' : design === 'secondary-outline-dark',
                'btn btn-outline-dark rounded-pill w-100' : design === 'secondary-outline-round-dark',


                'btn btn-dark' : design === 'dark',
                'btn btn-dark w-100' : design === 'secondary-dark',
                'btn btn-dark rounded-pill' : design === 'secondary-dark',
                'btn btn-dark rounded-pill w-100' : design === 'secondary-round-dark',


            }" @click="click" @mouseover="mouseover" :disabled="disable">
        <span v-if="process" class="spinner-border text-white me-2 align-self-center loader-sm">Loading...</span>
        <slot/>
    </button>
</template>

<script>

export default {

    data() {
        return {
            disable : this.disabled,
            process : this.processing
        };
    },

    props : {

        design : {
            type : String,
            default : 'primary'
        },

        processing : {
            type : Boolean,
            default: false
        },

        type : {
            type : String,
            default : "button"
        },

        disabled : {
            type : Boolean,
            default: false
        },

        click : {
            type : Function,
            default () {
                return false;
            }
        },

        mouseover : {
            type : Function,
            default () {
                return false;
            }
        }
    },



    methods : {

        toggle() {
            this.disable = !this.disable
        },

        toggleProcessing() {
            this.process = !this.process;
            this.disable = !this.disable
        }

    }

}

</script>
