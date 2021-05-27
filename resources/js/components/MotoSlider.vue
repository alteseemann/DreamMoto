<template>
    <div class="moto-swiper mb-3">

        <!-- Swiper -->
        <div class="swiper-container gallery-top"
             :class="{'gallery-top-mobile' : is_mobile=='1'}">
            <div class="swiper-wrapper">
                <div v-for="slide in slides" class="swiper-slide"
                     v-bind:style="{ backgroundImage: 'url(\'/storage/' + slide.path + '\')' }"
                ></div>
                <div class="swiper-slide swiper-slide-ad d-flex justify-content-center align-items-center">
                    <!--Рекламный блок Google!!!-->
                    <!--                    <ins class="adsbygoogle w-100 h-100"-->
                    <!--                         style="display:block"-->
                    <!--                         data-ad-client="ca-pub-1731882308528549"-->
                    <!--                         data-ad-slot="5444661237"-->
                    <!--                         data-ad-format="auto"-->
                    <!--                         data-full-width-responsive="true"></ins>-->
                    <!--Рекламный блок Яндекса!!!-->
                    <ad-block
                            div_id='yandex_rtb_R-A-347397-17'
                            :script=script
                    >
                    </ad-block>
                </div>
            </div>
            <!-- Add Arrows -->
            <template v-if="is_robot=='1'">
                <div class="swiper-button-next"
                     :class="{'d-none' : isAdActive}">
                </div>
                <div class="swiper-button-prev"
                     :class="{'d-none' : isAdActive}">
                </div>
            </template>
            <template v-else>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"
                     :class="{'swiper-button-disabled' : isAdActive}">
                </div>
            </template>

        </div>
        <div class="swiper-container-thumbs gallery-thumbs">
            <div class="swiper-wrapper">
                <div v-for="(slide, index) in slides" class="swiper-slide"
                     v-on:mouseenter="mouseenter(index)"
                     v-bind:style="{ backgroundImage: 'url(\'/storage/' + slide.path + '\')' }"
                ></div>
                <div class="swiper-slide bg-primary d-flex justify-content-center align-items-center px-1"
                     v-on:mouseenter="mouseenter(slides.length)"
                ><img src="/images/logo.svg" alt="DreamMoto" style="height: 17px; max-width: 100%;"></div>
            </div>
        </div>

    </div>
</template>

<style>
    .adsbygoogle {
        margin-left : 0 !important;
    }
</style>

<script>
    import Swiper from 'swiper';

    export default {
        name      : 'moto_slider',
        props     : {
            images      : {},
            is_mobile   : '',
            is_robot    : '',
            current_hour: ''
        },
        data() {
            return {
                galleryThumbs: false,
                galleryTop   : false,
                slides       : this.images,
                isAdActive   : false,
                script       : '',
            }
        },
        created   : function () {
            let self = this;

            // let rand_block_id = 17;
            console.log(self.current_hour);
            // Выбор случайного блока с 0 до 10 часов утра
            // if (self.current_hour >= 4 && self.current_hour < 14) {
            //     console.log('!');
            let arr = [];

            if (self.is_mobile == 1) {
                arr = [12, 17];
            } else {
                arr = [14, 17];
            }
            // }
            let rand_block_id = arr[Math.floor(Math.random() * arr.length)];

            self.script = '(function(w, d, n, s, t) {w[n] = w[n] || [];' +
                'w[n].push(function() {' +
                'Ya.Context.AdvManager.render({' +
                'blockId: "R-A-347397-' + rand_block_id + '",' +
                'renderTo: "yandex_rtb_R-A-347397-17",' +
                'pageNumber: 79,' +
                'async: true,' +
                'onRender: function (data) { ' +
                '$(document.body).trigger("sticky_kit:recalc");' +
                'console.log(data.product);' +
                '}' +
                '});' +
                '});' +
                't = d.getElementsByTagName("script")[0];' +
                's = d.createElement("script");' +
                's.type = "text/javascript";' +
                's.src = "//an.yandex.ru/system/context.js";' +
                's.async = true;' +
                't.parentNode.insertBefore(s, t);' +
                '})(this, this.document, "yandexContextAsyncCallbacks");';

            // console.log(self.script);
        },
        mounted() {
            let self = this;

            $(document).ready(function () {

                this.galleryThumbs = new Swiper('.gallery-thumbs', {
                    spaceBetween         : 2,
                    slidesPerView        : 6,
                    freeMode             : true,
                    watchSlidesVisibility: true,
                    watchSlidesProgress  : true,
                });
                this.galleryTop    = new Swiper('.gallery-top', {
                    spaceBetween : 10,
                    preloadImages: false,
                    lazy         : true,
                    on           : {
                        transitionEnd: function () {
                            if ($('.swiper-slide-ad').hasClass('swiper-slide-active')) {
                                self.isAdActive = true;
                            } else {
                                self.isAdActive = false;
                            }
                        },
//                         init         : function () {
// // Отображение рекламного блока Google
//                             (adsbygoogle = window.adsbygoogle || []).push({});
//                         },
                    },

                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    thumbs    : {
                        swiper: this.galleryThumbs
                    }
                });

            });

            // console.log(this.slides);

        },
        watch     : {},
        components: {},
        methods   : {
            mouseenter(index) {
                $(document).ready(function () {
                    let self = this;
                    // console.log(self.galleryTop, index);
                    self.galleryTop.slideTo(index, 2, false);
                });
            }
        }
    };
</script>
