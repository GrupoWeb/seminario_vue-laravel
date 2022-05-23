<template>
    <CRow>
        <CCol col="12" xl="12">
            <CCard>
                <CCardHeader color="primary" textColor="white">
                    Proveedores
                </CCardHeader>
                <CCardBody>
                    <CButton color="primary" class="m-2"  @click="show">Añadir</CButton>
                    <CDataTable hover striped border :items="table.response.item" :fields="table.response.fields" :items-per-page="10" pagination
                        :noItemsView='{ noResults: "no se encontro ningun dato", noItems: "Sin datos para mostrar" }'>
                            <template #editar="{item}">
                                <td>
                                   <CButton color="primary" @click="edit( item.value )">Editar</CButton> 
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
                        <b-form-group
                                id="nit"
                                label="Nit:"
                                label-for="nit"
                                invalid-feedback="Dato Requerido"
                                :state="form.states.nitState"
                                
                                >
                                <b-form-input @blur="consultaSat()" v-model="form.nit" placeholder="Ingrese NIT"></b-form-input>
                        </b-form-group>
                        <b-card v-if="skeleton">
                            <b-skeleton animation="fade" width="85%"></b-skeleton>
                            <b-skeleton animation="fade" width="55%"></b-skeleton>
                            <b-skeleton animation="fade" width="70%"></b-skeleton>
                        </b-card>
                        <div v-else>
                            <b-form-group
                                    id="nombre"
                                    label="Nombre:"
                                    label-for="nombre"
                                    invalid-feedback="Dato Requerido"
                                    :state="form.states.nameState"
                                    >
                                    <b-form-input v-model="form.name" placeholder="Ingrese Nombre"></b-form-input>
                            </b-form-group>
                            <b-form-group
                                    id="direccion"
                                    label="Dirección:"
                                    label-for="direccion"
                                    invalid-feedback="Dato Requerido"
                                    :state="form.states.addressState"
                                    >
                                    <b-form-input v-model="form.address" placeholder="Ingrese Direccion"></b-form-input>
                            </b-form-group>
                            <b-form-group
                                    id="telefono"
                                    label="teléfono:"
                                    label-for="telefono"
                                    invalid-feedback="Dato Requerido"
                                    :state="form.states.phoneState"
                                    >
                                    <b-form-input v-model="form.phone" placeholder="Ingrese Teléfono"></b-form-input>
                            </b-form-group>
                            <b-form-group
                                    id="Contacto"
                                    label="Contacto:"
                                    label-for="Contacto"
                                    invalid-feedback="Dato Requerido"
                                    :state="form.states.contactState"
                                    >
                                    <b-form-input v-model="form.contact" placeholder="Ingrese Contacto"></b-form-input>
                            </b-form-group>
                        </div>
                    </b-form>
                    <template #modal-footer="{ ok, cancel}">
                        <b-button size="sm" variant="danger" @click="cancel()">
                            Cancelar
                        </b-button>
                        <b-button size="sm" variant="success" @click="ok()" v-if="!flag">
                            Guardar
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

    export default {
        data() {
            return {
                token: '?token=' + localStorage.getItem("api_token"),
                skeleton: false,
                table: {
                    response: {
                        item: [],
                        fields: [
                            { key: 'value' , label: "Id" },
                            { key: 'nit' , label: "Nit" },
                            { key: 'name' , label: "Descripción" },
                            { key: 'address' , label: "Dirección" },
                            { key: 'contact' , label: "Contacto" },
                            'editar',
                            'eliminar'
                            ]
                    }
                },
                modal: {
                    getter: {
                        show: false,
                        title: 'Nuevo Proveedor',
                        id: 'tipoPago',
                        ref: 'tipoPago',
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
                    states: {
                        nitState: null,
                        nameState: null,
                        addressState: null,
                        phoneState: null,
                        contactState: null,
                    },
                    id: 0,
                    nit: "",
                    name: "",
                    address: "",
                    phone: "",
                    contact: "",
                },
                flag: false
            }
        },
        mounted: function(){
            this.getTipos()
        },
        methods: {
            consultaSat() {
                this.skeleton = !this.skeleton
                axios.get(router[1].get.sat + this.form.nit)
                .then(response => {
                    this.skeleton = !this.skeleton
                    this.form.name = response.data.value.nombre 
                    this.form.address = response.data.value.direccion 
                    this.form.phone = response.data.value.telefono 
                })
            },
            show(){
                this.modal.getter.show = !this.modal.getter.show
                
            },
            getTipos(){
                axios.get(router[1].get.getProveedores + this.token)
                .then(response => {
                    this.table.response.item = response.data
                })
            },
            resetModal() {
                this.form.states.nitState = null
                this.form.states.nameState = null
                this.form.states.addressState = null
                this.form.states.phoneState = null
                this.form.states.contactState = null

                this.form.nit = ""
                this.form.name = ""
                this.form.address = ""
                this.form.phone = ""
                this.form.contact = ""
                if(this.flag){
                    this.flag = !this.flag
                }
            },
            handleOk(bvModalEvent){
                bvModalEvent.preventDefault()
                this.handleSubmit()
            },
            checkFormValidity(){
                const valid = this.$refs.form.checkValidity()
                this.form.states.nitState = valid
                this.form.states.nameState = valid
                this.form.states.addressState = valid
                this.form.states.phoneState = valid
                this.form.states.contactState = valid
                return valid
            },
            handleSubmit(){
                
                if (!this.checkFormValidity()) {
                    return
                }

                axios.post(router[1].post.setProveedores + this.token,{
                    nombre: this.form.name,
                    nit: this.form.nit,
                    direccion: this.form.address,
                    telefono: this.form.phone,
                    contacto: this.form.contact
                })
                .then(response => {
                    if(response.status == 200){
                        this.getTipos()
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
                this.flag = !this.flag

                axios.post(router[1].post.getProveedoresById + this.token, {
                    id: id
                })
                .then(response => {
                    this.form.id = id 
                    this.form.name = response.data[0].name
                    this.form.nit = response.data[0].nit
                    this.form.address = response.data[0].address
                    this.form.phone = response.data[0].phone
                    this.form.contact = response.data[0].contact
                })
                this.show()
            },
            deleted(id){
                axios.put(router[1].put.deleteProveedores + this.token,{
                    id: id
                })
                .then(response => {
                    if(response.status == 200){
                        this.message_success('dato eliminado');
                        this.$nextTick(() => {
                            this.$bvModal.hide(this.modal.getter.id)
                        })
                        this.getTipos();
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
                        this.getTipos();
                    }
                }).catch(error => {
                    this.message_error('Error al actualizar el dato' + error);
                })
            }
        }
        
    }
</script>
