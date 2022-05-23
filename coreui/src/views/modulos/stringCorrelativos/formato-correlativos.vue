<template>
    <CRow>
        <CCol col="12" xl="12">
            <CCard>
                <CCardHeader color="primary" textColor="white">
                    Formato de Correlativos
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
                                   <CButton color="danger" @click="deleted( item.value )" v-if="item.numero > 0" disabled>Eliminar</CButton>
                                   <CButton color="danger" @click="deleted( item.value )" v-else >Eliminar</CButton>
                                </td>
                            </template>
                            <template #iniciar="{item}">
                                <td>
                                    <span v-if="item.numero > 0">Correlativo iniciado en: {{ item.numero }}</span>
                                   <CButton color="info" @click="initial( item.value )" v-else>Iniciar</CButton>
                                </td>
                            </template>
                            <template #correlativos="{item}">
                                <td>
                                    <span v-if="item.numero > 0">{{ item.name }}{{ item.numero }}-{{ item.year }} </span>
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
            <b-modal
                @hidden="resetModal"
                @ok="handleOkInitial"
                v-model="modal.initial.show"
                :title="modal.initial.title"
                :id="modal.initial.id"
                :ref="modal.initial.ref"
                :header-bg-variant="modal.initial.header.color"
                :header-text-variant="modal.initial.header.text"
                :footer-bg-variant="modal.initial.footer.color"
                >
                    <b-form ref="formInitial" @submit.stop.prevent="handleSubmitInitial">
                        <b-form-group
                                id="Empresa"
                                label="empresa"
                                label-for="empresa_id"
                                invalid-feedback="Dato Requerido"
                                :state="form.empresaState"
                                >
                                <b-form-select
                                    id="empresa_id"
                                    v-model="form.initial.empresa_id"
                                    :options="table.initial.options"
                                    :state="form.initial.empresaState"
                                    required
                                    ></b-form-select>
                        </b-form-group>
                    </b-form>
                    <template #modal-footer="{ ok, cancel}">
                        <b-button size="sm" variant="danger" @click="cancel()">
                            Cancelar
                        </b-button>
                        <b-button size="sm" variant="success" @click="ok()">
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

    export default {
        data() {
            return {
                token: '?token=' + localStorage.getItem("api_token"),
                table: {
                    response: {
                        item: [],
                        fields: [
                            { key: 'value' , label: "Id" },
                            { key: 'name' , label: "Descripción" },
                            'editar',
                            'eliminar',
                            'iniciar',
                            'correlativos'
                            ]
                    },
                    initial: {
                        options: [],

                    }
                },
                modal: {
                    getter: {
                        show: false,
                        title: 'Nuevo Correlativo',
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
                    initial: {
                        show: false,
                        title: 'Iniciar Correlativo',
                        id: 'initialCorrelativo',
                        ref: 'initialCorrelativo',
                        header: {
                            color: "primary",
                            text: "light"
                        },
                        footer: {
                            color: "light"
                        }
                    },
                    
                },
                form: {
                    id: 0,
                    name: "",
                    tipoState: null,
                    initial: {
                        string_id: 0,
                        empresa_id: 0,
                        empresaState: null
                    }
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
                axios.get(router[1].get.getStringCorrelativo + this.token)
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

                this.form.initial.string_id = 0
                this.form.initial.empresa_id = 0
                this.form.initial.empresaState = null
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
            checkFormValidityInitial(){
                const valid = this.$refs.formInitial.checkValidity()
                this.form.initial.empresaState = valid
                return valid
            },
            handleSubmit(){
                
                if (!this.checkFormValidity()) {
                    return
                }

                axios.post(router[1].post.setStringCorrelativo + this.token,{
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

                axios.post(router[1].post.getStringCorrelativoById + this.token, {
                    tipo_id: id
                })
                .then(response => {
                    this.form.id = id 
                    this.form.name = response.data[0].name
                })
                this.show()
            },
            deleted(id){
                axios.put(router[1].put.deleteStringCorrelativoById + this.token,{
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
                axios.put(router[1].put.updateStringCorrelativoById + this.token,{
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
            },
            initial(id){
                this.modal.initial.show = !this.modal.initial.show
                this.form.initial.string_id = id
                this.getEmpresas()
            },
            getEmpresas(){
                axios.get(router[1].get.getEmpresas + this.token)
                .then(response => {
                    this.table.initial.options = response.data
                })
            },
            handleOkInitial(bvModalEvent){
                bvModalEvent.preventDefault()
                this.handleSubmitInitial()
            },
            handleSubmitInitial(){
                if (!this.checkFormValidityInitial()) {
                    return
                }

                axios.post(router[1].post.setCorrelativoInitial + this.token,{
                    string_id: this.form.initial.string_id,
                    empresa_id: this.form.initial.empresa_id
                })
                .then(response => {
                    if(response.status == 200){
                        this.getTipos()
                        this.message_success('Asociación completada')
                        this.$nextTick(() => {
                            this.$bvModal.hide(this.modal.initial.id)
                        })
                    }
                })
                .catch(error => {
                    this.message_error('no se pudo iniciar los datos' + error)
                })
            },
        }
        
    }
</script>
