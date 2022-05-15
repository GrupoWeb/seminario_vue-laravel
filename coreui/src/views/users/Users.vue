<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
      <CCard>
        <CCardHeader>
            Usuarios
        </CCardHeader>
        <CCardBody>
          <CAlert
            :show.sync="dismissCountDown"
            color="primary"
            fade
          >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>
          <CButton color="primary" @click="addUser()"  class="mb-3">Crear Usuario</CButton>
          <CModal
            :title="propsModal.title"
            :show.sync="visibleStaticBackdropDemo"
            :color="propsModal.color"
            :size="propsModal.size"
            :closeOnBackdrop="propsModal.BackDrop"
          >
            <CForm novalidate class="row g-3 needs-validation" ref="form">
              <CSelect
                :value.sync ="form.role"
                label="Roles de Usuario"
                :options="propsModal.options"
                placeholder="Seleccione ..."
                valid-feedback="Correcto.."
                invalid-feedback="Seleccione"
                required
                was-validated
              />
              <CInput
                v-model="form.name"
                label="Nombre"
                type="input"
                valid-feedback="Correcto.."
                invalid-feedback="Ingrese nombre de usuario"
                required
                was-validated
              />
              <CInput
                v-model="form.email"
                label="Correo Electrónico"
                type="email"
                valid-feedback="Correcto.."
                invalid-feedback="Ingrese un correo valido"
                required
                was-validated
              />
              <CInput
                v-model="form.password"
                label="Contraseña"
                type="password"
                autocomplete="current-password"
                valid-feedback="Correcto.."
                invalid-feedback="Ingrese una contraseña"
                :is-valid="validator"
                required
                was-validated
              />
            </CForm>
            <template #footer>
              <CButton @click="close" color="danger">Cancelar</CButton>
              <CButton @click="submit" color="success">Guardar</CButton>
            </template>
          </CModal>

          <CDataTable
            hover
            striped
            :items="items"
            :fields="fields"
            :items-per-page="5"
            pagination
          >
          <template #estado="{item}">
            <td>
              <CBadge :color="getBadge(item.estado)">{{ item.estado }}</CBadge>
            </td>
          </template>
          <template #ver="{item}">
            <td>
              <CButton color="primary" @click="showUser( item.id )">Ver</CButton>
            </td>
          </template>
          <template #editar="{item}">
            <td>
              <CButton color="primary" @click="editUser( item.id )">Editar</CButton>
            </td>
          </template>
          <template #eliminar="{item}">
            <td>
              <CButton v-if="you!=item.id" color="danger" @click="deleteUser( item.id )">Eliminar</CButton>
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
import { router } from '../../utils/router'

export default {
  name: 'Users',
  data: () => {
    return {
      items: [],
      fields: ['id', 'nombre', 'email', 'estado', 'ver', 'editar', 'eliminar'],
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      you: null,
      message: '',
      showMessage: false,
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false,
      visibleStaticBackdropDemo: false,
      propsModal: {
        title: "Agregar Nuevo Usuario",
        color: "dark",
        size: "lg",
        BackDrop: false,
        options: []
      },
      form: {
        role:"",
        name: "",
        email: "",
        password:""
      }
    }
  },
  paginationProps: {
    align: 'center',
    doubleArrows: false,
    previousButtonHtml: 'prev',
    nextButtonHtml: 'next'
  },
  methods: {
    validator (val) {
      return val ? val.length >= 8 : false
    },
    getBadge (estado) {
      return estado === 'Activo' ? 'success'
        : estado === 'Inactivo' ? 'secondary'
          : estado === 'Pending' ? 'warning'
            : estado === 'Banned' ? 'danger' : 'primary'
    },
    addUser(){
      this.visibleStaticBackdropDemo = !this.visibleStaticBackdropDemo
      // this.$router.push({path: `menuelement/${id.toString()}/menuelement`});
    },
    close(){
      this.visibleStaticBackdropDemo = !this.visibleStaticBackdropDemo
    },
    submit(){
      // createUser
      if(this.$refs.form.checkValidity()){
        axios.post(router[0].createUser + '?token=' + localStorage.getItem("api_token"),{
          name: this.form.name,
          email: this.form.email,
          password: this.form.password,
          role_id: this.form.role
        }).then(response => {
          if(response.status == 200){
            this.$swal({
              icon: "success",
              title: "Usuario Creado",
              showConfirmButton: false,
              timer: 2500,
            });

            this.clearForm()
            this.close()
            this.getUsers()
          }
        }).catch(error => {
          console.log(error)
        })
      }
    },
    clearForm(){
      this.form.role = ""
      this.form.name = ""
      this.form.email = "",
      this.form.password = ""
    },
    userLink (id) {
      return `users/${id.toString()}`
    },
    editLink (id) {
      return `users/${id.toString()}/edit`
    },
    showUser ( id ) {
      const userLink = this.userLink( id );
      this.$router.push({path: userLink});
    },
    editUser ( id ) {
      const editLink = this.editLink( id );
      this.$router.push({path: editLink});
    },
    deleteUser ( id ) {
      let self = this;
      let userId = id;
      axios.post(  this.$apiAdress + '/api/users/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
      .then(function (response) {
          self.message = 'Usuario Eliminado!';
          self.showAlert();
          self.getUsers();
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    getUsers (){
      let self = this;
      axios.get(  this.$apiAdress + '/api/users?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.items = response.data.users;
        self.you = response.data.you;
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    },
    getRoles(){
      let self = this;
      axios.get(  this.$apiAdress + '/api/getRoles?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.propsModal.options = response.data
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });

    }
  },
  mounted: function(){
    this.getUsers();
    this.getRoles();
  }
}
</script>
