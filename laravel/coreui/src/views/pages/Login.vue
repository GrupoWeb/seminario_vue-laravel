<template>
  <CContainer class="d-flex content-center min-vh-100">
    <CRow>
      <CCol>
        <CCardGroup>
          <CCard class="p-4">
            <CCardBody>
              <CForm @submit.prevent="login" method="POST">
                <h1>Sistema</h1>
                <p class="text-muted">Iniciar sesión en su cuenta</p>
                <CInput
                  v-model="email"
                  prependHtml="<i class='cui-user'></i>"
                  placeholder="Username"
                  autocomplete="username email"
                >
                  <template #prepend-content><CIcon name="cil-user"/></template>
                </CInput>
                <CInput
                  v-model="password"
                  prependHtml="<i class='cui-lock-locked'></i>"
                  placeholder="Password"
                  type="password"
                  autocomplete="curent-password"
                >
                  <template #prepend-content><CIcon name="cil-lock-locked"/></template>
                </CInput>
                <CRow>
                  <!-- <CCol col="8"> -->
                    <CButton type="submit" color="primary" class="px-4">Entrar</CButton>
                  <!-- </CCol> -->
                  <!-- <CCol col="6" class="text-right">
                    <CButton color="link" class="px-0">Forgot password?</CButton>
                  </CCol> -->
                </CRow>
              </CForm>
            </CCardBody>
              <b-alert :show="showMessage" dismissible variant="danger" @dismissed="showMessage=0" @dismiss-count-down="countDownChanged">{{ message }}</b-alert>
          </CCard>
          <!-- <CCard
            color="primary"
            text-color="white"
            class="text-center py-5 d-md-down-none"
            body-wrapper
          >
            <h2>Sign up</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            <CButton
              color="primary"
              class="active mt-3"
              @click="goRegister()"
            >
              Register Now!
            </CButton>
          </CCard> -->
        </CCardGroup>
      </CCol>
    </CRow>
  </CContainer>
</template>

<script>

import axios from "axios";

    export default {
      name: 'Login',
      data() {
        return {
          email: '',
          password: '',
          showMessage: 0,
          message: '',
          dismissSecs: 5,
          
        }
      },
      methods: {
        countDownChanged(showMessage) {
          this.showMessage = showMessage
        },
        showAlert() {
          this.showMessage = this.dismissSecs
        },
        goRegister(){
          this.$router.push({ path: 'register' });
        },
        login() {
          let self = this;
          axios.post(  this.$apiAdress + '/api/login', {
            email: self.email,
            password: self.password,
          }).then(function (response) {
            
            self.email = '';
            self.password = '';
            localStorage.setItem("api_token", response.data.access_token);
            localStorage.setItem('roles', response.data.roles);
            self.$router.push({ path: 'dashboard' });
          
          })
          .catch(function (error) {
            self.message = 'Usuario o Contraseña Incorrecta!';
            self.showAlert()
            // self.showMessage = true;
            
          });
  
        }
      }
    }

</script>
