<template src="./sidebar.html"></template>

<script>

    export default {

        mounted() {
            const selector = document.querySelector('#sidebar a[href="' + window.location.pathname + '"]');
            if (selector) {
                const ul = selector.closest('ul.collapse');
                if (ul) {
                    let ele = ul.closest('li.menu').querySelectorAll('.dropdown-toggle');
                    if (ele) {
                        ele = ele[0];
                        setTimeout(() => {
                            ele.click();
                        });
                    }
                } else {
                    selector.click();
                }
            }
        }

    }

</script>

