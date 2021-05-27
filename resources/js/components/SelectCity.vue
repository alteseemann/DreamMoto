<template>
    <div class="select-city-component d-flex">
        <a href="javascript:void(0);" class="px-0 select-city" data-toggle="modal" data-target="#citiesModal"
           @click="load()">
            <span class="d-none d-sm-inline">
            <template v-if="current_city_name">
                {{ current_city_name }}
            </template>
            <template v-else="current_city_name">
                Выбрать регион
            </template>
            </span>
        </a>

        <compare></compare>

        <!--        Кнопки добавления в избранное и сравнение моделей-->
        <!--        <a href="javascript:void(0);" class="mr-4 align-self-center icon-btn"><i class="fal fa-heart"></i></a>-->
        <!--        <a href="javascript:void(0);" class="align-self-center icon-btn"><i class="fal fa-clone"></i></a>-->

        <!-- Modal -->
        <div class="modal fade" id="citiesModal" tabindex="-1" role="dialog" aria-labelledby="citiesModalTitle"
             aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 900px;" v-if="open_modal">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h5 class="modal-title align-self-center mr-4" id="citiesModalTitle">Выберите город</h5>
                        <button type="button" class="btn btn-primary align-self-center" v-if="current_city_name"
                                @click="reset_city()">Любой город
                        </button>
                        <button type="button" class="close align-self-center" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="cities-wrap">
                            <template v-for="alpha in cities">
                                <div class="city-alpha">
                                    <b>{{alpha.letter}}</b>
                                    <a href="javascript:void(0);" class="d-block" v-for="city in alpha.cities"
                                       @click="sel_city( city.title, city.alias )">
                                        {{ city.title }}
                                    </a>
                                </div>
                            </template>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name      : 'select-city',
        props     : {
            block : {},
            cities: {},
            ratio : {
                default: false
            },
        },
        data() {
            return {
                open_modal       : false,
                current_city_name: '',
            }
        },
        mounted() {
            let self = this;
            if (this.$cookie.get('current_city_name')) {
                this.current_city_name = this.$cookie.get('current_city_name');
            }
            $("#citiesModal").on('hidden.bs.modal', function (event) {
                self.open_modal = false;
            });
            // toastr.success(window.location.pathname);
        },
        components: {},
        watch     : {
            'test.vmodel': {
                handler: function (val, oldVal) {
                    let self = this;

                },
                deep   : true
            },
        },
        methods   : {
            sel_city($title, $alias) {
                var city_alias = this.$cookie.get('current_city_alias');
                if (city_alias) {
                    var current_path = window.location.pathname;
                    console.log('current_path', '-', current_path);
                    var redirect_path = current_path.replace(city_alias, $alias);
                    console.log('redirect_path', '-', redirect_path);
                    // return;
                } else {
                    var current_path = window.location.pathname;
                    console.log('current_path', '-', current_path);
                    if (current_path != '/') {
                        var redirect_path = '/' + $alias + current_path;
                    } else {
                        var redirect_path = '/' + $alias;
                    }
                    console.log('redirect_path', '-', redirect_path);
                    // return;
                }
                this.$cookie.set('current_city_name', $title);
                this.$cookie.set('current_city_alias', $alias);
                this.current_city_name = this.$cookie.get('current_city_name');
                console.log(this.current_city_name);
                $('#citiesModal').modal('hide');
                console.log(redirect_path);
                // return;
                setTimeout(function () {
                    window.location.href = redirect_path
                }, 500);
                // window.location.reload();
            },
            reset_city() {
                var city_alias = this.$cookie.get('current_city_alias');
                if (city_alias) {
                    var current_path  = window.location.pathname;
                    var redirect_path = current_path.replace(city_alias, '').replace('//', '/');
                }
                this.$cookie.delete('current_city_name');
                this.$cookie.delete('current_city_alias');
                this.current_city_name = this.$cookie.get('current_city_name');
                $('#citiesModal').modal('hide');
                setTimeout(function () {
                    window.location.href = redirect_path
                }, 500);
            },
            load() {
                this.open_modal = true;
                // axios.get('/load-cities').then(response => {
                //     this.cities = response.data;
                //     // console.log(this.cities);
                // }).catch(error => {
                //     if (error.response.status === 422) {
                //         this.errors = error.response.data.errors || {};
                //     }
                // });
            },
            test() {

                let self = this;

            },
        },
    }
</script>
