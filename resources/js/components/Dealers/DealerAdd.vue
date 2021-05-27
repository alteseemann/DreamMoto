<template>
    <div class="dealer-add">

        <form>

            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="title">Наименование</label>
                        <div class="input-group">
                            <input type="text"
                                   autocomplete="off"
                                   class="form-control"
                                   id="title"
                                   placeholder=""
                                   required
                                   v-model="title"
                                   @onenter="needCheck = true"
                            >
                            <div class="input-group-append">
                                <button
                                        :disabled="!show_check_list"
                                        @click="show_check_list = false"
                                        class="btn btn-outline-secondary"
                                        type="button">Скрыть
                                </button>
                                <button
                                        :disabled="!title"
                                        v-bind:class="{  'btn-spinner': loading, 'btn-outline-secondary': !needCheck || !title, 'btn-outline-danger': needCheck && title }"
                                        class="btn"
                                        type="button"
                                        @click="check()">Проверить дилера
                                    <span class="spinner-grow spinner-grow-sm my-spinner" role="status"
                                          aria-hidden="true"
                                          v-if="loading"></span>
                                </button>
                            </div>
                        </div>
                        <div v-show="show_check_list"
                             style="position: relative;">
                            <div style="z-index:10; border: solid 1px rgba(0,0,0,0.3); border-top: none;    position: absolute;    width: 100%;    max-height: 250px;    overflow-y: auto;     background-color: #fff; box-shadow: 0 4px 7px rgba(0,0,0,.2);">
                                <ul style="margin: 0; padding: 0;">
                                    <li
                                            v-for="item in check_list"
                                            style="padding:10px;list-style-type: none; border-bottom: solid 1px rgba(0,0,0,.1);">
                                        <a target="_blank"
                                           :href="'/dealers/' + item.dealer_alias">
                                            <b>{{ item.city_title }}</b> - {{ item.dealer_title }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Тип техники дилера</label>
                        <multiselect
                                v-model="moto_id"
                                :options="motos"
                                label="title"
                                track-by="id"
                                :searchable="false"
                                :close-on-select="true"
                                :show-labels="false"
                                :multiple="true"
                                :taggable="true"
                                placeholder="Выберите тип"
                                @input="get_brands"
                        ></multiselect>
                    </div>

                    <div class="form-group">
                        <label>Бренды дилера</label>
                        <multiselect
                                v-model="brand_id"
                                :options="brands"
                                label="title"
                                track-by="id"
                                :searchable="false"
                                :close-on-select="true"
                                :show-labels="false"
                                :multiple="true"
                                :taggable="true"
                                placeholder="Выберите брэнд"
                        ></multiselect>
                    </div>

                    <div class="form-group">
                        <label for="alias">URL (латиницей, без пробелов) - </label>
                        <span class="lat-generate"
                              @click="generate()">Сгенерировать</span>
                        <input type="text"
                               class="form-control"
                               id="alias"
                               placeholder=""
                               required
                               v-model="alias"
                        >
                    </div>

                    <div class="form-group">
                        <label for="city">Город</label>
                        <select class="form-control"
                                id="city"
                                v-model="city_id"
                        >
                            <template v-for="alpha in cities">
                                <b>{{alpha.letter}}</b>
                                <option disabled value="-1">
                                    {{alpha.letter}}
                                </option>
                                <option
                                        v-for="city in alpha.cities"
                                        v-bind:value="city.id">
                                    {{ city.title }}
                                </option>
                            </template>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="address">Адрес</label>
                        <input type="text"
                               class="form-control"
                               id="address"
                               placeholder=""
                               required
                               v-model="address"
                        >
                    </div>

                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="text"
                               class="form-control"
                               id="phone"
                               placeholder=""
                               required
                               v-model="phone"
                        >
                    </div>

                    <div class="form-group">
                        <label for="site">Сайт</label>
                        <input type="text"
                               class="form-control"
                               id="site"
                               placeholder=""
                               required
                               v-model="site"
                        >
                    </div>

                </div>

                <div class="col-md-6">

                    <label for="latitude">Щелкните по карте для получения координат</label>

                    <div style="height: 400px;" class="mb-3">
                        <yandex-map
                                :coords="center"
                                :zoom="zoom"
                                :placemarks="placemarks"
                                style="width: 100%; height: 100%; max-height: 100%"
                                @map-was-initialized="initMap"
                                @click="click"
                        >
                        </yandex-map>
                    </div>

                    <div class="form-group">
                        <label for="latitude">Широта</label>
                        <input type="text"
                               class="form-control"
                               id="latitude"
                               placeholder=""
                               required
                               v-model="latitude"
                        >
                    </div>

                    <div class="form-group">
                        <label for="longitude">Долгота</label>
                        <input type="text"
                               class="form-control"
                               id="longitude"
                               placeholder=""
                               required
                               v-model="longitude"
                        >
                    </div>

                </div>
            </div>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary"
                    v-bind:class="{ 'btn-spinner': loading }"
                    v-bind:disabled="loading"
                    @click="save()">
                <span class="spinner-grow spinner-grow-sm my-spinner" role="status" aria-hidden="true"
                      v-if="loading"></span>
                Сохранить
            </button>

        </form>

    </div>
</template>


<script>
    export default {
        name      : 'dealer_add',
        props     : {
            motoss: {},
            cities: {},
        },
        data() {
            return {
                loading        : false,
                zoom           : '17',
                center         : [54.62896654088406, 39.731893822753904],
                brands         : [],
                moto_id        : [],
                brand_id       : [],
                city_id        : '',
                title          : '',
                alias          : '',
                address        : '',
                phone          : '',
                site           : '',
                latitude       : '',
                longitude      : '',
                map            : {},
                placemarks     : [],
                check_list     : {},
                show_check_list: false,
                needCheck      : false,
                motos          : [],
            }
        },
        mounted() {
            let self = this;
            let moto = {};
            for (moto in self.motoss) {
                self.motos.push({id: self.motoss[moto].id, title: self.motoss[moto].title});
            };
            // console.log(this.motos);
        },
        watch     : {
            title: function (val, oldVal) {
                this.needCheck = true;
            }
        },
        components: {},
        methods   : {
            check() {
                let self     = this;
                self.loading = true;
                // return;
                axios.post('/dealers/dealer_check', {
                    title: this.title,
                }).then(function (response) {
                    if (response.data.success) {
                        self.check_list = response.data.check_list;
                        self.needCheck  = false;
                        self.loading    = false;
// console.log(self.check_list);
                        if (self.check_list.length) {
                            self.show_check_list = true;
                        } else {
                            toastr.warning('Совпадений не найдено');
                        }
                    } else {
                        toastr.warning('Ошибка');
                        self.loading = false;
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });

            },
            click(e) {
                let self   = this;
                var coords = e.get('coords');

                self.placemarks = [];
                self.placemarks.push({
                    'coords' : [coords[0], coords[1]],
                    'options': {'preset': 'islands#redCircleDotIcon'},
                });

                self.map.balloon.open(coords, {
                    contentHeader: 'Новый дилер тут',
                    contentBody  : '<p>Координаты: ' + [
                        coords[0].toPrecision(6),
                        coords[1].toPrecision(6)
                    ].join(', ') + '</p>',
                });

                self.latitude  = coords[0];
                self.longitude = coords[1];
            },
            initMap(ymap) {
                let self = this;

                self.map = ymap;

            },
            save() {
                let self = this;
                console.log(this.moto_id, this.brand_id);
                self.loading = true;
                // return;
                axios.post('/dealers/dealer_save', {
                    moto_id  : this.moto_id,
                    brand_id : this.brand_id,
                    city_id  : this.city_id,
                    title    : this.title,
                    alias    : this.alias,
                    address  : this.address,
                    phone    : this.phone,
                    site     : this.site,
                    latitude : this.latitude,
                    longitude: this.longitude,
                }).then(function (response) {
                    if (response.data.success) {
                        setTimeout(function () {
                            window.location.href = response.data.redirect_path;
                        }, 500);
                    } else {
                        toastr.warning('Ошибка');
                        self.loading = false;
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },

            get_brands() {
                let self = this;
// return;
                axios.post('/dealers/get_brands', {
                    moto_id: this.moto_id,
                }).then(function (response) {
                    console.log(response.data);

                    if (response.data.brands) {
                        self.brands = response.data.brands;
                        console.log(self.brand_id);
                        self.brand_id = [];
                    } else {
                        toastr.warning('Ошибка');
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },

            generate() {
                let self   = this;
                self.alias = self.title.trim().replace(/([а-яё])|([\s_-])|([^a-z\d])/gi,
                    function (all, ch, space, words, i) {
                        if (space || words) {
                            return space ? '-' : '';
                        }
                        var code  = ch.charCodeAt(0),
                            index = code == 1025 || code == 1105 ? 0 :
                                code > 1071 ? code - 1071 : code - 1039,
                            t     = ['yo', 'a', 'b', 'v', 'g', 'd', 'e', 'zh',
                                'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
                                'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh',
                                'shch', '', 'y', '', 'e', 'yu', 'ya'
                            ];
                        return t[index];
                    }).toLowerCase();
            }
        }
    };
</script>
