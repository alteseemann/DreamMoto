<template>
    <div class="d-flex flex-column" style="width: max-content">
        <h4 class="title mt-2 mb-3" >Укажите местоположение, чтобы клиентам было проще вас найти<sup class="nec">*</sup></h4>
        <div style="width: max-content; max-width: 600px">
            <p>Поставьте маркер вашего местоположения на карте</p>
        </div>
        <!-- Яндекс.Карты -->
        <div class="row m-0 p-0 justify-content-center" style="width: max-content">
            <yandex-map
                :controls="['typeSelector', 'zoomControl','fullscreenControl']"
                :coords="coords"
                @click="setMark"
                zoom="3"
                style="width: 600px; height: 400px; border: 1px solid black"
            >

                <ymap-marker
                    :coords="coords"
                    marker-id="1"
                    hint-content="some hint"
                />

            </yandex-map>
        </div>
        <p>{{coords}}</p>

        <form>

            <input type="hidden" name="_token" :value="token"/>
            <input type="hidden" name="coords" :value="coords"/>

            <div class="d-flex">
                <div class="w-100">
                    <h4 class="title mt-2 mb-3" >Введите адрес и телефон<sup class="nec">*</sup></h4>
                </div>
            </div>

            <div class="form-group row m-0 p-0">
                <label for="city" class="pl-0 text-left col-sm-2 col-form-label">Город</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="city" placeholder="Населенный пункт">
                </div>
            </div>

            <div class="form-group row m-0 p-0">
                <label for="street" class="pl-0 text-left col-sm-2 col-form-label">Улица</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="street" placeholder="Улица">
                </div>
            </div>

            <div class="form-group row m-0 p-0">
                <label for="house" class="pl-0 text-left col-sm-2 col-form-label">Дом</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="house" placeholder="Дом">
                </div>
            </div>

            <div class="d-flex">
                <div class="w-100">
                    <h4 class="title mt-2 mb-3" >Какую технику вы продаете?<sup class="nec">*</sup></h4>
                </div>
            </div>

            <div class="custom-control custom-checkbox text-left" v-for="moto in motos">
                <input type="checkbox" class="custom-control-input" :id="moto.id" v-on:click="findBrands(moto.id)">
                <label class="custom-control-label" :for="moto.id">{{moto.title}}</label>
                <div v-if="moto_types.includes(moto.id)"
                     class="d-flex flex-wrap justify-content-start"
                     style="width: 600px;">
                    <div class="custom-control custom-checkbox"
                         v-for="brand in brands[moto.id]"
                         style="width: 150px">
                        <input type="checkbox" class="custom-control-input" :id="brand.id" v-on:click="set_checked(moto.id,brand.id)">
                        <label class="custom-control-label" :for="brand.id">{{brand.title}}</label>
                    </div>
                </div>
            </div>

            <p>{{moto_types}}</p>

        </form>

    </div>

</template>

<script>
    export default {
        name: "PersonalSettings",
        props:['motos'],
        data() {
            return {
                token:'',
                brands:{},
                moto_types:[],//список типов техники, которые выбрал пользователь
                moto_brands:{},//конечный массив вида [checkedMoto=>[checkedBrand_1,...,checkedBrand_n]]
                coords: [//Координаты в начальный момент отображения на экране
                    65.841076,
                    98.133271
                ],

            }
        },
        mounted() {
            this.token = window.csrf_token
            let options = {
                motos:this.motos
            }
            axios//получаем список брендов для каждого типа техники
                .post('/home/personal/get_brands',options)
                .then(response => {
                    //формируем массив вида [moto_id=>[brand_1,brand_2,...brand_n]]
                    this.brands = response.data.data
                    console.log(response)
                })
                .catch(error=>{
                    console.log(error)
                })
        },
        methods: {
            findBrands(id){
                //Если массив выбранных пользователем типов техники уже содержит конкретный тип, то этот тип
                //будет удален, иначе он будет добавлен
                if (!this.moto_types.includes(id)){
                    this.moto_types.push(id)
                }else{
                    this.moto_types.splice(this.moto_types.indexOf(id),1)
                }
            },
            set_checked(m_id,b_id){

            },
            //Установка маркера на карту, геокодер
            setMark(e){
                this.coords=e.get('coords')
                /*let options = {

                }
                axios
                    .get('',options)
                    .then(response => {
                        console.log(response)
                    })
                    .catch(error=>{
                        console.log(error)
                    })*/

            },


        }
    }
</script>

<style scoped>
    .showForm {
        font-weight: bold;
    }
    .showForm:hover{
        color: #0f74a8;
        cursor: pointer;
    }
    .title{
        width: max-content;
        max-width: 600px;
        text-align: left;
    }
    .nec{
        color: red;
    }

    /*Кнопка, которая откроет форму ввода*/
    a.glo{
        color: #337AB7;
        cursor: pointer;
        padding: 10px 10px 10px 10px;
        width:max-content;
        text-decoration:none;
        border-radius: 30px;
        text-align:center;
        margin-left: 10px;
        display: block;
        background-image: linear-gradient(to left,transparent,transparent 50%,#00c6ff 50%,#337AB7);
        background-position: 100% 0;
        background-size: 200% 100%;
        transition: all .25s ease-in;
        font: 400 18px tahoma;
        border: 2px solid #337AB7;
    }
    a.glo:hover {
        background-position: 0 0;
        color:#fff;
    }
    /*Кнопка, которая откроет форму ввода*/

    /*Всплывающее окно*/

    .popup{
        position: fixed;
        z-index: 50;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.1);
        top: 0;
        left:0;
    }
    .popup_body{
        min-height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 10px;
    }
    .popup_content{
        background-color: #fff;
        border-radius: 30px;
        color: #000;
        width: 1000px;
        height: max-content;
        padding: 30px;
    }
    /*Всплывающее окно*/


</style>
