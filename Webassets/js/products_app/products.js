var productsApp = new Vue({
    el:'#products-app',
    mounted:function(){

    },
    data:{
        products:[],
        measurement_units:[],
        categories:[]
    },
    methods:{
        getProducts(){
            let route = new Route('products','products');
            axios({
                method: 'get',
                url: route.route,
            }).then(response => {
                this.products = response.data;
            }).catch(function (error) {
                console.log(error);
            });
        },
        getMeasurementUnits(){
            let route = new Route('Miscellany','measurementUnits');
            axios({
                method: 'get',
                url: route.route,
            }).then(response => {
                this.measurement_units = response.data;
            }).catch(function (error) {
                console.log(error);
            });
        },
        getCategories(){
            let route = new Route('Miscellany','categories');
            axios({
                method: 'get',
                url: route.route,
            }).then(response => {
                this.categories = response.data;
            }).catch(function (error) {
                console.log(error);
            });
        },
        createProduct(){
            let formData = new FormData(document.querySelector('#add-product-form'));
            let route = new Route('products','createProduct');
            axios({
                method: 'post',
                url: route.route,
                data: formData
            }).then(response=> {
                console.log(response.data);
            }).catch(function (error) {
                console.log(error);
            });

        }
    }
})