<template>
    <div>
        <h2>Загрузка изображений</h2>

        <form method="post" action="/image/add" enctype="multipart/form-data">

            <div class="form-group">
                <input type='file' multiple="multiple" name="file[]" @change="previewImages"
                       class="form-control"
                       accept="image/jpeg,image/png,image/gif">
            </div>

            <draggable
                    :list="imagesData"
                    :disabled="!enabled"
                    class="row no-gutters image-block"
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

            <div class="progress mb-2" style="height: 1px;">
                <div class="progress-bar" role="progressbar" v-bind:style="{ width: ProgressBarValue + '%'  }"
                     v-bind:aria-valuenow=ProgressBarValue aria-valuemin="0"
                     aria-valuemax="100">
                </div>
            </div>


            <button type="button" class="btn btn-primary" @click="save()" :disabled="disabled">

                <template v-if="disabled">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Сохранение...
                </template>
                <template v-else>
                    Сохранить
                </template>

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
        name      : 'image-upload',
        props     : {
            current_product: {
                type     : String,
                'default': function () {
                    return '';
                }
            },
            proguct_images : {},
        },
        data() {
            return {
                enabled         : true,
                dragging        : false,
                imagesData      : this.proguct_images,
                product         : this.current_product,
                deleted         : [],
                ProgressBarValue: 0,
                disabled        : false,
            }
        },
        mounted() {
            let self = this;
        },
        watch     : {},
        computed  : {},
        methods   : {
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
                let pictures = event.target.files;
                for (let i = 0; i < pictures.length; i++) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagesData.push({
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

                formData.append('current_product', this.product);
                formData.append('imagesData', this.imagesData);


                for (let i = 0; i < this.deleted.length; i++) {
                    formData.append('deleted[' + i + '][id]', this.deleted[i].id);
                    formData.append('deleted[' + i + '][path]', this.deleted[i].path);
                }

                axios.post('/catalog/images_save',
                    formData,
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
                    }
                )
                    .then(function (response) {
                        if (response.data.success) {
                            self.deleted = [];
                            self.ProgressBarValue = 100;
                            toastr.success('Изображения обновлены');
                            window.location.reload();
                        }
                        if (response.data.error) {
                            toastr.warning('Ошибка');
                            self.disabled = false;
                        }
                    })
                    .catch(function (error) {
                        if (error.response.status == 422) {
                            self.server_error = true;
                            self.disabled = false;
                        }
                    });
            }
        }
    };

</script>

<style>
    .image-block {
        margin-left: -.25rem;
        margin-right: -.25rem;
    }

    .image-outer {
        overflow: hidden;
        display: flex;
        position: relative;
    }

    .image-outer:before {
        display: block;
        content: "";
        width: 100%;
        padding-top: 66.6%;
    }

    .image-inner {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        cursor: move;

    }

    .image-inner img {
        border: none;
    }

    .del-icon {
        position: absolute;
        right: 10px;
        top: 10px;
        color: #fff;
        cursor: pointer;
        background-color: #5e5e5e;
        padding: 0 4px;
        font-size: .7rem;
        border-radius: 2px;
    }

    .ghost {
        opacity: 0.5;
        background: #c8ebfb;
        z-index: 100;
    }
</style>
