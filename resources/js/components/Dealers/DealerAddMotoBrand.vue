<template>
    <div class="dealer-add-moto-brand">


        <div class="form-group">
            <multiselect
                v-model="moto_id"
                :options="new_motos"
                label="title"
                track-by="id"
                :searchable="false"
                :close-on-select="false"
                :show-labels="false"
                :multiple="true"
                :taggable="true"
                placeholder="Выберите тип"
                @input="get_brands"
            ></multiselect>
        </div>

        <div class="form-group">
            <multiselect
                v-model="brand_id"
                :options="new_brands"
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

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary"
                v-bind:class="{ 'btn-spinner': loading }"
                v-bind:disabled="loading"
                @click="save()">
                <span class="spinner-grow spinner-grow-sm my-spinner" role="status" aria-hidden="true"
                      v-if="loading"></span>
            Сохранить
        </button>


    </div>
</template>


<script>
    export default {
        name: 'dealer_add_moto_brand',
        props: {
            new_motos: {},
            dealer_id: '',
        },
        data() {
            return {
                loading: false,
                new_brands: [],
                moto_id: [],
                brand_id: [],
            }
        },
        mounted() {
            let self = this;
            // console.log(this.dealer_id);

        },
        watch: {},
        components: {},
        methods: {
            save() {
                let self = this;
                console.log(this.moto_id, this.brand_id);
                self.loading = true;
                // return;
                axios.post('/dealers/dealer_save', {
                    moto_id: this.moto_id,
                    brand_id: this.brand_id,
                    dealer_id: this.dealer_id,
                    edit: 1,
                }).then(function (response) {
                    if (response.data.success) {
                        setTimeout(function () {
                            window.location.href = window.location.href;
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
                console.log(this.moto_id);
                axios.post('/dealers/get_brands', {
                    moto_id: this.moto_id,
                }).then(function (response) {
                    // console.log(response.data);

                    if (response.data.brands) {
                        self.new_brands = response.data.brands;
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
        }
    };
</script>
