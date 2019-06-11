var productsList = new Vue({
    el:'#product-list',
    mounted:function(){
      this.products.push({name:'Producto1',measurementUnits:'UN',price:50.88});
      this.products.push({name:'Producto2',measurementUnits:'UN',price:65});
    },
    data:{
        products:[

        ]
    },
    methods:{

    }
})