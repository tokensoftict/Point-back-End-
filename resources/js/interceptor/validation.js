
const helpers = {

    'validate' : (formComponent) => {

        let status = false;

        for(let component in formComponent)
        {
            if(typeof formComponent[component].is_valid !== "undefined" && formComponent[component].validate === true) {

                if (formComponent[component].is_valid() === false) {

                    formComponent[component].valid = false;

                    status = true;
                }

            }
        }

        return status;
    },

    'validateSingle' : (formComponent, keys) => {
        let status = false;

        for(let component of keys)
        {
            if(typeof formComponent[component].is_valid !== "undefined" && formComponent[component].validate === true) {

                if (formComponent[component].is_valid() === false) {

                    formComponent[component].valid = false;

                    status = true;
                }

            }
        }

        return status;
    },

    error(notify,title,message){
        notify({
            title:title,
            text:message,
            type: 'error',
            duration: 2000,
        })
        this._alert(message,'error')
    },
    success(notify,title,message){
        notify({
            title:title,
            text:message,
            type: 'success',
            duration: 2000,
        });

        this._alert(message,'success')
    },
    warning(notify,title,message){
        notify({
            title:title,
            text:message,
            type: 'warning',
            duration: 2000,
        })
        this._alert(message,'warning')
    },
    _alert(msg = '', type = 'success'){
        const toast = window.Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
        });
        toast.fire({
            icon: type,
            title: msg,
            padding: '10px 20px',
        });
    }


}


export default helpers;
