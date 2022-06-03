<template>
    <div
        v-show="isVisible"
        class="loader-overlay"
    >
        <div class="spinner" />
        <span
            class="text"
            v-text="message"
        />
    </div>
</template>

<script>
export default {
    name: 'Loader',
    props: {
        isVisible: { type: Boolean, required: false, default: false },
        message: { type: String, required: false, default: "Cargando..." }
    }
}
</script>

<style lang="css">
  .loader-overlay {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 999;
    cursor: pointer;
  }
  span.text {
    display: inline-block;
    position: relative;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    color: #fff;
    margin-top: 24px;
  }
  .spinner {
    border: 4px solid rgba(0, 0, 0, 0.1);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border-left-color: #fff;
    position: absolute;
    left: calc(51% - 40px);
    top: calc(51% - 40px);
    animation: spin 1s ease infinite;
  }
  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
</style>