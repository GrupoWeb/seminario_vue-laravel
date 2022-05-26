<template>
    <CRow>
        <CCol col="12" xl="12">
            <CCard>
                <CCardHeader color="primary" textColor="white">
                    Inventario
                </CCardHeader>
                <CCardBody>
                    <CButton color="primary" class="m-2"  @click="show">Añadir</CButton>
                    <CDataTable hover striped border :items="table.response.item" :fields="table.response.fields" :items-per-page="10" pagination
                        :noItemsView='{ noResults: "no se encontro ningun dato", noItems: "Sin datos para mostrar" }'>
                            <template #Cargar="{item}">
                                <td>
                                   <CButton color="primary" @click="edit( item.value, item.stock )">Cargar</CButton> 
                                </td>
                            </template>
                            <template #eliminar="{item}">
                                <td>
                                   <CButton color="danger" @click="deleted( item.value )">Eliminar</CButton>
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
                            <CCol sm="6">
                                <b-form-group
                                    id="Producto"
                                    label="Producto:"
                                    label-for="product_id"
                                    invalid-feedback="Seleccione un producto"
                                >
                                    <b-form-select
                                        id="product_id"
                                        v-model="form.producto_id"
                                        :options="options.option_product"
                                        :state="validate($v.form.producto_id)"
                                    ></b-form-select>
                                </b-form-group>
                            </CCol>
                            <CCol sm="6">
                                <b-form-group
                                    id="medida"
                                    label="Medida:"
                                    label-for="medida_id"
                                    invalid-feedback="Seleccione un registro"
                                >
                                    <b-form-select
                                        id="medida_id"
                                        v-model="form.medida_id"
                                        :options="options.option_medida"
                                        :state="validate($v.form.medida_id)"
                                    ></b-form-select>
                                </b-form-group>
                            </CCol>
                        </CRow>
                        <CRow>
                            <CCol sm="12">
                                <b-form-group
                                    id="proveedor"
                                    label="Proveedor:"
                                    label-for="proveedor_id"
                                    invalid-feedback="Seleccione un registro"
                                >
                                    <b-form-select
                                        id="proveedor_id"
                                        v-model="form.proveedor_id"
                                        :options="options.option_proveedor"
                                        :state="validate($v.form.proveedor_id)"
                                    ></b-form-select>
                                </b-form-group>

                            </CCol>
                        </CRow>
                        <CRow>
                            <CCol sm="12">
                                <b-form-group
                                    id="file"
                                    label="Fotografia:"
                                    label-for="file"
                                    invalid-feedback="Seleccione una fotografia"
                                >
                                <b-form-file
                                    v-model="form.file"
                                    :state="Boolean(form.file)"
                                    placeholder="Elija un archivo o suéltelo aquí..."
                                    drop-placeholder="Drop file here..."
                                ></b-form-file>
                                </b-form-group>

                            </CCol>
                        </CRow>
                        <CRow>
                            <CCol sm="6">
                                <b-form-group
                                    id="stock"
                                    label="Stock:"
                                    label-for="stock"
                                    invalid-feedback="Ingrese un dato"
                                >
                                    <b-form-input id="stock" type="number" v-model="form.stock" :state="validate($v.form.stock)"></b-form-input>
                                </b-form-group>

                            </CCol>
                            <CCol sm="6">
                                <b-form-group
                                    id="precio"
                                    label="Precio:"
                                    label-for="precio"
                                    invalid-feedback="Ingrese un dato"
                                >
                                    <b-form-input id="precio" type="number" v-model="form.precio" :state="validate($v.form.precio)"></b-form-input>
                                </b-form-group>

                            </CCol>
                        </CRow>
                        <CRow>
                            <CCol sm="12">
                                <b-form-group
                                    id="Sucursal"
                                    label="Sucursal:"
                                    label-for="sucursal_id"
                                    invalid-feedback="Seleccione un registro"
                                >
                                    <b-form-select
                                        id="sucursal_id"
                                        v-model="form.sucursal_id"
                                        :options="options.option_sucursal"
                                        :state="validate($v.form.sucursal_id)"
                                    ></b-form-select>
                                </b-form-group>
                            </CCol>
                        </CRow>
                    </b-form>
                    <template #modal-footer="{ ok, cancel}">
                        <b-button size="sm" variant="danger" @click="cancel()">
                            Cancelar
                        </b-button>
                        <b-button size="sm" variant="success" @click="ok()" v-if="!flag" :disabled="button.loading">
                            <b-spinner small v-if="button.loading"></b-spinner>
                            Guardar
                        </b-button>
                        <b-button size="sm" variant="success" @click="update()" v-else>
                            Actualizar
                        </b-button>
                    </template>
            </b-modal>

            <b-modal
                size="sm"
                @hidden="resetModal"
                @ok="handleOkUpload"
                v-model="modal.cargar.show"
                :title="modal.cargar.title"
                :id="modal.cargar.id"
                :ref="modal.cargar.ref"
                :header-bg-variant="modal.cargar.header.color"
                :header-text-variant="modal.cargar.header.text"
                :footer-bg-variant="modal.cargar.footer.color"
                >

                <b-form ref="formCarga" @submit.stop.prevent="handleSubmit">
                    <CRow>
                        <b-form-group
                            id="stock"
                            label="Stock:"
                            label-for="stock"
                            invalid-feedback="Ingrese un dato"
                        >
                            <b-form-input id="stock" type="number" v-model="form.stock" ></b-form-input>
                        </b-form-group>
                    </CRow>
                </b-form>
                    <template #modal-footer="{ ok, cancel}">
                        <b-button size="sm" variant="danger" @click="cancel()">
                            Cancelar
                        </b-button>
                        <b-button size="sm" variant="success" @click="ok()" :disabled="button.loading">
                            <b-spinner small v-if="button.loading"></b-spinner>
                            Guardar
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
                    response: {
                        item: [],
                        fields: [
                            { key: 'value' , label: "Id" },
                            { key: 'producto_name' , label: "Producto" },
                            { key: 'medida_name' , label: "Medida" },
                            { key: 'proveedor_name' , label: "Proveedor" },
                            { key: 'precio' , label: "Precio Unitario" },
                            { key: 'stock' , label: "Stock" },
                            { key: 'sucursal_name' , label: "Sucursal" },
                            'Cargar',
                            'eliminar'
                            ]
                    }
                },
                modal: {
                    getter: {
                        show: false,
                        title: 'Ingreso al Inventario',
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
                    producto_id: null,
                    medida_id: null, 
                    proveedor_id: null,
                    file: null,
                    precio: null,
                    stock: null,
                    sucursal_id:null,
                    id: null,
                    update: null
                },
                flag: false
            }
        },
        validations: {
            form: {
                producto_id: {
                    required
                },
                medida_id: {
                    required
                },
                proveedor_id: {
                    required
                },
                precio: {
                    required
                },
                stock: {
                    required
                },
                sucursal_id: {
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

            getInfo(){
                const requestProduct = axios.get(router[1].get.getProductos + this.token)
                const requestMeasures = axios.get(router[1].get.getMedidas + this.token)
                const requestProv = axios.get(router[1].get.getProveedores + this.token)
                const requestSucursal = axios.get(router[1].get.getSucursal + this.token)
                const requestInventario = axios.get(router[1].get.getInventario + this.token)
                
                axios.all([requestProduct, requestMeasures, requestProv, requestSucursal, requestInventario])
                .then(axios.spread((responseProducto, responseMedida, responseProv, responseSucursal, responseInventario) => {
                    this.options.option_product = responseProducto.data
                    this.options.option_medida  = responseMedida.data
                    this.options.option_proveedor  = responseProv.data
                    this.options.option_sucursal  = responseSucursal.data
                    this.table.response.item  = responseInventario.data

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

                this.$v.form.$touch()
                if (this.$v.form.$anyError)
                    return false

                let formData = new FormData();
                formData.append('files', this.form.file);
                formData.append('producto_id', this.form.producto_id);
                formData.append('medida_id', this.form.medida_id);
                formData.append('proveedores_id', this.form.proveedor_id);
                formData.append('precio', this.form.precio);
                formData.append('stock', this.form.stock);
                formData.append('sede_empresa_id', this.form.sucursal_id);
                const config = { headers: {'Content-Type': 'multipart/form-data'} };


                axios.post(router[1].post.setInventario + this.token,formData, config)
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
            edit(id, stock){
                this.modal.cargar.show = !this.modal.cargar.show
                this.form.id = id
                this.form.update = stock

            },
            deleted(id){
                axios.put(router[1].put.deleteStock + this.token,{
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
