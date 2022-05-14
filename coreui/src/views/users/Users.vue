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

export default {
  name: 'Users',
  data: () => {
    return {
      items: [],
      fields: ['id', 'nombre', 'registro', 'roles', 'estado', 'ver', 'editar', 'eliminar'],
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      you: null,
      message: '',
      showMessage: false,
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false
    }
  },
  paginationProps: {
    align: 'center',
    doubleArrows: false,
    previousButtonHtml: 'prev',
    nextButtonHtml: 'next'
  },
  methods: {
    getBadge (estado) {
      return estado === 'Active' ? 'success'
        : estado === 'Inactive' ? 'secondary'
          : estado === 'Pending' ? 'warning'
            : estado === 'Banned' ? 'danger' : 'primary'
    },
    addUser(){
      this.$router.push({path: `menuelement/${id.toString()}/menuelement`});
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
          self.message = 'Successfully deleted user.';
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
    }
  },
  mounted: function(){
    this.getUsers();
  }
}
</script>
