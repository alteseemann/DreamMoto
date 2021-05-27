<template>
    <div class="sale-add-wrapper">

        <form>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type">Тип техники</label>
                        <select class="form-control"
                                id="type"
                                v-model="moto_id"
                                @change="getModels"
                        >
                            <option disabled value="">Выберите тип техники</option>
                            <option
                                    v-for="moto in dealer.motos"
                                    v-bind:value="moto.id">
                                {{moto.title}}
                            </option>
                        </select>
                    </div>

                    <div class="form-group"
                         v-show="moto_id">
                        <label for="brand">Марка</label>
                        <select class="form-control"
                                id="brand"
                                v-model="brand_id"
                                @change="getModels"
                        >
                            <option disabled value="">Выберите бренд</option>
                            <option
                                    v-for="brand in moto_brands"
                                    v-bind:value="brand.brand_id"
                                    v-if="brand.moto_id===moto_id"
                            >
                                {{brand.title}}
                            </option>
                        </select>
                    </div>

                    <div class="form-group"
                         v-show="brand_id && moto_id">
                        <label for="brand">Модель</label>
                        <select class="form-control"
                                id="model"
                                v-model="product_id"
                        >
                            <option disabled value="">Выберите модель</option>
                            <option
                                    v-for="model in models"
                                    v-bind:value="model.id">
                                {{model.title}}
                            </option>
                        </select>
                    </div>

                    <div class="form-group"
                         v-show="moto_id && brand_id && product_id">
                        <label for="year">Год</label>
                        <input type="text"
                               class="form-control"
                               id="year"
                               placeholder=""
                               required
                               v-model="year"
                        >
                    </div>

                    <div class="form-group"
                         v-show="moto_id && brand_id && product_id">
                        <label for="mileage">Пробег, км</label>
                        <input type="text"
                               class="form-control"
                               id="mileage"
                               placeholder=""
                               v-model="mileage"
                        >
                    </div>

                    <div class="form-group"
                         v-show="moto_id && brand_id && product_id">
                        <label for="price_catalog">Цена, руб</label>
                        <input type="text"
                               class="form-control"
                               id="price_catalog"
                               placeholder=""
                               required
                               v-model="price"
                        >
                    </div>

                    <div class="form-group"
                         v-show="moto_id && brand_id && product_id">
                        <label>Фотографии</label><br>
                        <div class="btn btn-secondary position-relative overflow-hidden">
                            Добавить
                            <input style="position: absolute;opacity: 0; right: 0; top: 0;" type='file'
                                   multiple="multiple" name="file[]" @change="previewImages"
                                   class="form-control-file"
                                   accept="image/jpeg">
                        </div>
                    </div>

                    <draggable
                            :list="imagesData"
                            :disabled="!enabled"
                            class="row no-gutters image-block mb-3"
                            ghost-class="ghost"
                            @start="dragging = true"
                            @end="dragging = false"
                    >
                        <div class="col-md-4 image-outer" v-for="(image, index) in imagesData">
                            <div class="image-inner">

                                <img class="img-thumbnail" :src="'/storage/'+image.path"
                                     v-if="image.path.length > 0 && image.id"
                                     style="object-fit: cover">
                                <img class="img-thumbnail" :src="image.path" v-if="image.path.length > 0 && !image.id"
                                     style="object-fit: cover">

                                <span class="del-icon" @click="delete_image(index, image.id, image.path)"><i
                                        class="fal fa-times"></i></span>
                            </div>
                        </div>
                    </draggable>

                    <div class="form-group"
                         v-show="moto_id && brand_id && product_id">
                        <label>Комментарий дилера</label>
                        <div class="form-control" id="summernote">{{description}}</div>
                    </div>
                </div>
            </div>

            <div class="progress mb-3" style="height: 1px;">
                <div class="progress-bar" role="progressbar" v-bind:style="{ width: ProgressBarValue + '%'  }"
                     v-bind:aria-valuenow=ProgressBarValue aria-valuemin="0"
                     aria-valuemax="100">
                </div>
            </div>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary"
                    :disabled="!moto_id || !brand_id || !product_id"
                    @click="save()">
                Разместить
            </button>
        </form>

    </div>
</template>


<script>
    import draggable from 'vuedraggable'

    export default {
        order     : 0,
        components: {
            draggable,
        },
        name      : 'sale_add',
        props     : {
            dealer     : {},
            sale_images: {},
            moto_brands: {}
        },
        data() {
            return {
                enabled         : true,
                dragging        : false,
                imagesData      : this.sale_images,
                // imagesData      : [],
                deleted         : [],
                ProgressBarValue: 0,
                disabled        : false,

                models     : {},
                brand_id   : '',
                moto_id    : '',
                product_id : '',
                description: '',
                price      : '',
                year       : null,
                mileage    : ''
            }
        },
        mounted() {
            console.log(this.moto_brands)
            let self = this;
            $('#summernote').on('summernote.change', function (we, contents, $editable) {

                self.description = contents;

            });
        },
        watch     : {},
        components: {},
        methods   : {
            getModels() {
                let self = this;
                if (this.moto_id && this.brand_id) {
                    axios.post('/get-models', {
                            moto_id : this.moto_id,
                            brand_id: this.brand_id,
                        }
                    )
                        .then(function (response) {
                            self.models = response.data.models;
                        }).catch(error => {
                    });
                }
            },
            delete_image(image_index, image_id, image_path) {
                if (image_id) {
                    this.deleted.push({
                        id  : image_id,
                        path: image_path
                    });
                    console.log(this.deleted);
                }
                this.imagesData.splice(image_index, 1);
                this.ProgressBarValue = 0;
            },
            previewImages: function (event) {
                let self     = this;
                let pictures = event.target.files;
                for (let i = 0; i < pictures.length; i++) {
                    let reader    = new FileReader();
                    reader.onload = (e) => {
                        self.imagesData.push({
                            path      : e.target.result,
                            image_name: pictures[i]
                        });
                    };
                    reader.readAsDataURL(pictures[i]);
                }
                this.ProgressBarValue = 0;
            },
            save() {
                let self = this;

                self.disabled = true;

                let formData = new FormData();

                for (let i = 0; i < this.imagesData.length; i++) {
                    console.log(this.imagesData[i].image_name);
                    formData.append('images[' + i + '][image]', this.imagesData[i].image_name);
                    if (this.imagesData[i].id) {
                        formData.append('images[' + i + '][id]', this.imagesData[i].id);
                    } else {
                        formData.append('images[' + i + '][id]', '0');
                    }
                }

                formData.append('imagesData', this.imagesData);
                formData.append('moto_id', this.moto_id);
                formData.append('brand_id', this.brand_id);
                formData.append('product_id', this.product_id);
                formData.append('price', this.price);
                formData.append('description', this.description);
                formData.append('year', this.year);
                formData.append('mileage', this.mileage);

                for (let i = 0; i < this.deleted.length; i++) {
                    formData.append('deleted[' + i + '][id]', this.deleted[i].id);
                    formData.append('deleted[' + i + '][path]', this.deleted[i].path);
                }

                axios.post('/sale-save', formData,
                    {
                        headers         : {
                            'Content-Type':
                                'multipart/form-data'
                        },
                        onUploadProgress: progressEvent => {
                            var progressVal = Math.round(progressEvent.loaded / progressEvent.total * 100);
                            if (progressVal < 90) {
                                this.ProgressBarValue = progressVal;
                            }
                        }
                    }).then(function (response) {
                    // console.log(response.data);
                    if (response.data.success) {
                        toastr.success('Объявление добавлено');
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
            }
        }
    };
</script>
