<template>
    <yandex-map
            :controls="['typeSelector', 'zoomControl']"
            :coords="center"
            :zoom="zoom"
            style="width: 100%; height: 100%; max-height: 100%"
            :scroll-zoom="false"
    >

        <ymap-marker v-if="dealer.latitude && dealer.longitude"
                :marker-id="1"
                marker-type="placemark"
                :options="{preset : 'islands#redCircleDotIcon'}"
                :coords="[dealer.latitude, dealer.longitude]"
                :hint-content="dealer.title"
        ></ymap-marker>
    </yandex-map>
</template>

<script>

    export default {

        name   : 'dealer_map',
        props  : {
            dealer: {},
        },
        data() {
            return {
                zoom  : '17',
                center: [this.dealer.latitude, this.dealer.longitude],
            }
        },
        mounted() {
            let self = this;

        },
        watch  : {},
        methods: {
            initMap(ymap) {

            },
        }
    };

</script>
