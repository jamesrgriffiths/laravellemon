<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">

        <!-- Header -->
        <heading
          :title="title"
          :initialized="initialized"
          :loading="loading"
          :total="total"
          :filters="filters"
          @change="changeFilter">
        </heading>

        <!-- Body -->
        <div v-if="initialized" class="card-body">

          <!-- Pagination -->
          <pagination
            :page="page"
            :pages="pages"
            @change="changePage">
          </pagination>

          <!-- Users -->
          <div v-for="(user,index) in users" :key="user.id">

            <!-- User Info -->
            <user
              :index="index"
              :user="user"
              :loading="loading"
              :current_user="current_user"
              :edit_modal_id="edit_modal_id+user.id"
              :delete_modal_id="delete_modal_id+user.id">
            </user>

            <!-- User Edit Modal -->
            <modal-edit
              :modal_id="edit_modal_id+user.id"
              :index="index"
              :label="'Edit '+user.name+' (#'+user.id+')'"
              :object="user"
              :fields="[
                {'name': 'name', 'display': 'Name'},
                {'name': 'email', 'display': 'email_address'},
                {'name': 'user_type_id', 'display': 'User Type', 'type': 'select', 'options': user_types}]"
              :toggles="[
                {'name': 'is_admin', 'display': 'Admin', 'disabled': user.id == current_user.id},
                {'name': 'is_active', 'display': 'Active'},
                {'name': 'email_verified_at', 'display': 'Verified'}]"
              @save="updateUser">
            </modal-edit>

            <!-- Delete Modal -->
            <modal-delete
              :modal_id="delete_modal_id+user.id"
              :text="'Are you sure you want to delete user '+user.name+'(#'+user.id+')?'"
              @delete="deleteUser(user.id,index)">
            </modal-delete>

          </div>

        </div>

      </div>
    </div>
  </div>
</template>

<script>

  import Heading from './child_components/Heading';
  import ModalDelete from './child_components/ModalDelete';
  import ModalEdit from './child_components/ModalEdit';
  import Pagination from './child_components/Pagination';
  import User from './child_components/User';

  export default {
    components: { Heading, ModalDelete, ModalEdit, Pagination, User },
    data() {
      return {
        title: 'Users',
        edit_modal_id: 'edit_modal_',
        delete_modal_id: 'delete_modal_',

        initialized: false,
        loading: false,
        total: 0,
        page: 1,
        pages: [],

        users: [],
        current_user: '',
        user_types: [],

        // Filters
        filter_active: '-1',
        filter_verified: '-1',
        filter_user_type: '0',
        filters: [],
      }
    },
    created() {
      this.fetchData();
    },
    methods: {

      // Change the filter value and refresh the page.
      changeFilter(type,value) {
        this[type] = value;
        this.fetchData();
      },

      // Change the current page.
      changePage(page) {
        this.page = page;
        this.fetchData();
      },

      // Deletes the user in the database, will only refresh on error.
      deleteUser(id,index) {
        this.loading = true;
        this.$delete(this.users,index);
        this.total--;
        axios.delete("/users/"+id)
          .then(response => { this.loading = false; })
          .catch(error => { this.fetchData(); });
      },

      // Updates the data on the page
      fetchData() {
        this.loading = true;
        axios.get("/users",{params: {
          vue: true,
          page: this.page,
          filter_active: this.filter_active,
          filter_verified: this.filter_verified,
          filter_user_type: this.filter_user_type
        }}).then((response)=>{
          this.filter_active = response.data.filter_active;
          this.filter_verified = response.data.filter_verified;
          this.filter_user_type = response.data.filter_user_type;
          this.filters = [
            {'prop': 'filter_active', 'all_values': [{'id': -1, 'name': 'All'},{'id': 1, 'name': 'Active'},{'id': 0, 'name': 'Inactive'}]},
            {'prop': 'filter_verified', 'all_values': [{'id': -1, 'name': 'All'},{'id': 1, 'name': 'Verified'},{'id': 0, 'name': 'Unverified'}]},
            {'prop': 'filter_user_type', 'all_values': [{'id': 0, 'name': 'All User Types'}].concat(response.data.user_types)}
          ];

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

      // Updates the user in the database, will only refresh on error.
      updateUser(updated_user,index) {
        this.loading = true;
        this.users[index] = updated_user;
        axios.put('/users/'+updated_user.id,{
          name: updated_user.name,
          email: updated_user.email,
          user_type_id: updated_user.user_type_id,
          is_admin: updated_user.is_admin,
          is_active: updated_user.is_active,
          email_verified_at: updated_user.email_verified_at
        })
          .then(response => {
            this.fetchData(); // If I add something for filters I can change this back.
            // this.users[index].userType = response.data.userType;
            // this.loading = false;
          })
          .catch(error => { this.fetchData(); });
      },

    }

  };

</script>
