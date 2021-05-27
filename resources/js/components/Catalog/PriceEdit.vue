<template>
    <div class="product-add">

        <form>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Цена</span>
                </div>
                <input type="text"
                       class="form-control"
                       placeholder=""
                       aria-label="Цена каталожная"
                       aria-describedby="basic-addon2"
                       style="z-index: 2;"
                       v-model="price">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary"
                            type="button"
                            @click="save()">
                        <i class="fal fa-save"></i>
                    </button>
                </div>
            </div>
        </form>

    </div>
</template>


<script>
    export default {
        name: 'price_edit',
        props: [
            'product_id',
            'price_catalog',
        ],
        data() {
            return {
                price: this.price_catalog,
            }
        },
        mounted() {
        },
        watch: {},
        components: {},
        methods: {
            save() {
                let self = this;

                axios.post('/catalog/price_save', {
                    price_catalog: this.price,
                    id: this.product_id
                }).then(function (response) {
                    console.log(response.data);
                    if (response.data.success) {
                        toastr.success(response.data.price + ' руб, ОК');
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
