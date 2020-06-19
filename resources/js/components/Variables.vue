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

            <!-- Create New Variable -->
            <div class="row">
              <div class="col col-12 text-right">
                <button type="button" class="btn btn-sm btn-outline-success m-1" data-toggle="modal" :data-target="'#'+create_modal_id">Create New Variable</button>
              </div>
            </div>

            <!-- Create New Variable Modal -->
            <modal-create
              :modal_id="create_modal_id"
              :label="'Create A New Variable'"
              :fields="[
                {'name': 'type', 'display': 'Type'},
                {'name': 'key', 'display': 'Key'},
                {'name': 'value', 'display': 'Value'}]"
              @save="createVariable">
            </modal-create>

            <!-- List of Variables -->
            <div v-for="(variable,index) in variables" :key="variable.id">

              <!-- Variable Info -->
              <variable
                :index="index"
                :variable="variable"
                :loading="loading"
                :edit_modal_id="edit_modal_id+variable.id"
                :delete_modal_id="delete_modal_id+variable.id">
              </variable>

              <!-- Edit Modal -->
              <modal-edit-route-access v-if="variable.type == 'Route Access'"
                :modal_id="edit_modal_id+variable.id"
                :index="index"
                :label="'Edit '+variable.type+' - '+variable.key"
                :object="variable"
                @save="updateVariableRoutes">
              </modal-edit-route-access>
              <modal-edit v-else
                :modal_id="edit_modal_id+variable.id"
                :index="index"
                :label="'Edit '+variable.type+' - '+variable.key"
                :object="variable"
                :fields="[{'name': 'value', 'display': 'Value'}]"
                @save="updateVariable">
              </modal-edit>

              <!-- Delete Modal -->
              <modal-delete
                :modal_id="delete_modal_id+variable.id"
                :text="'Are you sure you want to delete variable '+variable.type+' - '+variable.key+'?'"
                @delete="deleteVariable(variable.id,index)">
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
  import Variable from './child_components/Variable';

  export default {

    components: { Heading, ModalCreate, ModalDelete, ModalEdit, ModalEditRouteAccess, Variable },

    data() {
      return {
        title: 'Variables',
        create_modal_id: 'modal_create_',
        edit_modal_id: 'modal_edit_',
        delete_modal_id: 'modal_delete_',
        initialized: false,
        loading: false,
        variables: [],
        total: 0,
      }
    },

    created() {
      this.fetchData();
    },

    methods: {

      // Creates a new variable
      createVariable(obj) {
        this.loading = true;
        axios.post("/variables",{type: obj.type, key: obj.key, value: obj.value})
          .then(response => { this.fetchData(); })
          .catch(error => { this.fetchData(); });
      },

      // Delete a given variable
      deleteVariable(id,index) {
        this.loading = true;
        this.$delete(this.variables,index);
        this.total--;
        axios.delete("/variables/"+id)
          .then(response => { this.loading = false; })
          .catch(error => { this.fetchData(); });
      },

      // Initial retrieval and refresh of data
      fetchData() {
        this.loading = true;
        axios.get("/variables",{params: {vue: true}}).then((response)=>{
          this.variables = response.data.variables;
          this.total = parseInt(this.variables.length);
          this.loading = false;
          this.initialized = true;
        });
      },

      updateVariable(updated_variable,index) {
        this.loading = true;
        this.variables[index] = updated_variable;
        axios.put('/variables/'+updated_variable.id,{
          value: updated_variable.value
        })
          .then(response => { this.loading = false; })
          .catch(error => { this.fetchData(); });
      },

      async updateVariableRoutes(updated_variable,index) {
        this.loading = true;

        // Set the current variable's values
        var route_access = [];
        for(var i=0;i<updated_variable.routes.length;i++) {
          if(updated_variable.routes[i].active) {
            route_access.push(updated_variable.routes[i].name);
          }
        }
        this.variables[index].value = route_access.join();
        this.variables[index].routes = updated_variable.routes;

        // Save the current variable
        var success = await axios.put('/variables/'+updated_variable.id,{ value: this.variables[index].value })
          .then(response => { return 1; })
          .catch(error => { return 0; });

        // Update the route access variables or refresh
        if(success) {
          for(var i=0; i<this.variables.length; i++) {
            if(this.variables[i].type == 'Route Access') {
              await axios.get("/variables",{params: {
                option: 'get_assignable_routes_array',
                option_value: this.variables[i].value}})
                .then(response => { this.variables[i].routes = response.data; })
                .catch(error => { success = 0; } );
            }
            if(i == this.variables.length - 1 && success == 1) {
              this.loading = false;
            }
          }
        }

        // Refetch the data if there was an issue
        if(!success) { this.fetchData(); this.$forceUpdate(); }

      },

    }

  };

</script>