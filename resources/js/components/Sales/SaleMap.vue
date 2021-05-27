<template>
    <div style="width: 100%">
        <div class="mb-3">Как добраться:</div>
            <yandex-map
                :controls="['typeSelector', 'zoomControl','fullscreenControl']"
                :coords="coords"
                @click="setMark"
                zoom="17"
                style="width: 600px; height: 400px; border: 1px solid black"
            >

                <ymap-marker
                    :coords="coords"
                    marker-id="1"
                    hint-content="some hint"
                />

            </yandex-map>
    </div>
</template>

<script>
    export default {
        name: "SaleMap",
        props:['coordinates'],
        data() {
            return {
                coords:[this.coordinates['lat'],this.coordinates['long']]
            }
        },
    }
</script>

<style scoped>

</style>
