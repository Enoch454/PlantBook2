<template>
    <div class="">

        <!-- Modal -->
        <div class="modal" :class="{mostrar : modalActivo}" style="overflow-y: scroll">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h4 class="modal-title">{{planta_seleccionada.nombre}}</h4>
                            <h6 class="modal-title">{{planta_seleccionada.nCientifico}}</h6>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="cerrarModal();">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                    <img
                                        alt="una flor"
                                        class="img-fluid rounded"
                                        v-bind:src="get_pathImagen(planta_seleccionada.plantaImagen)"
                                    />
                                </div>
                                <div class="col-md-8">
                                    <p>{{planta_seleccionada.info}}</p>
                                    <p>Tambien conocido como</p>
                                    <p>{{planta_seleccionada.nAlterno}}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--Form barra de busqueda-->
        <header>
            <form action="">
                <input v-model="term_busqueda" type="text" class="barra-busqueda" id="barra-busqueda" placeholder="Buscar">
            </form>
        </header>

        <div class="container">
            <div class="row">
                <div v-for="planta in this.filtrar_lista(lista_plantas)"
                    :key="planta.id" 
                    class="col-lg-3 col-md-4 col-xs-6 thumb">
                    <div
                        @click="seleccionar(planta)"
                        class="fancybox"
                        rel="ligthbox">
                        <img
                            v-bind:src="get_pathImagen(planta.pathImagen)"
                            class="zoom img-fluid " 
                            alt="una flor"
                        >
                    </div>    
                </div>
            </div>
        </div>







    </div>
</template>

<script>
    export default {
        data () {
            return{
                lista_plantas: [],
                planta_seleccionada: {
                    info:"",
                    nAlterno:"",
                    nCientifico:"",
                    nombre:"",
                    pathImagen:"",
                },
                modalActivo: false,
                term_busqueda: "",
            }
        },
        methods: {
            async listar() {
                const res = await axios.get('/plantas');
                this.lista_plantas = res.data;
                
            },
            get_pathImagen(path_imagen){
                var path = "http://"+window.location.host+"/storage/"+path_imagen;
                console.log(path)
                return path;
            },
            seleccionar(planta){
                this.planta_seleccionada.info = planta.info;
                this.planta_seleccionada.nAlterno = planta.nAlterno;
                this.planta_seleccionada.nCientifico = planta.nCientifico;
                this.planta_seleccionada.nombre = planta.nombre;
                this.planta_seleccionada.plantaImagen = planta.pathImagen;

                this.modalActivo=true;
                console.log(this.planta_seleccionada);
            },
            cerrarModal(){
                this.modalActivo=false;
            },
            get_full_text(planta){
                var full_text = /*planta.info + */" "
                    + planta.nombre + " "
                    + planta.nCientifico + " "
                    + planta.nAlterno + " ";
                return full_text;
            },
            busqueda(planta){
                if(this.term_busqueda == ''){
                    return true;
                }else{
                    var full_text = this.get_full_text(planta);
                    return(full_text.toLocaleLowerCase().includes(this.term_busqueda.toLocaleLowerCase()))
                }
            },
            filtrar_lista(){
                var lista = [];
                for (var i = 0; i<this.lista_plantas.length; i++){
                    if(this.busqueda(this.lista_plantas[i])){
                        lista.push(this.lista_plantas[i]);
                    }
                }
                return lista;
            }

        },
        created() {
            //console.log('Component mounted.')
            this.listar();
        },
        mounted() {
            
        },
    }
</script>

<style>
    .mostrar{
        display: list-item;
        opacity: 1;
        background: rgba(43, 43, 43, 0.705);
    }
    
   
   /*body {
    background-color:#1d1d1d !important;
    font-family: "Asap", sans-serif;
    color:#989898;
    margin:10px;
    font-size:16px;
    }*/

    #demo {
        height:100%;
        position:relative;
        overflow:hidden;
    }

    .green{
        background-color:#6fb936;
    }
    
    .thumb{
        margin-bottom: 30px;
    }
    
    .page-top{
        margin-top:85px;
    }

    img.zoom {
        width: 100%;
        height: 200px;
        border-radius:5px;
        object-fit:cover;
        /*-webkit-transition: all .3s ease-in-out;
        -moz-transition: all .3s ease-in-out;
        -o-transition: all .3s ease-in-out;
        -ms-transition: all .3s ease-in-out;
        */
    }
            
    
    .transition {
        -webkit-transform: scale(1.2); 
        -moz-transform: scale(1.2);
        -o-transform: scale(1.2);
        transform: scale(1.2);
    }
        .modal-header {
    
        border-bottom: none;
    }
    .modal-title {
        color:#000;
    }
    .modal-footer{
    display:none;  
    }

/* Form barra de busqueda */
    header form{
        width: 100%;
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    header .barra-busqueda{
        width: 70%;
        height: 40px;
        line-height: 40px;
        background: #ffffff;
        padding: 0 20px;
        border-radius: 100px;
        border: none;
        text-align: center;
        font-size: 16px;
    }

</style>