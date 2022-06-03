<template>
    <CRow>
        <CCol col="9" xl="9">
            <CCard>
                <CCardHeader color="primary" textColor="white">
                    Facturación
                </CCardHeader>
                <CCardBody>
                    <b-form ref="form" @submit.stop.prevent="handleSubmit">
                        <CRow>
                            <CCol sm="6">
                                <b-input-group class="mt-2 mb-2" prepend="Nit">
                                    <b-form-input id="nit_id"  v-model="form.nit" :state="validate($v.form.nit)" ></b-form-input>
                                    <b-input-group-append>
                                        <b-button variant="primary" @click="consultaSat()">Consultar</b-button>
                                    </b-input-group-append>
                                </b-input-group>
                            </CCol>
                            <CCol sm="6">
                                <b-input-group class="mt-2 mb-2" prepend="Cargar Despacho">
                                    <b-form-input id="nit_id"  v-model="form.requi" ></b-form-input>
                                    <b-input-group-append>
                                        <b-button variant="success" @click="requisicion()">Cargar</b-button>
                                    </b-input-group-append>
                                </b-input-group>
                            </CCol>
                        </CRow>
                        <CRow>
                            <CCol sm="12">
                                <b-form-group id="nombre" label="Cliente:" label-for="cliente_id" invalid-feedback="Ingrese un dato">
                                    <b-form-input id="cliente_id"  v-model="form.nombre" :state="validate($v.form.nombre)" :disabled="disabled_input" ></b-form-input>
                                </b-form-group>
                            </CCol>
                        </CRow>
                        <CRow>
                            <CCol sm="4">
                                <b-form-group id="telefono" label="Teléfono:" label-for="telefono_id" invalid-feedback="Ingrese un dato">
                                    <b-form-input id="telefono_id"  v-model="form.telefono" :state="validate($v.form.telefono)" :disabled="disabled_input"  ></b-form-input>
                                </b-form-group>
                            </CCol>
                            <CCol sm="4">
                                <b-form-group id="correo" label="Correo:" label-for="correo_id" invalid-feedback="Ingrese un dato">
                                    <b-form-input id="correo_id"  v-model="form.correo" :state="validate($v.form.correo)" :disabled="disabled_input" ></b-form-input>
                                </b-form-group>
                            </CCol>
                            <CCol sm="4">
                                <b-form-group id="direccion" label="Dirección:" label-for="direccion_id" invalid-feedback="Ingrese un dato">
                                    <b-form-input id="direccion_id"  v-model="form.direccion" :state="validate($v.form.direccion)" :disabled="disabled_input" ></b-form-input>
                                </b-form-group>
                            </CCol>
                        </CRow>
                        <CRow>
                            <CCol sm="6">
                                <b-form-group
                                    id="tipo"
                                    label="Medio de Pago:"
                                    label-for="tipo_id"
                                    invalid-feedback="Seleccione un registro"
                                >
                                    <b-form-select
                                        id="tipo_id"
                                        v-model="form.tipo"
                                        :options="options.options_tipo"
                                        :state="validate($v.form.tipo)"
                                    ></b-form-select>
                                </b-form-group>
                            </CCol>
                        </CRow>
                        <CRow>
                            <CCol sm="12">
                                <b-card v-if="table_despacho">
                                    <b-skeleton animation="throb" width="85%"></b-skeleton>
                                    <b-skeleton animation="throb" width="55%"></b-skeleton>
                                    <b-skeleton animation="throb" width="70%"></b-skeleton>
                                </b-card>
                                <CDataTable hover striped border :items="table.items" :fields="table.fields" :items-per-page="10" pagination
                                :noItemsView='{ noResults: "no se encontro ningun dato", noItems: "Sin datos para mostrar" }' v-if="table_productos">
                                </CDataTable>
                                <div v-if="!download_factura">
                                    <b-button class="float-right" variant="success" @click="handleSubmit()" v-if="table_productos">Facturar</b-button>
                                </div>
                                <div v-else>
                                    <b-button class="float-right" variant="warning" @click="download()">Descargar Factura</b-button>
                                </div>
                            </CCol>
                        </CRow>
                    </b-form>
                </CCardBody>
            </CCard>
        </CCol>
        <CCol col="3" xl="3">
            <h1 class="titulo">Precio Total:</h1>
            <h1 class="sumatoria">
               {{ sumatoria | currency}}
            </h1>
        </CCol>
    </CRow>
</template>

<style>
    .titulo{
        font-size: 3vw;
     }

     .sumatoria { 
         font-size: 3vw;
     }
</style>


<script>
import axios from 'axios'
import { router } from '../../../utils/router'

import { validationMixin } from "vuelidate"
import { required, minLength } from "vuelidate/lib/validators"


    export default {
        mixins: [validationMixin],
        data() {
            return { 
                token: '?token=' + localStorage.getItem("api_token"),
                options:{
                    options_tipo: []
                },
                form: {
                    nit: null,
                    nombre: null,
                    telefono: null,
                    correo: null,
                    direccion: null,
                    requi:null,
                    tipo: null
                },
                skeleton: {
                    preload: false
                },
                disabled_input: false,
                response: {
                    requi: null
                },
                table: {
                    items:[],
                    fields:  [
                        { key: 'producto', label: 'Descripcion' },
                        { key: 'cantidad', label: 'Cantidad' },
                        { key: 'precio', label: 'precio' },
                    ]
                },
                sumatoria: 0,
                table_despacho: false,
                table_productos: false,
                download_factura: false            
            }
        },
        validations: {
            form: {
                nit: {
                    required
                },
                nombre: {
                    required
                },
                telefono: {
                    required
                },
                correo: {
                    required
                },
                direccion: {
                    required
                },
                tipo: {
                    required
                }
            }
        },
        mounted: function(){
            this.getTipos();
        },
        methods: {
            getTipos(){
                axios.get(router[1].get.getTipoPago + this.token)
                .then(response => {
                    this.options.options_tipo = response.data
                })
            },
            message_success(title) {
                this.$swal({
                    icon: "success",
                    title: title,
                    showConfirmButton: false,
                    timer: 2500
                })
            },
            message_error(title) {
                this.$swal({
                    icon: "error",
                    title: title,
                    showConfirmButton: false,
                    timer: 2500
                })
            },
            validate(item){
                const { $dirty, $error } = item
                return $dirty ? !$error : null
            },
            handleSubmit(){
                axios.post(router[1].post.setFactura + this.token, {
                    nit: this.form.nit,
                    correlativo: this.form.requi,
                    nombre: this.form.nombre,
                    direccion: this.form.direccion,
                    telefono: this.form.telefono,
                    correo: this.form.correo,
                    tipo_id: this.form.tipo,
                    monto: this.sumatoria
                })
                .then(response => {
                    if(response.status == 200){
                        this.message_success("Factura Realizada")
                        this.clearForm()
                        this.mostrarFactura()

                    }
                })
            },
            mostrarFactura(){
                this.download_factura = !this.download_factura
            },
            consultaSat() {
                if(this.form.nit === null){
                    this.message_error("Ingrese un nit")
                }else{
                    this.skeleton.preload = !this.skeleton.preload
                    axios.get(router[1].get.sat + this.form.nit)
                    .then(response => {
                        this.skeleton.preload = !this.skeleton.preload
                        this.disabled_input = !this.disabled_input
                        this.form.nombre = response.data.value.nombre 
                        this.form.direccion = response.data.value.direccion 
                        this.form.telefono = response.data.value.telefono 
                        this.form.correo = response.data.value.correo 
                    })
                }
            },
            clearForm(){
                this.form.nit = null,
                this.form.nombre = null,
                this.form.telefono = null,
                this.form.correo = null,
                this.form.direccion = null,
                this.form.requi = null,
                this.form.tipo = null
                this.sumatoria = 0
                this.table.items = []
                this.table_despacho = false
                this.table_productos = false  
            },
            requisicion(){
                this.table_despacho = !this.table_despacho
                if(this.form.nit === null){
                    this.message_error("Ingrese un nit")
                    this.table_despacho = !this.table_despacho
                }else{
                    axios.post(router[1].post.cargarItems + this.token,{correlativo: this.form.requi})
                    .then(response => {
                        if(response.data ===  true){
                            this.table_despacho = !this.table_despacho
                            this.download_factura = !this.download_factura
                        }else{
                            this.table.items = response.data
                            response.data.forEach(element => {
                                this.sumatoria += (element.precio * element.cantidad)
                            });
                            this.table_despacho = !this.table_despacho
                            this.table_productos = !this.table_productos
                        }
                    })
                }
            },
            download(){
                axios.post(router[1].post.pdf + this.token, {flag: false, correlativo: this.form.requi}, { responseType: "blob" })
                .then(response => {

                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement("a");
                    fileLink.href = fileURL;
                    fileLink.setAttribute("download", 'test.pdf');
                    document.body.appendChild(fileLink);
                    fileLink.click();
                    // this.message_success('Descargando documento');
                })
            }
        }
        
    }
</script>
