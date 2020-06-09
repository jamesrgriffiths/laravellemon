<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">

        <!-- Header -->
        <management-heading :title="title" :initialized="initialized" :loading="loading" :total="total"></management-heading>

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

  import ManagementHeading from './sub_components/ManagementHeading';
  import Pagination from './sub_components/Pagination';

  export default {

    components: { 'management-heading': ManagementHeading, 'pagination': Pagination },

    data() {
      return {
        title: 'Users',
        initialized: false,
        loading: false,

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

          this.total = parseInt(response.data.users.total);
          this.page = parseInt(response.data.page);
          this.pages = response.data.pages;

          this.loading = false;
          this.initialized = true;
        });
      }

    }

  };

</script>
