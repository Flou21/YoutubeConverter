<template>
  <div>
    <!-- title -->
    <section class="section">
      <div class="container">
        <h1 class="title">Hey this is a little youtube converter</h1>
        <h2
          class="subtitle"
        >You can download the youtube video as video, audio or just the thumbnail</h2>
      </div>
    </section>

    <!-- input stuff -->
    <section class="section">
      <div class="container" v-if="types">
        <b-field label="Url" horizontal>
          <b-input v-model="url"></b-input>
        </b-field>
        <b-field position="is-centered">
          <b-field label="Type" horizontal>
            <b-select v-model="type">
              <option v-for="option in types" :value="option" :key="option">{{ option }}</option>
            </b-select>
          </b-field>
          <p class="control">
            <b-field>
              <b-button class="button is-primary" @click="convert">Convert</b-button>
            </b-field>
          </p>
        </b-field>

        <p v-if="downloadPending">Download in progress, please wait</p>
        <p class="control">
          <b-field>
            <b-button class="button is-primary" @click="download"
                      v-if="downloadInfos"
                      :active="downloadPending"
            >Download
            </b-button>
          </b-field>
        </p>
      </div>
    </section>
    <!-- final video -->
    <section v-if="video">
      <video controls>
        <source :src="video.src" type="video/mp4">
        Your browser does not support HTML5 videos
      </video>
    </section>
  </div>
</template>

<script>
  export default {
    layout: 'basic',

    metaInfo () {
      return { title: this.$t('youtube') }
    },

    data: () => ({
      url: null,
      types: null,
      type: null,
      downloadInfos: null,
      downloadPending: null,
      video: null
    }),

    mounted () {
      this.$loading = this.$refs.loading
      this.onCreate()
    },

    methods: {
      onCreate () {
        this.loadTypes()
        this.url = 'https://www.youtube.com/watch?v=tBeHYH1NYtc'
      },
      async loadTypes () {
        let response = await fetch('/api/youtube/types')
        this.types = await response.json()
        this.type = this.types[0]
      },
      async convert () {
        this.downloadPending = true
        let response = await fetch('/api/youtube/convert', {
          body: JSON.stringify({
            url: this.url,
            type: this.type
          }),
          method: 'post'
        })
        this.downloadInfos = await response.json()
        this.downloadPending = false
      },
      async download () {
        console.log(this.downloadInfos)
        this.video = {}
        let response = await fetch('/api/youtube/download', {
          body: JSON.stringify({
            title: this.downloadInfos.title,
            uid: this.downloadInfos.uid
          }),
          method: 'post'
        })
        let reader = response.body.getReader()
        return new ReadableStream({
          start (controller) {
            return pump()

            function pump () {
              return reader.read().then(({ done, value }) => {
                // When no more data needs to be consumed, close the stream
                if (done) {
                  controller.close()
                  return
                }
                // Enqueue the next data chunk into our target stream
                controller.enqueue(value)
                return pump()
              });
            }
          }
        })
          .then(stream => new Response(stream))
    .then(response => response.blob())
    .then(blob => URL.createObjectURL(blob))
    .then(url => console.log(image.src = url))
    .catch(err => console.error(err))

  }
  }
  }
</script>

<style scoped>
</style>
