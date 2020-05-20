<template>
  <div id="app">

    <Youtube/>

  </div>
</template>

<script>
  import Youtube from './Youtube'
export default {
  el: '#app',

  components: {
    Youtube
  },

  data: () => ({
    url: null,
    types: null
  }),

  metaInfo () {
    const { appName } = window.config

    return {
      title: appName,
      titleTemplate: `%s · ${appName}`
    }
  },

  mounted () {
    this.$loading = this.$refs.loading
  },

  methods: {
  }
}
</script>
