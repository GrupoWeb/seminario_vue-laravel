<template>
    <CRow>
        <CCol col="12" xl="6">
            <CCard>
                <CCardHeader color="primary" textColor="white">
                    Tipos de Pago
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
                                id="descripcion"
                                label="Descripción"
                                label-for="descripcion"
                                invalid-feedback="Dato Requerido"
                                :state="form.tipoState"
                                >
                                <b-form-input v-model="form.name" placeholder="Ingrese Descripción"></b-form-input>
                        </b-form-group>
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
                table: {
                    response: {
                        item: [],
                        fields: [
                            { key: 'value' , label: "Descripción" },
                            { key: 'name' , label: "Descripción" },
                            'editar',
                            'eliminar'
                            ]
                    }
                },
                modal: {
                    getter: {
                        show: false,
                        title: 'Nuevo Tipo de Pago',
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
                    id: 0,
                    name: "",
                    tipoState: null
                },
                flag: false
            }
        },
        mounted: function(){
            this.getTipos()
        },
        methods: {
            show(){
                this.modal.getter.show = !this.modal.getter.show
                
            },
            getTipos(){
                axios.get(router[1].get.getTipoPago + this.token)
                .then(response => {
                    this.table.response.item = response.data
                })
            },
            resetModal() {
                this.form.name = ""
                this.form.tipoState = null
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
                this.form.tipoState = valid
                return valid
            },
            handleSubmit(){
                
                if (!this.checkFormValidity()) {
                    return
                }

                axios.post(router[1].post.setTipoPago + this.token,{
                    name: this.form.name,
                })
                .then(response => {
                    if(response.status == 200){
                        this.getTipos()
                        this.message_success('Dato Agregado con Exito')
                        this.$nextTick(() => {
                            this.$bvModal.hide(this.modal.getter.id)
                        })
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

                axios.post(router[1].post.getTipoById + this.token, {
                    tipo_id: id
                })
                .then(response => {
                    this.form.id = id 
                    this.form.name = response.data[0].name
                })
                this.show()
            },
            deleted(id){
                axios.put(router[1].put.deleteTipoById + this.token,{
                    tipo_id: id
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
                axios.put(router[1].put.updateTipoPagoById + this.token,{
                    tipo_id: this.form.id,
                    name: this.form.name
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
