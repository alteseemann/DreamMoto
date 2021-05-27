<template>
    <div>
        <a
                v-show="motos.indexOf( product.id ) != -1"
                href="#"
                class="compare-link">
            Сравнить
        </a>
        <a href="javascript:void(0);"
           class="compare-add"
           title="Добавить к сравнению"
           v-show="motos.indexOf( product.id ) == -1"
           @click="add()">
            <i class="fal fa-layer-plus" style="font-size: 1rem;"></i>
        </a>
        <a href="javascript:void(0);"
           class="compare-add added"
           title="Удалить из сравнения"
           v-show="motos.indexOf( product.id ) != -1"
           @click="add()">
            <i class="fal fa-layer-minus" style="font-size: 1rem;"></i>
        </a>
    </div>
</template>

<script>
    export default {
        name      : 'compare',
        props     : {
            product: {},
        },
        data() {
            return {
                motos: [],
            }
        },
        mounted() {
            let self = this;
            // localStorage.removeItem('motos');
            // localStorage.removeItem('motos_count');

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
            // console.log(this.product);
        },
        components: {},
        watch     : {},
        methods   : {
            add() {
                let self = this;

                if (this.motos.indexOf(this.product.id) == -1) {

                    this.motos.push(self.product.id);
                    const parsed = JSON.stringify(this.motos);
                    localStorage.setItem('motos', parsed);
                    this.$bus.$emit('compare-add', {});
                    toastr.success(this.product.brand.title + ' ' + this.product.title + ' добавлен к сравнению');

                } else {

                    this.motos.splice(this.motos.findIndex(el => el === this.product.id), 1);
                    const parsed = JSON.stringify(this.motos);
                    localStorage.setItem('motos', parsed);
                    this.$bus.$emit('compare-remove', {});
                    toastr.warning(this.product.brand.title + ' ' + this.product.title + ' удален из сравнения');

                }
            }
        },
    }
</script>

<style>
    a.compare-link {
        position         : absolute;
        right            : 32px;
        top              : 0;
        color            : #3490dc;
        z-index          : 10;
        padding          : 3px 8px 4px;
        background-color : rgba(255, 255, 255, .7);
    }

    a.compare-link:hover {
        background-color : rgba(0, 0, 0, .1);
    }

    a.compare-add {
        position         : absolute;
        right            : 0;
        top              : 0;
        padding          : 3px 8px 4px;
        background-color : rgba(0, 0, 0, .3);
        color            : #fff;
        z-index          : 10;
    }

    a.added {
        background-color : rgba(227, 52, 47, .7);
    }

    a.compare-add:hover {
        background-color : rgba(0, 0, 0, .5);
    }

    a.added:hover {
        background-color : rgba(227, 52, 47, .9);
    }
</style>