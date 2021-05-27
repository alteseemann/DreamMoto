<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {{msg}}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name      : 'example',
        props     : {},
        data() {
            return {
                msg: '',
            }
        },
        mounted() {
            let self = this;
            this.$bus.$on('specialEvent', (event) => {
                this.msg = event.msg;
            });
        },
        components: {},
        watch     : {},
        methods   : {},
    }
</script>