<template>
    <div class="product-add">

        <form>

            <div class="input-group">
                <select class="custom-select"
                        style="z-index: 2;"
                        v-model="moto_class_id">
                    <option v-for="class_moto in classes"
                            v-bind:value="class_moto.id">
                        {{class_moto.title}}
                    </option>
                </select>
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
        name      : 'class_edit',
        props     : {
            product_id: {},
            class_id  : {},
            classes   : {},
        },
        data() {
            return {
                moto_class_id: this.class_id,
            }
        },
        mounted() {
        },
        watch     : {},
        components: {},
        methods   : {
            save() {
                let self = this;

                axios.post('/catalog/class_save', {
                    class_id: this.moto_class_id,
                    id      : this.product_id
                }).then(function (response) {
                    console.log(response.data);
                    if (response.data.success) {
                        toastr.success(response.data.class + ', ОК');
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
