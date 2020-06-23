<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">

        <!-- Header -->
        <heading
          :title="title"
          :initialized="initialized"
          :loading="loading"
          :total="total">
        </heading>

          <!-- Body -->
          <div v-if="initialized" class="card-body">

            <!-- Add New User Type -->
            <div class="row">
              <div class="col col-12 text-right">
                <button type="button" class="btn btn-sm btn-outline-success m-1" data-toggle="modal" :data-target="'#'+create_modal_id">Add User Type</button>
              </div>
            </div>

            <!-- Add New User Type Modal -->
            <modal-create
              :modal_id="create_modal_id"
              :label="'Create A New User Type'"
              :fields="[{'name': 'name', 'display': 'Name'}]"
              @save="createUserType">
            </modal-create>

            <!-- Pagination -->
            <pagination :page="page" :pages="pages" @change="changePage"></pagination>

            <!-- User Types -->
            <div v-for="(user_type,index) in user_types" :key="user_type.id">

              <!-- User Type Info -->
              <show-item :key="update_counter"
                :index="index"
                :loading="loading"
                :label="user_type.name"
                :label_class="'text-info'"
                :data="[{'title': 'Users', 'value': user_type.user_count}]"
                :data_space="user_type.routes"
                :options="[
                  {'action': 'modal', 'class': 'btn-outline-info', 'target': assign_modal_id+user_type.id, 'display': 'Assign Routes', 'disabled': loading},
                  {'action': 'modal', 'class': 'btn-outline-info', 'target': edit_modal_id+user_type.id, 'display': 'Edit', 'disabled': loading},
                  {'action': 'modal', 'class': 'btn-outline-danger', 'target': delete_modal_id+user_type.id, 'display': 'Delete', 'disabled': loading}
                ]">
              </show-item>

              <!-- Assign Routes Modal -->
              <modal-edit-route-access
                :modal_id="assign_modal_id+user_type.id"
                :index="index"
                :label="'Assign Routes for '+user_type.name"
                :object="user_type"
                @save="updateChangeRoutes">
              </modal-edit-route-access>

              <!-- Edit Modal -->
              <modal-edit
                :modal_id="edit_modal_id+user_type.id"
                :index="index"
                :label="'Edit '+user_type.name"
                :object="user_type"
                :fields="[{'name': 'name', 'display': 'Name'}]"
                @save="updateChangeObject">
              </modal-edit>

              <!-- Delete Modal -->
              <modal-delete
                :id="delete_modal_id+user_type.id"
                :text="'Are you sure you want to delete user type '+user_type.name+'?'"
                @delete="deleteUserType(user_type.id,index)">
              </modal-delete>

            </div>

          </div>

        </div>
      </div>
    </div>
</template>

<script>

  import Heading from './child_components/Heading';
  import ModalCreate from './child_components/ModalCreate';
  import ModalDelete from './child_components/ModalDelete';
  import ModalEdit from './child_components/ModalEdit';
  import ModalEditRouteAccess from './child_components/ModalEditRouteAccess';
  import Pagination from './child_components/Pagination';
  import ShowItem from './child_components/ShowItem';

  export default {

    components: { Heading, ModalCreate, ModalDelete, ModalEdit, ModalEditRouteAccess, Pagination, ShowItem },

    data() {
      return {
        title: 'User Types',
        create_modal_id: 'create_modal_',
        assign_modal_id: 'assign_modal_',
        edit_modal_id: 'edit_modal_',
        delete_modal_id: 'delete_modal_',
        initialized: false,
        loading: false,
        total: 0,
        page: 1,
        pages: [],
        update_counter:0,
        user_types: []
      }
    },

    created() {
      this.fetchData();
    },

    methods: {

      // Change the current page.
      changePage(page) {
        this.page = page;
        this.fetchData();
      },

      // Creates a new user type in the database, will refresh.
      createUserType(obj) {
        this.loading = true;
        axios.post("/user_types",{name: obj.name})
          .then(response => { this.fetchData(); })
          .catch(error => { this.fetchData(); });
      },

      // Deletes a user type in the database, only refreshes on error.
      deleteUserType(id,index) {
        this.loading = true;
        this.$delete(this.user_types,index);
        this.total--;
        axios.delete("/user_types/"+id)
          .then(response => { this.loading = false; })
          .catch(error => { this.fetchData(); });
      },

      // Initial retrieval and refresh of data
      fetchData() {
        this.loading = true;
        axios.get("/user_types",{params: {vue: true, page: this.page}}).then((response)=>{
          this.user_types = response.data.user_types.data;
          this.total = parseInt(response.data.user_types.total);
          this.page = parseInt(response.data.page);
          this.pages = response.data.pages;
          this.loading = false;
          this.initialized = true;
        });
      },

      update(index) {
        this.loading = true;
        var user_type = this.user_types[index];
        this.update_counter++;
        axios.put("/user_types/"+user_type.id,{name: user_type.name, route_access: user_type.route_access})
          .then(response => { this.loading = false; })
          .catch(error => { this.fetchData(); });
      },

      updateChangeObject(updated_user_type,index) {
        this.loading = true;
        this.user_types[index] = updated_user_type;
        this.update(index);
      },

      updateChangeRoutes(updated_user_type,index) {
        this.loading = true;
        var user_type = this.user_types[index];
        user_type.routes = updated_user_type.routes;
        var new_route_access = '';
        for(var i=0; i<user_type.routes.length; i++) {
          if(user_type.routes[i].active == 1) {
            new_route_access += new_route_access == '' ? user_type.routes[i].name : ','+user_type.routes[i].name;
          }
        }
        user_type.route_access = new_route_access;
        this.update(index);
      }

    }

  };

</script>
