<template>

        <div>
            <form>
                <template v-for="(group, group_index) in edit_parameter_data">
                    <h2>{{ group.title }}</h2>
                    <div class="form-group" v-for="(parameter, parameter_index) in group.parameter_names">

                        <label for="exampleFormControlInput1">{{ parameter.title }}</label>

                        <template v-if="parameter.parameter_name_terms.length">

                            <div class="input-group">
                                <select class="form-control"
                                        v-model.trim="parameter.val"
                                        v-bind:ref="'parameter' + parameter.id">
                                    <option disabled value="">Выберите один из вариантов</option>
                                    <option
                                        v-for="term in parameter.parameter_name_terms"
                                        v-bind:value="term.title">
                                        {{term.title}}
                                    </option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button"
                                            @click="clear(group_index, parameter_index)">Очистить
                                    </button>
                                </div>
                            </div>

                        </template>

                        <template v-else>
                            <div class="input-group">
                                <input type="text" class="form-control"
                                       v-bind:placeholder="'Введите параметр: ' + parameter.title"
                                       v-model.trim="parameter.val"
                                       v-bind:ref="'parameter' + parameter.id">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button"
                                            @click="clear(group_index, parameter_index)">Очистить
                                    </button>
                                </div>
                            </div>
                        </template>

                    </div>
                </template>

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

        name: 'product_edit',
        props: {
            parameters: {},
            current_product: {
                type: String,
                'default': function () {
                    return '';
                }
            },
        },
        data() {
            return {
                product: this.current_product,
                edit_parameter_data: this.parameters,
            }
        },
        mounted() {
            let self = this;
        },
        watch: {},
        methods: {
            save() {
                console.log(this.parameters);
                axios.post('/catalog/parameters_change', {
                    parameters: this.edit_parameter_data,
                    current_product: this.product,
                })
                    .then(function (response) {
                        // self.edit_user_data.loading = false;
                        console.log('error', response);
                        toastr.success('Данные обновлены');

                        if (response.data.success) {
                            console.log('error', response);
                            toastr.success('Данные обновлены');
                        }

                        if (response.data.error) {
                            console.log('error', response.data.error);
                            toastr.warning('Ошибка');
                        }
                    })
                    .catch(function (error) {
                        // self.edit_user_data.loading = false;
                        if (error.response.status == 422) {
                            self.server_error = true;
                        }
                    });
            },
            clear(i, j) {
                this.edit_parameter_data[i].parameter_names[j].val = '';
                console.log(i, j);
            }
        }
    };

</script>
