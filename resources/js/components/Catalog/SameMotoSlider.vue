<template>
    <div class="moto-swiper mb-3">
        <!-- Swiper -->
        <div class="swiper-container same-gallery">
            <div class="swiper-wrapper">
                <div v-for="slide in same_products" class="swiper-slide border rounded"
                     v-bind:style="{ backgroundImage: 'url(\'/storage/' + slide.images[0].path + '\')' }"
                >
                    <a class="d-flex"
                       :href="'/' + slide.moto.alias + '/catalog/' + slide.brand.alias + '/' + slide.alias + '?utm_source=' + slide.moto.alias">
                        <div class="shadow m-2 px-2 py-3 text-center bg-white"
                             style="border-radius: 7px; width: 100%; align-self: flex-end;">
                            <h5 class="font-weight-bold m-0">
                                {{ slide.brand.title }} {{ slide.title }}
                            </h5>
                        </div>
                    </a>
                </div>
                <div class="swiper-slide d-flex justify-content-center align-items-center position-relative">
                    <ad-block
                            div_id='yandex_rtb_R-A-347397-12-100'
                            script='(function(w, d, n, s, t) {w[n] = w[n] || [];
                w[n].push(function() {
                    Ya.Context.AdvManager.render({
                    blockId: "R-A-347397-12",
                    renderTo: "yandex_rtb_R-A-347397-12-100",
                    pageNumber: 100,
                    async: true,
                    onRender: function (data) {
                    $(document.body).trigger("sticky_kit:recalc");
                    console.log(data.product);
                    }
                    });
                    });
                    t = d.getElementsByTagName("script")[0];
                    s = d.createElement("script");
                    s.type = "text/javascript";
                    s.src = "//an.yandex.ru/system/context.js";
                    s.async = true;
                    t.parentNode.insertBefore(s, t);
                    })(this, this.document, "yandexContextAsyncCallbacks");'
                    >
                    </ad-block>
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <!-- Add Arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</template>


<script>
    import Swiper from 'swiper';

    export default {
        name      : 'same_moto_slider',
        props     : {
            same_products: {},
        },
        data() {
            return {
                galleryTop: false,
                slides    : this.images
            }
        },
        created   : function () {
        },
        mounted() {
            let self = this;
            $(document).ready(function () {
                this.galleryTop = new Swiper('.same-gallery', {
                    spaceBetween : 10,
                    // slidesPerView: 4,
                    breakpoints  : {
                        320: {
                            slidesPerView: 1
                        },
                        576: {
                            slidesPerView: 2
                        },
                        768: {
                            slidesPerView: 3
                        },
                        992: {
                            slidesPerView: 4
                        }
                    },
                    pagination   : {
                        el  : '.swiper-pagination',
                        type: 'progressbar',
                    },
                    preloadImages: false,
                    lazy         : true,

                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
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