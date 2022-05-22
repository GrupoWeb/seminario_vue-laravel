<template>
    <CRow>
        <CCol col="12" xl="12">
            <transition name="slide">
                <CCard>
                    <CModal
                        :title="modal.title"
                        :show.sync="modal.show"
                        :color="modal.color"
                        :size="modal.size"
                        :closeOnBackdrop="modal.close"
                        >
                        <CForm
                            novalidate
                            class="row g-3 needs-validation"
                            ref="form"
                            >
                            <CInput
                                v-model="form.name"
                                label="Nombre"
                                type="input"
                                valid-feedback="Correcto.."
                                invalid-feedback="Campo no puede estar vacio"
                                required
                                was-validated
                            />
                        </CForm>
                        <template #footer>
                            <CButton @click="close" color="danger">Cancelar</CButton>
                            <CButton @click="submit" color="success" v-if="!isUpdate">Guardar</CButton>
                            <CButton @click="updateSubmit" color="success" v-else>Actualizar</CButton>
                        </template>
                    </CModal>

                    <CCardHeader color="primary" textColor="white">
                        Empresas
                    </CCardHeader>
                    <CCardBody>
                        <CButton color="primary" class="m-2" size="lg" @click="show">Añadir</CButton>
                        <CDataTable hover striped :items="table.item" :fields="table.fields" :items-per-page="10" pagination
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
            </transition>
        </CCol>
    </CRow>
</template>

<script>
import axios from 'axios'
import { router } from '../../../utils/router';

    export default {
        data(){
            return {
                isUpdate: false,
                form: {
                    id: 0,
                    name: "",
                },
                modal: {
                    title: "",
                    show: false,
                    color: "dark",
                    size: "sm",
                    close: false
                },
                table: {
                    item: [],
                    fields: [
                        {key: 'value', label: 'id'},
                        {key: 'text', label: 'Nombre'},
                        'editar',
                        'eliminar'
                        ]
                },
                token: '?token=' + localStorage.getItem("api_token"),
            }
        },
        mounted: function(){
            this.getEmpresa()
        },
        methods: {
            clearForm() {
                this.form.id = ""
                this.form.name = ""

                
            },
            getEmpresa(){
                axios.get(router[1].get.getEmpresas+ '?token=' + localStorage.getItem("api_token"))
                .then(response => {
                    this.table.item = response.data
                })
                .catch(err => {
                    this.$swal({
                        icon: 'error',
                        title: 'Error con el servidor' + err,
                        showConfirmButton: false,
                        timer: 2500,
                    })
                })
            },
            show(){
                this.modal.show = !this.modal.show
                this.modal.title = 'Nueva Empresa'
            },
            close(){
                this.clearForm()
                if(this.isUpdate){
                    this.isUpdate = !this.isUpdate
                } 
                this.modal.show = !this.modal.show
            },
            submit(){
                if(this.$refs.form.checkValidity()){
                    axios.post(router[1].post.setEmpresa + this.token ,
                    {
                        name: this.form.name
                    })
                    .then(response => {
                        if(response.status == 200){
                            this.clearForm();
                            this.getEmpresa()
                            this.message_success('Dato ingresado con exito');
                            this.show()
                        }else{
                            this.message_error('Error al agregar la información');
                        }
                    }).catch(error => {
                        this.message_error('Error al agregar la información');
                    })
                }else{
                    this.message_error('Complete todos los campos');
                }
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
                this.isUpdate = !this.isUpdate
                this.modal.title = 'Actualizar Empresa'
                
                axios.post(router[1].post.getEmpresaById + this.token,{
                    id: id
                })
                .then(response => {
                    this.form.id = id
                    this.form.name = response.data[0].name
                    this.show()
                })
            },
            updateSubmit(){
                axios.post(router[1].post.updateEmpresa + this.token,{
                    id: this.form.id,
                    name: this.form.name,
                })
                .then(response => {
                    if (response.status == 200){
                        this.clearForm();
                        this.getEmpresa()
                        this.message_success('Actualización realizada con exito')
                        this.close()
                    }else{
                        this.message_error('Error al actualizar la información');
                    }
                })
                .catch(error => {
                    this.message_error('Error al actualizar la información ', error);
                })
            },
            deleted(id){
                axios.put(router[1].put.deleteEmpresaById + this.token,{
                    id: id
                })
                .then(response => {
                    if(response.status == 200){
                        this.message_success('Dato eliminado con exito');
                        this.getEmpresa()
                    }else{
                        this.message_error('Error en el servidor');
                    }
                })
                .catch(error => {
                    this.message_error('Error del servidor ' + error)
                })
            }

            
        }   
    }
</script>

