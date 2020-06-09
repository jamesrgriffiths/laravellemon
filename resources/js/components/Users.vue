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
                USERS: <span class="text-info">{{total}}</span><br/>
              </div>
              <div class="col col-1 p-0">&nbsp;</div>
            </div>
          </h2>
        </div>

        <!-- Body -->
        <div class="card-body">

          <!-- Pagination -->
          <div class="row">
            <div class="col col-12 flex-center text-center">
              <span v-for="page in pages">
                <span v-if="page !== ''">
                  <button type="button" v-on:click="changePage(page)" class="btn btn-sm p-1 m-1" v-bind:class="page == current_page ? 'btn-dark' : 'btn-light'">{{page}}</button>
                </span>
                <span v-else>&nbsp;&nbsp;&nbsp;</span>
              </span>
            </div>
          </div>
          <!-- END Pagination -->

          <!-- Users -->
          <div v-for="(user,i) in users">
            <div class="row p-2" v-bind:class="i%2 ? 'bg-light' : ''">
              <div class="col col-12">

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
  export default {

    data() {
      return {
        timer: '',
        loading: false,

        total: '',
        users: [],

        current_page: 1,
        pages: [],
      }
    },

    watch: {

    },

    created() {
      this.fetchUsers();
    },

    methods: {

      changePage(page) {
        this.current_page = page;
        this.fetchUserTypes();
      },

      deleteUsers(id) {
        axios.delete("/users/"+id)
          .then(this.fetchUsers());
      },

      fetchUsers() {
        if(!this.loading) {
          this.loading = true;
          axios.get("/users",{params: {vue: true, current_page: this.current_page}}).then((response)=>{
            this.users = response.data.users;

            this.current_page = response.data.current_page;
            this.pages = response.data.pages;

            this.loading = false;
          });
        }
      }

    }

  };
</script>
