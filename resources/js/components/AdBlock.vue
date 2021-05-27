<template>
    <div
        v-bind:style="{ marginBottom: margin + 'px' }"
        v-bind:id="div_id">
    </div>
</template>

<script>
    export default {
        name: 'ad-block',
        props: {
            div_id: '',
            script: '',
            margin: {
                default: '0'
            },
            resize: {
                default: '0'
            },
        },
        data() {
            return {}
        },
        mounted() {
            let self = this;
            // console.log(self.script);
            // формируем тэг script и вставляем его в head
            var script = document.createElement('script');
            script.text = self.script;
            document.getElementsByTagName('head')[0].appendChild(script);
            //
        },
        components: {},
        watch: {},
        methods: {},
    }
</script>
