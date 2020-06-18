<template>
  <div>

    <!-- Content -->
    <div class="container-fluid mt-3 ">
      <div class="card">

        <!-- Header -->
        <heading
          :title="title"
          :initialized="initialized"
          :loading="loading">
        </heading>

        <!-- Body -->
        <div class="card-body" v-if="initialized">

          Home

        </div>

      </div>
    </div>

  </div>
</template>

<script>

  import Heading from './child_components/Heading';

  export default {

    components: { Heading },

    data() {
      return {
        title: 'Home',
        initialized: false,
        loading: false
      }
    },
    created() {
      this.fetchData();
    },
    methods: {

      // Get the updated home data.
      async fetchData() {
        if(!this.loading) {
          this.loading = true;
          await axios.get("/home",{params: {vue: true}}).then((response)=>{
            this.loading = false;
            this.initialized = true;
          });
        }
      }

    }

  };

</script>
