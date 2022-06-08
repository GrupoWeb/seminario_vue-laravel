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
          img-height="20"
          style="text-shadow: 1px 1px 2px #333;"
          @sliding-start="onSlideStart"
          @sliding-end="onSlideEnd"
        >
          <!-- Text slides with image -->
          <b-carousel-slide
            caption="First slide"
            text="Nulla vitae elit libero, a pharetra augue mollis interdum."
            :img-src="img.b1"
            img-height="500"
          ></b-carousel-slide>
          <b-carousel-slide
            caption="First slide"
            text="Nulla vitae elit libero, a pharetra augue mollis interdum."
            :img-src="img.b1"
            img-height="500"
          ></b-carousel-slide>
          <b-carousel-slide
            caption="First slide"
            text="Nulla vitae elit libero, a pharetra augue mollis interdum."
            :img-src="img.b1"
            img-height="500"
          ></b-carousel-slide>

          <!-- Slides with custom text -->
          <!-- <b-carousel-slide img-src="https://picsum.photos/1024/220/?image=54">
            <h1>Hello world!</h1>
          </b-carousel-slide> -->

          <!-- Slides with image only -->
          <!-- <b-carousel-slide img-src="https://picsum.photos/1024/220/?image=58"></b-carousel-slide> -->

          <!-- Slides with img slot -->
          <!-- Note the classes .d-block and .img-fluid to prevent browser default image alignment -->
          <!-- <b-carousel-slide>
            <template #img>
              <img
                class="d-block img-fluid w-100"
                width="1024"
                height="220"
                src="https://picsum.photos/1024/220/?image=55"
                alt="image slot"
              >
            </template>
          </b-carousel-slide> -->

          <!-- Slide with blank fluid image to maintain slide aspect ratio -->
          <!-- <b-carousel-slide caption="Blank Image" img-blank img-alt="Blank image">
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eros felis, tincidunt
              a tincidunt eget, convallis vel est. Ut pellentesque ut lacus vel interdum.
            </p>
          </b-carousel-slide> -->
        </b-carousel>
      </CCol>
    </CRow>
  </div>
</template>

<script>
export default {
  name: 'Dashboard',
  components: {
  },
  data () {
    return {
      slide: 0,
      sliding: null,
      img: {
        b1: 'img/d1.jpg'
      }
    }
  },
  methods: {
    onSlideStart(slide) {
        this.sliding = true
      },
      onSlideEnd(slide) {
        this.sliding = false
      }
  }
}
</script>
