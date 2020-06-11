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
          <user-edit v-for="(user,index) in users" v-bind:key="index" :index="index" :user="user" :current_user="current_user" :user_types="user_types"></user-edit>

        </div>

      </div>
    </div>
  </div>
</template>

<script>

  import ManagementHeading from './sub_components/ManagementHeading';
  import Pagination from './sub_components/Pagination';
  import UserEdit from './sub_components/UserEdit';

  export default {
    components: { 'management-heading': ManagementHeading, 'pagination': Pagination, 'user-edit': UserEdit },
    data() {
      return {
        title: 'Users',
        initialized: false,
        loading: false,
        users: [],
        current_user: '',
        user_types: [],
        total: 0,
        page: 1,
        pages: [],
      }
    },
    created() {
      this.fetchData();
    },
    methods: {

      fetchData() {
        this.loading = true;
        axios.get("/users",{params: {vue: true, page: this.page}}).then((response)=>{
          this.users = response.data.users.data;
          this.current_user = response.data.current_user;
          this.user_types = response.data.user_types;
          this.total = parseInt(response.data.users.total);
          this.page = parseInt(response.data.page);
          this.pages = response.data.pages;
          this.$nextTick(() => {
            this.loading = false;
            this.initialized = true;
          });
        });
      },

    }

  };

</script>
