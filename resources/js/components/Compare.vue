<template>
    <div>
        <a href="javascript:void(0);"
           class="px-0 compare-btn"
           data-toggle="dropdown"
           aria-haspopup="true"
           @click="load()">
            <span
                    class="compare-count"
                    v-show="motos.length">{{ motos.length }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" style="width: 200px;">
            <strong v-show="!loading" class="pb-2 mb-2 px-4 d-block border-bottom">Сравнить</strong>
            <div class="m-3 text-center"
                 v-show="!motos.length">Модели для сравнения не добавлены</div>
            <div class="my-3 text-center"
                 v-show="loading">
                <span class="spinner-grow spinner-grow-sm my-spinner" role="status" aria-hidden="true"
                      v-if="loading"></span>
            </div>
            <a v-show="!loading"
               v-for="(moto) in compare_list"
               class="dropdown-item"
               :href="'/' + moto.alias + '/catalog/compare?' + encodeURI('model[]=' + moto.products.map( number => number.id).join('&model[]='))">
                <span>{{ moto.title }}</span> <span class="moto-count">{{ moto.products.length }}</span>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        name      : 'compare',
        props     : {},
        data() {
            return {
                loading     : false,
                motos       : [],
                compare_list: {}
            }
        },
        mounted() {
            let self = this;

            this.$bus.$on('compare-add', (event) => {
                this.motos = JSON.parse(localStorage.getItem('motos'));
            });
            this.$bus.$on('compare-remove', (event) => {
                this.motos = JSON.parse(localStorage.getItem('motos'));
            });

            if (localStorage.getItem('motos')) {
                try {
                    this.motos = JSON.parse(localStorage.getItem('motos'));
                } catch (e) {
                    localStorage.removeItem('motos');
                }
            }
        },
        components: {},
        watch     : {},
        methods   : {
            load() {
                let self     = this;
                if (!this.motos.length) {
                    return;
                }
                self.loading = true;
                axios.post('/get-compare-list', {
                    product_list: this.motos,
                }).then(function (response) {
                    self.compare_list = response.data.compare_list;
                    self.loading = false;
                    console.log(response.data.compare_list);
                }).catch(error => {
                    console.log('!');
                });
            }
        },
    }
</script>
