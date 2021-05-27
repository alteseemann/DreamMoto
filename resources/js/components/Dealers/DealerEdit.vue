<template>
    <div class="dealer-edit">


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
        name      : 'dealer_edit',
        props     : {
            dealer: {},
        },
        data() {
            return {
                loading  : false,
                latitude : this.dealer.latitude,
                longitude: this.dealer.longitude,
            }
        },
        mounted() {
            let self = this;
            // console.log(this.dealer_id);
        },
        watch     : {},
        components: {},
        methods   : {
            save() {
                let self     = this;
                self.loading = true;
                // return;
                axios.post('/dealers/dealer_save_coords', {
                    latitude : this.latitude,
                    longitude: this.longitude,
                    id       : this.dealer.id
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
        }
    };
</script>
