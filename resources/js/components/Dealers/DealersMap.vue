<template>
    <div class="w-100 h-100">
        <yandex-map
            :controls="['typeSelector', 'zoomControl']"
            :coords="center"
            :zoom="zoom"
            style="width: 100%; height: 100%; max-height: 100%"
            :cluster-callbacks="{'1': {mouseenter: openHandler, mouseleave: openHandler}}"
            :scroll-zoom="false"
            :cluster-options="{'1': {'preset': 'islands#blueClusterIcons'}, '2': {'preset': 'islands#redClusterIcons'}}"
            @map-was-initialized="initMap"
        >
            <template v-for="(dealer, index) in dealers">
                <ymap-marker v-if="dealer.latitude && dealer.longitude"
                    :marker-id="index"
                    marker-type="placemark"
                    :coords="[dealer.latitude, dealer.longitude]"
                    :hint-content="dealer.title"
                    :balloon="{header: '<a href=\'/dealers/'+dealer.alias+'\'>'+dealer.title+'</a>'}"
                    :options="marker_icon[index].options"
                    :cluster-name="'1'"
                    :callbacks="{mouseenter: openHandler, mouseleave: openHandler}"
                ></ymap-marker>
<!--                {{marker_icon[0]}}-->
            </template>
        </yandex-map>
<!--        <template v-for="(marker_iconn, index) in marker_icon">-->
            <div>
<!--                {{marker_icon[0].options}}-->
            </div>
<!--        </template>-->
<!--        <div-->
<!--                @mouseover="mouseenter()"-->
<!--                @mouseleave="mouseleave()"-->
<!--                @click="test()">-->
<!--            Тест</div>-->
    </div>
</template>

<script>

    export default {

        name: 'dealers_map',
        props: {
            dealers: {},
            placemarks: {}
        },
        data() {
            return {
                zoom: '17',
                center: [54.62896654088406, 39.731893822753904],
                map: {},
                result: {},
                marker_icon: this.placemarks,
                marker_icon_blue: {preset: 'islands#blueCircleDotIcon'},
                marker_icon_red: {preset: 'islands#redCircleDotIcon'},
            }
        },
        mounted() {
            let self = this;

        },
        watch: {},
        methods: {
            mouseenter() {
                let self = this;
                self.marker_icon[0].options = self.marker_icon_red;
            },
            mouseleave() {
                let self = this;
                self.marker_icon[0].options = self.marker_icon_blue;
            },
            openHandler(e) {
                var target = e.get('target'),
                    type = e.get('type');
                // console.log(target.options);
                if (typeof target.getGeoObjects != 'undefined') {
                    // Событие произошло на кластере.
                    if (type == 'mouseenter') {
                        target.options.set('preset', 'islands#redClusterIcons');
                    } else {
                        target.options.set('preset', 'islands#blueClusterIcons');
                    }
                } else {
                    // Событие произошло на геообъекте.
                    if (type == 'mouseenter') {
                        target.options.set('preset', 'islands#redCircleDotIcon');
                    } else {
                        target.options.set('preset', 'islands#blueCircleDotIcon');
                    }
                }
                // console.log(target.getGeoObjects)
            },
            test() {
                let self = this;

            },
            initMap(ymap) {
                let self = this;

                //изменение цвета марке при наведении на дилера (отслеживание событий)
                this.$bus.$on('mouseenter', (event) => {
                    self.marker_icon[event.id].options = self.marker_icon_red;
                });
                this.$bus.$on('mouseleave', (event) => {
                    self.marker_icon[event.id].options = self.marker_icon_blue;
                });


                if (this.dealers.length === 1) {
                    self.center = [self.dealers[0].latitude, self.dealers[0].longitude];
                }

                if (this.dealers.length > 1) {
                    ymap.setBounds(ymap.geoObjects.getBounds());
                }

                if (this.dealers.length === 0) {
                    self.zoom = '4';
                }

                this.map = ymap;

            },
        }
    };

</script>
