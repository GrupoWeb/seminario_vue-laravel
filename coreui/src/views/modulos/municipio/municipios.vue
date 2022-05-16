<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
      <CCard>
        <CCardHeader>
            Municipios
        </CCardHeader>
        <CCardBody>

            <!-- modales -->

                <CModal
                :title="propsModal.title"
                :show.sync="idModal"
                :color="propsModal.color"
                :size="propsModal.size"
                :closeOnBackdrop="propsModal.BackDrop"
                >
                <CForm novalidate class="row g-3 needs-validation" ref="form_edit">
                    <CInput
                    v-model="form.name"
                    label="Nombre"
                    type="input"
                    valid-feedback="Correcto.."
                    invalid-feedback="Ingrese el dato"
                    required
                    was-validated
                    />
                    <CInput
                    v-model="form.departamento"
                    label="Departamento"
                    type="input"
                    valid-feedback="Correcto.."
                    invalid-feedback="Ingrese el dato"
                    required
                    disabled
                    was-validated
                    />
                </CForm>
                <template #footer>
                    <CButton @click="close" color="danger">Cancelar</CButton>
                    <CButton @click="submit" color="success">Guardar</CButton>
                </template>
                </CModal>

                <b-modal title="Eliminación de Departamentos" :id="modalDelte" :header-bg-variant="headerBgVariant" :header-text-variant="headerTextVariant" modal-ok="Aceptar" @ok="handleOk(form.id)">
                    <template >
                        <p>¿Desea eliminar el Municipio? </p>
                    </template>
                    <template #modal-footer="{ ok }">
                        <b-button size="sm" variant="success" @click="ok()">
                                Aceptar
                        </b-button>
                    </template>
                </b-modal>

            <!-- *********************** -->
            <CDataTable
                hover
                striped
                :items="items"
                :fields="fields"
                :items-per-page="10"
                pagination
                >
                
                    <template #editar="{item}">
                        <td>
                        <CButton color="primary" @click="editDepartamento( item.id )">Editar</CButton>
                        </td>
                    </template>
                    <template #eliminar="{item}">
                        <td>
                        <CButton color="danger" @click="deleteDepartamento( item.id )">Eliminar</CButton>
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
import { router } from '../../../utils/router'

    export default {
     data() {
         return {
            items: [],
            fields: ['id', 'nombre', 'departamento' ,'editar','eliminar'],
            idModal: false,
            modalDelte: 'deleteModal',
            headerTextVariant: "light",
            headerBgVariant: 'dark',
            propsModal: {
                    title: "Departamento",
                    color: "dark",
                    size: "sm",
                    BackDrop: false,
            },
            form: {
                id:"",
                name: "",
                departamento: ""
            },
         }
     },
     methods: {
         getDepartamenById(id) {
             axios.post(router[1].post.municipioById + '?token=' + localStorage.getItem("api_token"),
             {
                 id: id
             })
             .then(response => {
                 this.form.id = response.data[0].id
                 this.form.name = response.data[0].nombre
                 this.form.departamento = response.data[0].departamento
             })
             .catch(error => {
                 console.log(error)
             })
         },
         getDepartamento(){
             axios.get(router[1].get.municipios + '?token=' + localStorage.getItem("api_token"))
             .then(response => {
                 this.items = response.data
             })
         },
         editDepartamento(id){
             this.getDepartamenById(id)
             this.idModal = !this.idModal
         },
         deleteDepartamento(id){
             this.form.id = id
             this.$bvModal.show("deleteModal");
         },
        close() {
            this.close()
            this.idModal = !this.idModal
        },
         handleOk(id){
            axios.post(router[1].post.deleteMunicipio + '?token=' + localStorage.getItem("api_token"), {
                id: id
            }).then(response => {
                if(response.data == 1) {
                    this.$swal({
                    icon: "success",
                    title: "Departamento Eliminado",
                    showConfirmButton: false,
                    timer: 2500,
                    });

                    this.clearForm()
                    this.close()
                    this.getDepartamento()
                }
            })
        },
        submit() {
            if(this.$refs.form_edit.checkValidity()){
                axios.post(router[1].post.updateMunicipioById + '?token=' + localStorage.getItem("api_token"),{
                id: this.form.id,
                nombre: this.form.name
                }).then(response => {
                    if(response.status == 200){
                        this.$swal({
                        icon: "success",
                        title: "Departamento Modificado",
                        showConfirmButton: false,
                        timer: 2500,
                        });

                        this.clearForm()
                        this.close()
                        this.getDepartamento()
                    }
                    }).catch(error => {
                    console.log(error)
                    })
            }
        },
        clearForm(){
            this.form.id = "",
            this.form.name = ""
        }
     },
     mounted: function(){
         this.getDepartamento();
     }
    }
</script>

