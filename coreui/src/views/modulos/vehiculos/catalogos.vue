<template>
    <CRow>
        <CCol col="12" xl="12">
            <CCard>
                <CCardHeader color="primary" textColor="white">
                    Marcas de Vehiculos
                </CCardHeader>
                <CCardBody>
                    <CButton color="primary" class="m-2"  @click="show('marcas')">Añadir</CButton>
                    <CDataTable hover striped border :items="table.marcas.item" :fields="table.marcas.fields" :items-per-page="10" pagination
                        :noItemsView='{ noResults: "no se encontro ningun dato", noItems: "Sin datos para mostrar" }'>
                            <template #editar="{item}">
                                <td>
                                   <CButton color="primary" @click="edit( item.value, 'marcas' )">Editar</CButton> 
                                </td>
                            </template>
                            <template #eliminar="{item}">
                                <td>
                                   <CButton color="danger" @click="deleted( item.value, 'marcas'  )" >Eliminar</CButton>
                                </td>
                            </template>
                    </CDataTable>

                </CCardBody>
            </CCard>
            
            <CCard>
                <CCardHeader color="primary" textColor="white">
                    Lineas de Vehiculos
                </CCardHeader>
                <CCardBody>
                    <CButton color="primary" class="m-2"  @click="show('lineas')">Añadir</CButton>
                    <CDataTable hover striped border :items="table.lineas.item" :fields="table.lineas.fields" :items-per-page="10" pagination
                        :noItemsView='{ noResults: "no se encontro ningun dato", noItems: "Sin datos para mostrar" }'>
                            <template #editar="{item}">
                                <td>
                                   <CButton color="primary" @click="edit( item.value, 'lineas' )">Editar</CButton> 
                                </td>
                            </template>
                            <template #eliminar="{item}">
                                <td>
                                   <CButton color="danger" @click="deleted( item.value, 'lineas' )" >Eliminar</CButton>
                                </td>
                            </template>
                    </CDataTable>

                </CCardBody>
            </CCard>
            
            <CCard>
                <CCardHeader color="primary" textColor="white">
                    Transmisiones de Vehiculos
                </CCardHeader>
                <CCardBody>
                    <CButton color="primary" class="m-2"  @click="show('transmisiones')">Añadir</CButton>
                    <CDataTable hover striped border :items="table.transmision.item" :fields="table.transmision.fields" :items-per-page="10" pagination
                        :noItemsView='{ noResults: "no se encontro ningun dato", noItems: "Sin datos para mostrar" }'>
                            <template #editar="{item}">
                                <td>
                                   <CButton color="primary" @click="edit( item.value, 'transmisiones' )">Editar</CButton> 
                                </td>
                            </template>
                            <template #eliminar="{item}">
                                <td>
                                   <CButton color="danger" @click="deleted( item.value, 'transmisiones' )"  >Eliminar</CButton>
                                </td>
                            </template>
                    </CDataTable>

                </CCardBody>
            </CCard>

            <CCard>
                <CCardHeader color="primary" textColor="white">
                    Tipo de Vehiculos
                </CCardHeader>
                <CCardBody>
                    <CButton color="primary" class="m-2"  @click="show('tipo_vehiculos')">Añadir</CButton>
                    <CDataTable hover striped border :items="table.tipos.item" :fields="table.tipos.fields" :items-per-page="10" pagination
                        :noItemsView='{ noResults: "no se encontro ningun dato", noItems: "Sin datos para mostrar" }'>
                            <template #editar="{item}">
                                <td>
                                   <CButton color="primary" @click="edit( item.value, 'tipo_vehiculos' )">Editar</CButton> 
                                </td>
                            </template>
                            <template #eliminar="{item}">
                                <td>
                                   <CButton color="danger" @click="deleted( item.value , 'tipo_vehiculos')" >Eliminar</CButton>
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
                    marcas: {
                        item: [],
                        fields: [
                            { key: 'value' , label: "Id" },
                            { key: 'name' , label: "Descripción" },
                            'editar',
                            'eliminar',
                            ]
                    },
                    lineas: {
                        item: [],
                        fields: [
                            { key: 'value' , label: "Id" },
                            { key: 'name' , label: "Descripción" },
                            'editar',
                            'eliminar',
                            ]
                    },
                    transmision: {
                        item: [],
                        fields: [
                            { key: 'value' , label: "Id" },
                            { key: 'name' , label: "Descripción" },
                            'editar',
                            'eliminar',
                            ]
                    },
                    tipos: {
                        item: [],
                        fields: [
                            { key: 'value' , label: "Id" },
                            { key: 'name' , label: "Descripción" },
                            'editar',
                            'eliminar',
                            ]
                    },

                },
                modal: {
                    Model:"",
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
                    model: "",
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
            this.getData()
        },
        methods: {
            show(Model){
                this.modal.Model = Model
                this.modal.getter.show = !this.modal.getter.show
                
            },
            getData(){

                const requestMarcas = axios.post(router[1].post.getData + this.token, { model: 'marcas'});
                const requestLinea = axios.post(router[1].post.getData + this.token, { model: 'lineas'});
                const requesttransmision = axios.post(router[1].post.getData + this.token, { model: 'transmisiones'});
                const requestTipos = axios.post(router[1].post.getData + this.token, { model: 'tipo_vehiculos'});


                axios.all([requestMarcas, requestLinea, requesttransmision, requestTipos])
                .then(axios.spread((responseMarca, responseLinea, responseTransmision, responseTipos) => {
                    this.table.marcas.item = responseMarca.data
                    this.table.lineas.item = responseLinea.data
                    this.table.transmision.item = responseTransmision.data
                    this.table.tipos.item = responseTipos.data

                }))
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

                axios.post(router[1].post.setData + this.token,{
                    model: this.modal.Model,
                    name: this.form.name,
                })
                .then(response => {
                    console.log(response.data)
                    if(response.status == 200){
                        this.getData()
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
            edit(id, model){
                this.flag = !this.flag
                this.form.model = model

                axios.post(router[1].post.getDataById + this.token, {
                    model: model,
                    id: id
                })
                .then(response => {
                   
                    this.form.id = id 
                    this.form.name = response.data[0].name
                })
                this.show()
            },
            deleted(id, model){

                this.form.model = model

                axios.put(router[1].put.getDeleteDataById + this.token,{
                    model: this.form.model,
                    id: id
                })
                .then(response => {
                    if(response.status == 200){
                        this.message_success('dato eliminado');
                        this.$nextTick(() => {
                            this.$bvModal.hide(this.modal.getter.id)
                        })
                        this.getData();
                    }
                })
                .catch(error => {
                    this.message_error('error al eliminar el dato ' + error)
                })
            },
            update(){
                axios.put(router[1].put.getUpdateDataById + this.token,{
                    model: this.form.model,
                    id: this.form.id,
                    name: this.form.name
                })
                .then(response => {
                    if(response.status == 200){
                        this.message_success('Dato Actualizado')
                        this.$nextTick(() => {
                            this.$bvModal.hide(this.modal.getter.id)
                        })
                        this.getData();
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
