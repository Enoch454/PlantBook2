<template>
    <div class="">

        <!-- Modal -->
        <div class="modal" :class="{mostrar : modalActivo}" style="overflow-y: scroll">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <label for="nombre">
                                <h4 class="modal-title">Nombre</h4>
                            </label>
                            <br>
                            <!-- entrada para nombre -->
                            <input v-model="planta_temp.nombre" type="text" id="nombre-planta" placeholder="Nombre de la planta">
                            <br>
                            <label for="nCientifico">
                                <h6 class="modal-title">Nombre Cientifico</h6>
                            </label>
                            <br>
                            <!-- entrada para nombre cientifico -->
                            <input v-model="planta_temp.nCientifico" type="text" id="nCientifico-planta" placeholder="Nombre cientifico de la planta">

                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="cerrarModal();">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- entrada para imagen -->
                                    <input class="form-control-file" type="file" id="file-modal" @change="onFileSelected">
                                </div>
                                <div class="col-md-8">
                                    <!-- entrada para info de la planta -->
                                    <textarea v-model="planta_temp.info" required  rows="10" id="info-planta" placeholder="Informacion y datos importantes sobre la planta">
                                    </textarea>
                                    <p>Tambien conocido como</p>
                                    <!-- entrada para nombres alternos -->
                                    <input v-model="planta_temp.nAlterno" type="text" id="nAlterno-planta" placeholder="Otros nombres por lo que llaman a la planta">
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="botones-edit">
                            <button type="button" class="btn btn-primary" @click="guardarPlanta();">Guardar</button>
                            <button type="button" class="btn btn-secondary" @click="cerrarModal();">Descartar</button>
                        </div>
                </div>
            </div>
        </div>


        <h1>Your Dashboard</h1>

        
        <!--Form barra de busqueda-->
        <header>
            <form action="">
                <input v-model="term_busqueda" type="text" class="barra-busqueda" id="barra-busqueda" placeholder="Buscar">
            </form>
        </header>

        <!--Contenedor de fichas-->
        <div class="container">
            <div class="row">
                <div v-for="planta in this.filtrar_lista(lista_plantas)"
                    :key="planta.id" 
                    class="col-lg-3 col-md-4 col-xs-6 thumb">
                    <div
                        @click="editandoRegistro = true; abrirModal(planta)"
                        class="fancybox"
                        rel="ligthbox">
                        <img
                            v-bind:src="get_pathImagen(planta.pathImagen)"
                            class="zoom img-fluid " 
                            v-bind:alt=planta.nombre
                        >
                    </div>    
                </div>
                <!-- boton para crear nueva ficha -->
                <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                    <div
                        class="fancybox"
                        rel="lightbox">
                        <img
                            v-bind:src="get_pathImagen('plus-sign.png')"
                            class="zoom img-fluid " 
                            @click="editandoRegistro = false; abrirModal();"
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
                //lista completa de plantas
                lista_plantas: [],
                //objeto donde se colocan los datos de una planta nueva o a editar
                planta_temp: {
                    info:"",
                    nAlterno:"",
                    nCientifico:"",
                    nombre:"",
                    pathImagen:"",
                },
                //si el modal esta mostrandose o no
                modalActivo: false,
                //si se está editando una ficha de una planta
                editandoRegistro: true,
                //el texto que tiene la barra de busqueda escrito
                term_busqueda: "",
            }
        },
        methods: {
            //Hace peticion a la api local para recibir la lista de las plantas y sus datos
            async listar() {
                const res = await axios.get('/plantas');
                this.lista_plantas = res.data;                
            },
            //se usa para obtener la direccion completa de una imagen
            get_pathImagen(path_imagen){
                var path = "http://"+window.location.host+"/storage/"+path_imagen;
                //console.log(path)
                return path;
            },
            //evento que carga una imagen al formulario
            onFileSelected(event){
                this.planta_temp.pathImagen = event.target.files[0];
                console.log("metodo: onFileSelected()")
                console.log(this.planta_temp.pathImagen);
            },
            //toma los datos en planta_temp para enviarlos a la api local, ya sea par actualizar un 
            //registo o para crear uno nuevo
            async guardarPlanta(){
                var formData = new FormData();
                
                console.log("metodo: guardarPlanta()")
                console.log(this.planta_temp)

                if(this.editandoRegistro){
                    //---NOTA---
                    //Aparentemente funciona para enviar peticion put en json
                    //pero omite el contenido binario (archivo e imagenes)
                    //Sospecho que tendré que hacer un recurso enfocado solo
                    //a la actualizacion de la imagen y definir las conficiones
                    //para usar dicho recurso
                    //--RESUELTO--

                    const qs = require('qs');
                    const res = await axios.put('/plantas/'+this.planta_temp.id, qs.stringify(this.planta_temp));

                    if(document.getElementById("file-modal").value != ''){
                        var formData = new FormData();
                        formData.append('plantaId', this.planta_temp.id);
                        formData.append('pathImagen', this.planta_temp.pathImagen, this.planta_temp.pathImagen.name);
                        const res = await axios.post('/plantas_update_img', formData);
                    }
                    
                }else{
                    formData.append('pathImagen', this.planta_temp.pathImagen, this.planta_temp.pathImagen.name);
                    formData.append('info', this.planta_temp.info);
                    formData.append('nAlterno', this.planta_temp.nAlterno);
                    formData.append('nCientifico', this.planta_temp.nCientifico);
                    formData.append('nombre', this.planta_temp.nombre);

                    const res = await axios.post('/plantas', formData);
                }

                this.cerrarModal();
                this.listar();
            },
            //muestra el modal en pantalla y prepara el contenido de sus campos input
            //ya sea para editar o crear
            abrirModal(planta = {}){
                this.modalActivo = true;
                if (this.editandoRegistro){
                    this.planta_temp.id = planta.id;
                    this.planta_temp.info = planta.info;
                    this.planta_temp.nAlterno = planta.nAlterno;
                    this.planta_temp.nCientifico = planta.nCientifico;
                    this.planta_temp.nombre = planta.nombre;
                    //this.planta_temp.plantaImagen = null;
                    document.getElementById("file-modal").value = '';
                }else{
                    this.planta_temp.id = planta.id;
                    this.planta_temp.info = '';
                    this.planta_temp.nAlterno = '';
                    this.planta_temp.nCientifico = '';
                    this.planta_temp.nombre = '';
                    //this.planta_temp.plantaImagen = null;
                    document.getElementById("file-modal").value = '';
                }
                console.log("metodo: abrirModal()")
                console.log(planta);
            },
            //deja de mostrar el modal en pantalla
            cerrarModal(){
                this.modalActivo=false;
            },
            //concatena algunos datos de la planta que se le indica
            get_full_text(planta){
                var full_text = /*planta.info + */" "
                    + planta.nombre + " "
                    + planta.nCientifico + " "
                    + planta.nAlterno + " ";
                return full_text;
            },
            //indica si una planta dada coincide con el valor en "term_busqueda"
            busqueda(planta){
                if(this.term_busqueda == ''){
                    return true;
                }else{
                    var full_text = this.get_full_text(planta);
                    return(full_text.toLocaleLowerCase().includes(this.term_busqueda.toLocaleLowerCase()))
                }
            },
            //usa la funcion busqueda para generar una lista de las plantas que coinciden con el
            //termino de busqueda. Como indica la condicion en busqueda(), si esta vacio "term_busqueda",
            //entonces se muestran todos los que haya.
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
    
    .botones-edit{
        margin: auto;
        margin-bottom: 15px;
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