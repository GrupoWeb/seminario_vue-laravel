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
                            <CRow>
                                <CCol sm="6">
                                        <label for="departamento_id">Departamento</label>
                                        <b-form-select id="departamento_id" v-model="form.departamento_id" :options="request.departamento" class="mb-3" @input="seleccionData()" required>
                                            <template #first>
                                                <b-form-select-option :value="null" disabled>-- Seleccione --</b-form-select-option>
                                            </template>
                                        </b-form-select>
                                </CCol>
                                <CCol sm="6">
                                    <label for="municipio_id">Municipio</label>
                                    <b-form-select id="municipio_id" v-model="form.municipio_id" :options="request.municipio" class="mb-3" required>
                                        <template #first>
                                            <b-form-select-option :value="null" disabled>-- Seleccione --</b-form-select-option>
                                        </template>
                                    </b-form-select>
    
                                </CCol>
                            </CRow>
                            <CInput
                                v-model="form.name"
                                label="Nombre"
                                type="input"
                                valid-feedback="Correcto.."
                                invalid-feedback="Campo no puede estar vacio"
                                required
                                was-validated
                            />
                            <CInput
                                v-model="form.phone"
                                label="Teléfono"
                                type="input"
                                valid-feedback="Correcto.."
                                invalid-feedback="Campo no puede estar vacio"
                                required
                                was-validated
                            />
                            <CInput
                                v-model="form.address"
                                label="Dirección"
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

                    <CCardHeader>
                        Sucursales
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
                    phone: "",
                    address: "",
                    departamento_id : 0,
                    municipio_id : 0
                },
                modal: {
                    title: "Nueva Sucursal",
                    show: false,
                    color: "dark",
                    size: "lg",
                    close: false
                },
                table: {
                    item: [],
                    fields: [
                        {key: 'value', label: 'id'},
                        {key: 'name', label: 'Nombre'},
                        {key: 'number', label: 'Telefono'},
                        {key: 'address', label: 'Dirección'},
                        {key: 'departament', label: 'Departamento'},
                        {key: 'municipality', label: 'Municipio'},
                        'editar',
                        'eliminar'
                        ]
                },
                request: {
                    departamentos: [],
                    municipio: [],
                },
                token: '?token=' + localStorage.getItem("api_token"),
            }
        },
        mounted: function(){
            this.getSede()
            this.getDepartamento()
        },
        methods: {
            clearForm() {
                this.form.name = ""
                this.form.phone = ""
                this.form.address = ""
                this.form.departamento_id = 0
                this.form.municipio_id = 0
                
            },
            getSede(){
                axios.get(router[1].get.getSede+ '?token=' + localStorage.getItem("api_token"))
                .then(response => {
                    this.table.item = response.data
                })
                .catch(err => {
                    this.$swal({
                        icon: 'error',
                        title: 'Error con el servidor',
                        showConfirmButton: false,
                        timer: 2500,
                    })
                })
            },
            show(){
                this.modal.show = !this.modal.show
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
                    axios.post(router[1].post.setSede + this.token ,
                    {
                        name: this.form.name,
                        phone: this.form.phone,
                        address: this.form.address,
                        departamento_id: this.form.departamento_id,
                        municipio_id:   this.form.municipio_id
                    })
                    .then(response => {
                        if(response.status == 200){
                            this.clearForm();
                            this.getSede()
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
            getDepartamento() {
                axios.get(router[1].get.departamentos + this.token)
                .then(response => {
                    this.request.departamento = response.data
                })
            },
            getMunicipioFilter(id){
                axios.post(router[1].post.getMunicipioByIdSede + this.token, {
                    id: id
                })
                .then(response => {
                    this.request.municipio = response.data
                })
            },
            seleccionData(){
                this.getMunicipioFilter(this.form.departamento_id)
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
                
                axios.post(router[1].post.getSedeById + this.token,{
                    id: id
                })
                .then(response => {
                    this.form.id = id
                    this.form.departamento_id = response.data[0].departament
                    this.form.municipio_id = response.data[0].municipality
                    this.form.name = response.data[0].name
                    this.form.phone = response.data[0].number
                    this.form.address = response.data[0].address
                    this.show()
                })
            },
            updateSubmit(){
                axios.post(router[1].post.updateSedeById + this.token,{
                    id: this.form.id,
                    name: this.form.name,
                    phone: this.form.phone,
                    address: this.form.address,
                    departamento_id: this.form.departamento_id,
                    municipio_id:   this.form.municipio_id
                })
                .then(response => {
                    if (response.status == 200){
                        this.clearForm();
                        this.getSede()
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
            deleted(){}

            
        }   
    }
</script>

