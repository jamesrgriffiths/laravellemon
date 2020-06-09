<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">

        <!-- Header -->
        <div class="card-header pt-0">
          <h2 class="m-2">
            <div class="row">
              <div class="col col-1 p-0 mt-0">
                <div v-if="!loading">
                  <svg class="bi bi-circle-fill text-success" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="8" cy="8" r="8"/>
                  </svg>
                </div>
                <div v-if="loading" class="spinner-border text-warning" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </div>
              <div class="col col-10 p-0 pt-1 text-center">
                USER MANAGEMENT
                <br/> <span v-if="initialized" class="text-info">Total Users: {{total}}</span><br/>
              </div>
              <div class="col col-1 p-0">&nbsp;</div>
            </div>
          </h2>
        </div>

        <!-- Body -->
        <div v-if="initialized" class="card-body">

          <!-- Pagination -->
          <pagination :page="page" :pages="pages"></pagination>

          <!-- Users -->
          <div v-for="(user,i) in users">
            <div class="row p-2" v-bind:class="i%2 ? 'bg-light' : ''">
              <div class="col col-12">
                {{user.name}}
              </div>
              <div class="col col-12 col-md-3">

              </div>
              <div class="col col-12 col-md-3">

              </div>
              <div class="col col-12 col-md-2">

              </div>
              <div class="col col-12 col-md-1 my-auto">

              </div>
              <div class="col col-12 col-md-3 my-auto">
                <button type="button" class="btn btn-sm btn-outline-danger m-1" v-on:click="deleteLog(log.id,'delete')">Delete</button>
              </div>
            </div>
          </div>
          <!-- END Users -->

        </div>

      </div>
    </div>
  </div>
</template>

<script>

  import pagination from './sub_components/pagination';

  export default {

    components: { pagination },

    data() {
      return {
        timer: '',
        loading: false,
        initialized: false,

        users: [],

        total: 0,
        page: 1,
        pages: [],
      }
    },

    created() {
      this.fetchData();
    },

    methods: {

      deleteUsers(id) {
        axios.delete("/users/"+id)
          .then(this.fetchData());
      },

      fetchData() {
        this.loading = true;
        axios.get("/users",{params: {vue: true, page: this.page}}).then((response)=>{
          this.users = response.data.users.data;

          this.total = response.data.users.total;
          this.page = parseInt(response.data.page);
          this.pages = response.data.pages;

          this.loading = false;
          this.initialized = true;
        });
      }

    }

  };
  
</script>
