<template>
    <div class="dealers-wrapper w-100">

        <template v-if="dealers.length < 1">
            <div class="px-3 py-4 border-top position-relative text-center">
                <p>По данным условиям поиска дилеры не найдены</p>
                <p>Попробуйте изменить критерии или <a class="link" :href="'/'+current_moto.alias+'/dealers'">смотрите всех дилеров {{ current_moto.title_chego }}</a></p>
            </div>

<!--            РСЯ -->
            <div class="border-top border-bottom position-relative">
                <ad-block
                        :div_id="'adblock-3'"
                        script='(function(w, d, n, s, t) {
                w[n] = w[n] || [];
                w[n].push(function() {
                    Ya.Context.AdvManager.render({
                blockId: "R-A-347397-18",
                renderTo: "adblock-3",
                async: true,
                onRender: function (data) {
                $(document.body).trigger("sticky_kit:recalc");
                console.log(data.product);
                }
                });
                });
                t = d.getElementsByTagName("script")[0];
                s = d.createElement("script");
                s.type = "text/javascript";
                s.src = "//an.yandex.ru/system/context.js";
                s.async = true;
                t.parentNode.insertBefore(s, t);
                })(this, this.document, "yandexContextAsyncCallbacks");'
                >
                </ad-block>
            </div>
        </template>

        <template v-for="(dealer, index) in dealers">
            <div class="dealer p-3 border-top position-relative"
                 @mouseover="mouseenter(index)"
                 @mouseleave="mouseleave(index)">

                <h5><a class="stretched-link"
                       :href="'/dealers/' + dealer.alias"><b>{{ dealer.title }}</b>
                </a>
                </h5>
                <b>{{ dealer.city.title }}</b>
                <p class="my-2">{{ dealer.address }}</p>
                {{ dealer.phone }}
            </div>

<!--            РСЯ-->
            <div class="border-top position-relative"
                 v-if="index == 1">
                <ad-block
                        :div_id="'adblock-3'"
                        script='(function(w, d, n, s, t) {
                w[n] = w[n] || [];
                w[n].push(function() {
                    Ya.Context.AdvManager.render({
                blockId: "R-A-347397-18",
                renderTo: "adblock-3",
                async: true,
                onRender: function (data) {
                $(document.body).trigger("sticky_kit:recalc");
                console.log(data.product);
                }
                });
                });
                t = d.getElementsByTagName("script")[0];
                s = d.createElement("script");
                s.type = "text/javascript";
                s.src = "//an.yandex.ru/system/context.js";
                s.async = true;
                t.parentNode.insertBefore(s, t);
                })(this, this.document, "yandexContextAsyncCallbacks");'
                >
                </ad-block>
            </div>

        </template>
        <div class="border-top position-relative"
             v-if="dealers.length > 9">
            <ad-block
                    :div_id="'R-A-347397-171'"
                    script='(function(w, d, n, s, t) {
                w[n] = w[n] || [];
                w[n].push(function() {
                    Ya.Context.AdvManager.render({
                blockId: "R-A-347397-17",
                renderTo: "R-A-347397-171",
                async: true,
                onRender: function (data) {
                $(document.body).trigger("sticky_kit:recalc");
                console.log(data.product);
                }
                });
                });
                t = d.getElementsByTagName("script")[0];
                s = d.createElement("script");
                s.type = "text/javascript";
                s.src = "//an.yandex.ru/system/context.js";
                s.async = true;
                t.parentNode.insertBefore(s, t);
                })(this, this.document, "yandexContextAsyncCallbacks");'
            >
            </ad-block>
        </div>
    </div>

</template>

<script>
    export default {

        name      : 'dealers_list',
        props     : {
            dealers     : {},
            current_moto: {},
        },
        data() {
            return {
                msg: '',
            }
        },
        mounted() {
            let self = this;
        },
        components: {},
        watch     : {},
        methods   : {
            mouseenter(id) {
                // console.log(id);
                this.$bus.$emit('mouseenter', {
                    id: id
                });
            },
            mouseleave(id) {
                // console.log(id);
                this.$bus.$emit('mouseleave', {
                    id: id
                });
            },
        },
    }
</script>
