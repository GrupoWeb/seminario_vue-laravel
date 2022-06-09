<template>
  <div>
    <CRow>
      <CCol md="12">
        <b-carousel
          id="carousel-1"
          v-model="slide"
          :interval="4000"
          controls
          indicators
          background="#ababab"
          img-width="1024"
          img-height="200"
          style="text-shadow: 1px 1px 2px #333;"
          @sliding-start="onSlideStart"
          @sliding-end="onSlideEnd"
        >
          <!-- Text slides with image -->
          <b-carousel-slide
            caption="Servicio de transporte"
            text="Más de 50 unidades para todo tipo de cargamento nacional o internacional."
            :img-src="img.b1"
            img-height="200"
          ></b-carousel-slide>
          <b-carousel-slide
            caption="Servicio de construcción"
            text="Somos una empresa innovadora, especializada en el suministro de personal calificado para la construcción"
            :img-src="img.b2"
            img-height="200"
          ></b-carousel-slide>
          <b-carousel-slide
            caption="Maquinaría de Construcción"
            text="Venta y Alquiler de todo tipo de maquinaria"
            :img-src="img.b3"
            img-height="200"
          ></b-carousel-slide>
          <b-carousel-slide
            caption="Planta Minera"
            text="Extracción de materia prima para la contrucción"
            :img-src="img.b4"
            img-height="200"
          ></b-carousel-slide>
          <b-carousel-slide
            caption="Materiales de Construcción"
            text="Amplio stock de materiales de la construcción."
            :img-src="img.b5"
            img-height="200"
          ></b-carousel-slide>
        </b-carousel>
      </CCol>
    </CRow>
    <b-row>
      <b-col fluid class="mt-5" >
        <b-card-group deck>
          <b-card
              title="Perforaciones en Concreto"
              :img-src="card.f1"
              img-alt="Image"
              img-top
              tag="article"
              style="max-width: 35rem;"
              class="mb-2" 
            >
              <b-card-text>
                Realizamos perforaciones circulares para paso de instalaciones en muros y losas de concreto de hasta 1.2 metros de profundidad. Nuestros procedimiento son limpios y seguros, garantizando un excelente trabajo.
              </b-card-text>
              <b-button href="#" variant="primary" v-b-modal.contacto>Cotizar</b-button>
          </b-card>
          <b-card
              title="Demoliciones"
              :img-src="card.f2"
              img-alt="Image"
              img-top
              tag="article"
              style="max-width: 35rem;"
              class="mb-2" 
            >
              <b-card-text>
                Demoliciones profesionales de todo tipo de estructuras incluyendo concreto y acero estructural. Nos enfocamos en la seguridad del personal y las zonas aledañas, desmontamos aquellos materiales que son re-utilizables, extraemos el material generado y limpiamos el área.
              </b-card-text>
              <b-button href="#" variant="primary" v-b-modal.contacto>Cotizar</b-button>
          </b-card>
          <b-card
              title="Construcción"
              :img-src="card.f3"
              img-alt="Image"
              img-top
              tag="article"
              style="max-width: 35rem;"
              class="mb-2" 
            >
              <b-card-text>
                Somos contratistas de obra civil y acabados. Nos encargamos de ejecutar los planos y especificaciones de su diseñador ajustándonos al requerimiento del cliente y a las condiciones específicas de cada obra, garantizando así un proyecto bien ejecutado.
              </b-card-text>
              <b-button href="#" variant="primary" v-b-modal.contacto>Cotizar</b-button>
          </b-card>
          <b-card
              title="Transporte Terrestre"
              :img-src="card.f4"
              img-alt="Image"
              img-top
              tag="article"
              style="max-width: 35rem;"
              class="mb-2" 
            >
              <b-card-text>
                Contamos con una amplia experiencia en el manejo de sus importaciones y exportaciones en la modalidad de carga completa (FTL) y consolidada (LTL).
              </b-card-text>
              <b-button href="#" variant="primary" v-b-modal.contacto>Cotizar</b-button>
          </b-card>
        </b-card-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col fluid="sm" class="mt-5">
        <b-card title="Quienes Somos" class="mb-2">
          <b-card-text>
            
              La empresa TransPort fue fundada en 1979 por Marcelino Moldes, la compañía dispone de 40,000 m2 de instalaciones logísticas estratégicamente ubicadas en la Ciudad de Guatemala. Contando con una amplia red de giros de negocio que sustenta las necesidades de los clientes en las principales ciudades y proyectos del mundo

          </b-card-text>
        </b-card>
      </b-col>
      <b-col fluid="sm" class="mt-5">
        <b-card title="Misión" class="mb-2">
          <b-card-text>
            
              Proporcionar Soluciones profesionales y personalizadas en el ámbito del Transporte, Construcción, Extracción y Elaboración de materiales para la construcción, envío a diferentes partes del territorio nacional y extranjero. Posicionándonos como la primera empresa nacional en cubrir las mayores necesidades de nuestros clientes

          </b-card-text>
        </b-card>
      </b-col>
      <b-col fluid="sm" class="mt-5">
        <b-card title="Visión" class="mb-2">
          <b-card-text>
            
              Afianzarnos como uno de los principales socios logísticos en la industria de la construcción y extracción de materiales de materia prima de la región. Aportando soluciones profesionales y personalizadas en el ámbito del transporte y la logística, con clara orientación al cliente, basada en la formación, motivación, compromiso e implementación de nuestro equipo humano

          </b-card-text>
        </b-card>
      </b-col>
    </b-row>

    <b-modal id="contacto" ref="modal" title="Formulario de Contacto" @ok="handleOk" @show="resetModal"> 
      <form ref="form" @submit.stop.prevent="handleSubmit" >
        <b-form-group
          label="Nombre"
          label-for="nombre"
          invalid-feedback="Nombre Requerido"
          :state="form.states.nombre"
        >
          <b-form-input
            id="nombre"
            v-model="form.nombre"
            :state="form.states.nombre"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          label="Correo"
          label-for="correo"
          invalid-feedback="Email Requerido"
          :state="form.states.correo"
        >
          <b-form-input
            id="correo"
            v-model="form.correo"
            :state="form.states.correo"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          label="Mensaje"
          label-for="mensaje"
          invalid-feedback="Mensaje Requerido"
          :state="form.states.mensaje"
        >
          <b-form-textarea
            id="mensaje"
            v-model="form.mensaje"
            :state="form.states.mensaje"
            required
            rows="3"
            max-rows="6"
          ></b-form-textarea>
        </b-form-group>
      </form>
    </b-modal>
  </div>
</template>

<script>
import axios from 'axios'
import { router } from '../utils/router'

export default {
  name: 'Dashboard',
  components: {
  },
  data () {
    return {
      slide: 0,
      sliding: null,
      img: {
        b1: 'img/p1.jpg',
        b2: 'img/p2.jpg',
        b3: 'img/p3.jpg',
        b4: 'img/p4.jpg',
        b5: 'img/p5.jpg',
      },
      card: {
        f1: 'img/card/perforacion.jpg',
        f2: 'img/card/demolicion.jpg',
        f3: 'img/card/construccion.jpg',
        f4: 'img/card/trans.jpg',
      },
      form: {
        nombre: null,
        correo: null,
        mensaje: null,
        states: {
          nombre: null,
          correo: null,
          mensaje: null
        }
      }
    }
  },
  methods: {
    checkFormValidity() {
        const valid = this.$refs.form.checkValidity()
        this.form.states.nombre = valid
        this.form.states.correo = valid
        this.form.states.mensaje = valid
        return valid
      },
    resetModal() {
      this.form.nombre =  null
      this.form.correo =  null
      this.form.mensaje =  null
      this.form.states.nombre =  null
      this.form.states.correo =  null
      this.form.states.mensaje =  null
    },
    handleOk(bvModalEvent){
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit() {
      if (!this.checkFormValidity()) {
          return
        }

      axios.post(router[1].post.contactanos,{
        email: this.form.correo,
        nombre: this.form.nombre
      })
      .then(response => {
        console.log(response)

        if(response.status == 200){
          this.$nextTick(() => {
              this.$bvModal.hide('contacto')
          })
        }
      })



    },
    onSlideStart(slide) {
        this.sliding = true
      },
      onSlideEnd(slide) {
        this.sliding = false
      }
  }
}
</script>
