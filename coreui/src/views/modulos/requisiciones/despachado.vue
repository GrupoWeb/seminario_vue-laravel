<template>
    <CRow>
        <CCol col="12" xl="12">
            <CCard>
                <CCardHeader color="primary" textColor="white">
                    Requisiciones para Despachar
                </CCardHeader>
                <CCardBody>
                    <CDataTable hover striped border :items="table.response.item" :fields="table.response.fields" :items-per-page="10" pagination
                        :noItemsView='{ noResults: "no se encontro ningun dato", noItems: "Sin datos para mostrar" }'>
                            <template #despachar="{item}">
                                <td>
                                   <CButton color="primary" @click="edit( item.code )">Ver</CButton> 
                                </td>
                            </template>
                            <template #rechazar="{item}">
                                <td>
                                   <CButton color="danger" @click="deleted( item.code )">Rechazar</CButton>
                                </td>
                            </template>
                    </CDataTable>

                </CCardBody>
            </CCard>
            <b-modal
                size="lg"
                @hidden="resetModal"
                @ok="handleOk"
                v-model="modal.getter.show"
                :title="modal.getter.title"
                :id="modal.getter.id"
                :ref="modal.getter.ref"
                :header-bg-variant="modal.getter.header.color"
                :header-text-variant="modal.getter.header.text"
                :footer-bg-variant="modal.getter.footer.color"
                >
                    <b-form ref="form" @submit.stop.prevent="handleSubmit">
                        <CRow>
                            <CCol sm="12">
                                <b-form-group
                                    id="Observaciones"
                                    label="Observación:"
                                    label-for="text_obervacion"
                                >
                                {{ form.obs }}
                                </b-form-group>
                            </CCol>
                        </CRow>
                    </b-form>

                    <b-table class="mt-2" striped hover :items="table.pedido.items" :fields="table.pedido.fields"></b-table>
            
                    <template #modal-footer="{ ok, cancel}">
                        <b-button size="sm" variant="danger" @click="cancel()">
                            Cancelar
                        </b-button>
                        <b-button size="sm" variant="success" @click="ok()" v-if="!flag" :disabled="button.loading">
                            <b-spinner small v-if="button.loading"></b-spinner>
                            Despachar
                        </b-button>
                        <b-button size="sm" variant="success" @click="update()" v-else>
                            Actualizar
                        </b-button>
                    </template>
            </b-modal>
        </CCol>
    </CRow>
</template>

<script>
import axios from 'axios'
import { router } from '../../../utils/router'

import { validationMixin } from "vuelidate"
import { required, minLength } from "vuelidate/lib/validators"


    export default {
        mixins: [validationMixin],
        data() {
            return {
                button: {
                    loading: false
                },
                token: '?token=' + localStorage.getItem("api_token"),
                skeleton: false,
                options:{
                    option_product: [],
                    option_medida: [],
                    option_proveedor: [],
                    option_sucursal: [],
                },
                table: {
                    pedido: {
                        items:[],
                        fields: [
                            { key: 'producto', label: 'Descripción' },
                            { key: 'cantidad', label: 'Cantidad' }
                        ]
                    },
                    response: {
                        item: [],
                        fields: [
                            { key: 'code' , label: "Id" },
                            { key: 'observacion' , label: "Observacion Requisición" },
                            { key: 'estado' , label: "estado" },
                            { key: 'fecha' , label: "Fecha Aprobado" },
                            'despachar',
                            'rechazar'
                            ]
                    }
                },
                modal: {
                    getter: {
                        show: false,
                        title: 'Formulario de Requisición',
                        id: 'tipoPago',
                        ref: 'tipoPago',
                        header: {
                            color: "primary",
                            text: "light"
                        },
                        footer: {
                            color: "light"
                        }
                    },
                    cargar: {
                        show: false,
                        title: 'Cargar Stock',
                        id: 'cargaF',
                        ref: 'cargaF',
                        header: {
                            color: "primary",
                            text: "light"
                        },
                        footer: {
                            color: "light"
                        }
                    }
                },
                form: {
                    id: null,
                    obs: null,
                    producto_id: null,
                    cantidad: null,
                },
                flag: false
            }
        },
        validations: {
            form: {
                producto_id: {
                    required
                },
                cantidad: {
                    required
                },
            }
        },
        mounted: function(){
            this.getInfo()
        },
        methods: {
            validate(item){
                const { $dirty, $error } = item
                return $dirty ? !$error : null
            },
            addRow(){
                if(this.form.producto_id === null &&  this.form.cantidad === null){
                    this.message_error('Ingrese un producto')
                }else if(this.form.producto_id === null ){
                    this.message_error('Ingrese un producto')
                }else if(this.form.cantidad === null ){
                    this.message_error('Ingrese un producto')
                }
                else {
                    axios.post(router[1].post.findProducto + this.token,{
                        id: this.form.producto_id
                    })
                    .then(response => {
                        this.table.pedido.items.push({key: this.form.producto_id, producto: response.data[0].nombre , cantidad: this.form.cantidad})
                        this.form.producto_id = null,
                        this.form.cantidad = null
                    })
                }
            },
            getInfo(){
                const requestProduct = axios.get(router[1].get.listProductosInventario + this.token)
                const requestRequisiciones = axios.get(router[1].get.cargarrequisicionesDespacho + this.token)
                
                axios.all([requestRequisiciones])
                .then(axios.spread((responseRequisiciones) => {
                    // this.options.option_product = responseProducto.data
                    this.table.response.item  = responseRequisiciones.data
                    // this.options.option_proveedor  = responseProv.data
                    // this.options.option_sucursal  = responseSucursal.data
                    // this.table.response.item  = responseInventario.data

                }))

            },

            show(){
                this.modal.getter.show = !this.modal.getter.show
                
            },
            resetModal() {
                
                this.form = {
                    producto_id: null,
                    medida_id: null, 
                    proveedor_id: null,
                    file: null,
                    precio: null,
                    stock: null,
                    sucursal_id:null,
                };

                this.$nextTick(() => {
                    this.$v.$reset();
                });

                if(this.flag){
                    this.flag = !this.flag
                }
            },
            handleOk(bvModalEvent){
                bvModalEvent.preventDefault()
                this.handleSubmit()
            },
            handleOkUpload(bvModalEvent){
                bvModalEvent.preventDefault()
                this.updateStock()
            },
            updateStock(){
                if(this.form.stock == null){
                    this.message_error("Se necesita el valor de stock")
                }else{
                    axios.put(router[1].put.updateStock +this.token ,{
                        id: this.form.id,
                        stock: this.form.stock,
                        update: this.form.update
                    })
                    .then(response => {
                        if(response.status == 200){
                            this.message_success('Dato Actualizado')
                            this.$nextTick(() => {
                                this.$bvModal.hide(this.modal.cargar.id)
                            })
                            this.getInfo()
                        }
                    })
                }

                
            },
            handleSubmit(){
                axios.post(router[1].post.despacharRequisicion + this.token,{
                    id: this.form.id
                })
                .then(response => {
                    if(response.status == 200){
                        this.getInfo()
                        this.message_success('Dato Agregado con Exito')
                        this.$nextTick(() => {
                            this.$bvModal.hide(this.modal.getter.id)
                        })
                    }else{
                        this.message_error('Proveedor ya esta registrado');
                    }
                })
                .catch(error => {
                    this.message_error('No se pudo agregar el dato' + error)
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
            edit(id){
                
                axios.post(router[1].post.RequisicionesAprobarInfo + this.token,{ id: id})
                .then(response => {
                    this.table.pedido.items = response.data
                    this.form.obs = response.data[0].observaciones

                })
                this.modal.getter.show = !this.modal.getter.show
                this.form.id = id

            },
            deleted(id){
                axios.put(router[1].put.rechazarRequisicion + this.token,{
                    id: id
                })
                .then(response => {
                    if(response.status == 200){
                        this.message_success('dato eliminado');
                        this.$nextTick(() => {
                            this.$bvModal.hide(this.modal.getter.id)
                        })
                        this.getInfo();
                    }
                })
                .catch(error => {
                    this.message_error('error al eliminar el dato ' + error)
                })
            },
            update(){
                axios.put(router[1].put.updateProveedores + this.token,{
                    id: this.form.id,
                    nombre: this.form.name,
                    nit: this.form.nit,
                    direccion: this.form.address,
                    telefono: this.form.phone,
                    contacto: this.form.contact
                })
                .then(response => {
                    if(response.status == 200){
                        this.message_success('Dato Actualizado')
                        this.$nextTick(() => {
                            this.$bvModal.hide(this.modal.getter.id)
                        })
                        this.getInfo();
                    }
                }).catch(error => {
                    this.message_error('Error al actualizar el dato' + error);
                })
            }
        }
        
    }
</script>
