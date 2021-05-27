/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
//https://github.com/CodeSeven/toastr
window.toastr = require('toastr');
toastr.options = {
    track_action     : false,
    closeButton      : false,
    debug            : false,
    newestOnTop      : false,
    progressBar      : false,
    positionClass    : "toast-top-left",
    preventDuplicates: false,
    onclick          : null,
    showDuration     : "300",
    hideDuration     : "1000",
    timeOut          : "5000",
    extendedTimeOut  : "1000",
    showEasing       : "swing",
    hideEasing       : "linear",
    showMethod       : "fadeIn",
    hideMethod       : "fadeOut"
};

//Перетаскивание и сортировка
window.vuedraggable = require('vuedraggable');

//Куки
window.vuecookie = require('vue-cookie');
Vue.use(vuecookie);

//summernote
window.summernote = require('summernote');
Vue.use(summernote);

//Yandex map
const settings    = {
    apiKey : 'd2ad51d9-9a7d-4ebd-bc71-ecbe0d795dc3',
    lang   : 'ru_RU',
    version: '2.1'
};
window.YmapPlugin = require('vue-yandex-maps');
Vue.use(YmapPlugin, settings);

//Прижатый футер
// $(document).ready(function () {
//     if ($(document).height() <= $(window).height()) {
//         $("footer").addClass("fixed-bottom").removeClass("opacity-0");
//     } else {
//         $("footer").removeClass("opacity-0");
//     }
// });

//Редактор summernote
$(document).ready(function () {
    $('#summernote').summernote({
        height : 300,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['codeview'],
        ],
        popover: {
            image: [],
            link : [],
            air  : []
        }
    });
});

//Sticky-kit
require('../../node_modules/sticky-kit/dist/sticky-kit.min.js');
$(document).ready(function () {
    $(".sticky_column").stick_in_parent();
});

//бренды и модели в хлебных крошках
$(document).ready(function () {
    $('.brands-hover').hover(function () {
        $('#brands').css('display', 'block')
    });
    $('.models-hover').hover(function () {
        $('#models').css('display', 'block')
    });
    $(".breadcrumb-product, .breadcrumb-brands").mouseleave(function () {
        $('#brands, #models').css('display', 'none')
    });
});

//Sticky-js
//липкое меню
// require('../../node_modules/sticky-js/dist/sticky.min.js');
window.StickyJs = require('sticky-js');
import Sticky from 'sticky-js';
// $(document).ready(function () {
let sticky = new Sticky('#breadcrumb', {});
// });

//Отслеживание изменений размеров элементов
// window.ResizeObserver = require('vue-resize');
// Vue.use(ResizeObserver);

// require('../../node_modules/css-element-queries/src/ElementQueries');
// $(document).ready(function () {
//     $(".sticky_column").stick_in_parent();
// });

//bLazy ленивая загрузка изображений
window.blazy = require('blazy');
import Blazy from 'blazy';

let bLazy = new Blazy({});

//Swiper slider
window.Swiper = require('swiper');

//https://github.com/shentao/vue-multiselect
window.VueMultiselect = require('vue-multiselect');
Vue.component('multiselect', window.VueMultiselect.default);

//Рекламный блок
Vue.component('ad-block', require('./components/AdBlock.vue').default);

// //Форма входа
// Vue.component('login-form', require('./components/Forms/Login/Login.vue').default);
//
// //Форма обратной связи
Vue.component('callback', require('./components/Forms/CallBack/CallBack.vue').default);
//
// //Редактирование модели
Vue.component('product-edit', require('./components/Catalog/ProductEdit.vue').default);
// //Добавление модели
Vue.component('product-add', require('./components/Catalog/ProductAdd.vue').default);
// //Добавление дилера
Vue.component('dealer-add', require('./components/Dealers/DealerAdd.vue').default);
// //Редактирование дилера
Vue.component('dealer-edit', require('./components/Dealers/DealerEdit.vue').default);
// //Добавление типов и брендов у дилера
Vue.component('dealer-add-moto-brand', require('./components/Dealers/DealerAddMotoBrand.vue').default);
// //Список дилеров для карты
Vue.component('dealers-list', require('./components/Dealers/DealersList.vue').default);
// //Редактирование цены модели
Vue.component('price-edit', require('./components/Catalog/PriceEdit.vue').default);
// //Редактирование класса модели
Vue.component('class-edit', require('./components/Catalog/ClassEdit.vue').default);
// //Загрузка картинок модели
Vue.component('image-upload', require('./components/ImageUpload.vue').default);
Vue.component('select-city', require('./components/SelectCity.vue').default);
Vue.component('compare', require('./components/Compare.vue').default);
Vue.component('compare-add', require('./components/CompareAdd.vue').default);
Vue.component('dealers-map', require('./components/Dealers/DealersMap.vue').default);
Vue.component('dealer-map', require('./components/Dealers/DealerMap.vue').default);
Vue.component('test-map', require('./components/Dealers/TestMap.vue').default);
Vue.component('moto-slider', require('./components/MotoSlider.vue').default);
Vue.component('same-moto-slider', require('./components/Catalog/SameMotoSlider.vue').default);
Vue.component('example', require('./components/Example.vue').default);

//Заполнение информации о себе в личном кабинете
Vue.component('personal-settings', require('./components/Home/PersonalSettings.vue').default)
//Редактирование информации о себе в личном кабинете
Vue.component('edit-settings', require('./components/Home/EditSettings.vue').default)
// //Добавление модели
Vue.component('sale-add', require('./components/Sales/SaleAdd.vue').default);

//Modal Window

Vue.component('popup', require('./components/Modal/Popup.vue').default);
 //Показать карту на странице просмотра объявлений
Vue.component('sale-map', require('./components/Sales/SaleMap.vue').default);
//Сравнить модели
Vue.component('sale-compare', require('./components/Sales/SaleCompare.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Object.defineProperty(Vue.prototype, "$bus", {
    get: function () {
        return this.$root.bus;
    }
});

const app = new Vue({
    el  : '#app',
    data: {
        bus: new Vue({}) // Here we bind our event bus to our $root Vue model.
    }
});
