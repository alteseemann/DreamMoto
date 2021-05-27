<template>
    <div class="product-add">

        <form>

            <div class="form-group">
                <label for="brand">Марка</label>
                <select class="form-control"
                        id="brand"
                        v-model="brand_id"
                >
                    <option disabled value="">Выберите бренд</option>
                    <option
                        v-for="brand in current_moto.brands"
                        v-bind:value="brand.id">
                        {{brand.title}}
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="class">Класс</label>
                <select class="form-control"
                        id="class"
                        v-model="class_id"
                >
                    <option disabled value="">Выберите класс</option>
                    <option
                        v-for="class_moto in current_moto.classes"
                        v-bind:value="class_moto.id">
                        {{class_moto.title}}
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="title">Наименование</label>
                <input type="text"
                       class="form-control"
                       id="title"
                       placeholder=""
                       required
                       v-model="title"
                >
            </div>

            <div class="form-group">
                <label for="alias">Адрес (латиницей, без пробелов) - </label>
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
                <label for="price_catalog">Цена</label>
                <input type="text"
                       class="form-control"
                       id="price_catalog"
                       placeholder=""
                       v-model="price_catalog"
                >
            </div>

            <div class="form-group">
                <label for="alias">Описание модели</label>
                <div class="form-control" id="summernote">{{description}}</div>
            </div>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary"
                    @click="save()">
                Сохранить
            </button>
        </form>

    </div>
</template>


<script>
    export default {
        name: 'product_add',
        props: {
            current_moto: {},
        },
        data() {
            return {
                brand_id: '',
                class_id: '',
                title: '',
                alias: '',
                description: '',
                price_catalog: '',
            }
        },
        mounted() {
            let self = this;
            console.log(self.current_moto);

            $('#summernote').on('summernote.change', function (we, contents, $editable) {

                self.description = contents;

            });
        },
        watch: {},
        components: {},
        methods: {
            save() {
                let self = this;

                axios.post('/catalog/product_save', {
                    moto_id: this.current_moto.id,
                    brand_id: this.brand_id,
                    class_id: this.class_id,
                    title: this.title,
                    alias: this.alias,
                    price_catalog: this.price_catalog,
                    description: this.description,
                }).then(function (response) {
                    // console.log(response.data);
                    if (response.data.success) {
                        setTimeout(function () {
                            window.location.href = response.data.redirect_path;
                        }, 500);
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
                let self = this;
                self.alias = self.title.trim().replace(/([а-яё])|([\s_-])|([^a-z\d])/gi,
                    function (all, ch, space, words, i) {
                        if (space || words) {
                            return space ? '-' : '';
                        }
                        var code = ch.charCodeAt(0),
                            index = code == 1025 || code == 1105 ? 0 :
                                code > 1071 ? code - 1071 : code - 1039,
                            t = ['yo', 'a', 'b', 'v', 'g', 'd', 'e', 'zh',
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
