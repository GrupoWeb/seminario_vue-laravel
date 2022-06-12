<template>
  <CHeader fixed with-subheader light>
    <CToggler
      in-header
      class="ml-3 d-lg-none"
      @click="$store.commit('toggleSidebarMobile')"
    />
    <CToggler
      in-header
      class="ml-3 d-md-down-none"
      @click="$store.commit('toggleSidebarDesktop')"
    />
    <CHeaderBrand class="mx-auto d-lg-none" to="/">
      <CImg :src=logo   class="mb-2"  height="58"  with="300" />
      <!-- <CIcon name="logo" height="48" alt="Logo"/> -->
    </CHeaderBrand>

    <CMenu/>

    <CHeaderNav class="mr-4" v-if="flag">
      <TheHeaderDropdownAccnt/>
    </CHeaderNav>
    <CSubheader class="px-3">
      <CBreadcrumbRouter class="border-0 mb-0"/>
    </CSubheader>
  </CHeader>
</template>

<script>
import CMenu from './Menu'
import TheHeaderDropdownAccnt from './TheHeaderDropdownAccnt'

export default {
  name: 'TheHeader',
  components: {
    TheHeaderDropdownAccnt,
    CMenu
  },
  data() {
    return {
      logo: '/transport/logo_uno.jpg',
      account: localStorage.getItem('roles'),
      flag: false,
    }
  },
  mounted() {
    if(this.account.length > 0){
      this.flag = true
    }
    
  }
}
</script>