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

        <div v-if="alert" class="alert alert-danger alert-dismissible fade show text-center m-3">
          {{alert_message}}
          <button type="button" class="close" aria-label="Close" @click="alertClear()"><span aria-hidden="true">&times;</span></button>
        </div>

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
                {'name': 'value', 'display': 'Value'},
                {'name': 'info', 'display': 'Information'},
                {'name': 'organization_id', 'display': 'Organization', 'type': 'select', 'hide_first_option': true, 'options': organizations, 'invisible': active_organization}]"
              @save="createVariable">
            </modal-create>

            <!-- Pagination -->
            <pagination :page="page" :pages="pages" @change="changePage"></pagination>

            <!-- List of Variables -->
            <div v-for="(variable,index) in variables" :key="variable.id">

              <!-- Variable Info -->
              <show-item :key="update_counter"
                :index="index"
                :loading="loading"
                :label="variable.organization_name+' - '+variable.type"
                :label_class="'text-info'"
                :data="[{'title': 'Key', 'value': variable.key}]"
                :data_space="variable.routes ? variable.routes : variable.value"
                :options="[
                  {'action': 'modal', 'class': 'btn-outline-info', 'target': edit_modal_id+variable.id, 'display': 'Edit', 'disabled': loading},
                  {'action': 'modal', 'class': variable.protected == 1 ? 'btn-outline-secondary' : 'btn-outline-danger', 'target': delete_modal_id+variable.id, 'display': 'Delete', 'disabled': loading || variable.protected == 1}
                ]">
              </show-item>

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
                :fields="[
                  {'name': 'value', 'display': 'Value'},
                  {'name': 'organization_id', 'display': 'Organization', 'type': 'select', 'hide_first_option': true, 'options': organizations, 'invisible': active_organization}]"
                @save="updateVariable">
              </modal-edit>

              <!-- Delete Modal -->
              <modal-delete
                :modal_id="delete_modal_id+variable.id"
                :text="'Are you sure you want to delete variable '+variable.organization_name+' '+variable.type+' - '+variable.key+'?'"
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
  import Pagination from './child_components/Pagination';
  import ShowItem from './child_components/ShowItem';

  export default {

    components: { Heading, ModalCreate, ModalDelete, ModalEdit, ModalEditRouteAccess, Pagination, ShowItem },

    data() {
      return {
        title: 'Variables',
        create_modal_id: 'modal_create_',
        edit_modal_id: 'modal_edit_',
        delete_modal_id: 'modal_delete_',
        initialized: false,
        loading: false,
        total: 0,
        update_counter:0,

        variables: [],
        organizations: [],
        active_organization: [],

        // Pagination
        page: 1,
        pages: [],

        // Alerts
        alert: false,
        alert_message: "",

        // Filters
        filter_organization: 0,
        filter_type: '',
        filters: []
      }
    },

    created() {
      this.fetchData();
    },

    methods: {

      // Clears the alert
      alertClear() {
        this.alert = false;
        this.alert_message = "";
      },

      // Sets an alert message
      alertSet(message) {
        this.alert = true;
        this.alert_message = message;
      },

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

      // Creates a new variable
      createVariable(obj) {
        this.loading = true;
        axios.post("/variables",{
          type: obj.type,
          key: obj.key,
          value: obj.value,
          info: obj.info,
          organization_id: this.active_organization ? this.active_organization.id : obj.organization_id})
          .then(response => {
            if(response.data == 0) {
              this.alertSet("That variable already exists.");
              this.loading = false;
            } else {
              this.fetchData();
            }
          }).catch(error => { this.fetchData(); });
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
        axios.get("/variables",{params: {vue: true, page: this.page, filter_organization: this.filter_organization, filter_type: this.filter_type}}).then((response)=>{
          this.variables = response.data.variables.data;
          this.organizations = [{'id': '', 'name': 'Global'}].concat(response.data.organizations);
          this.active_organization = response.data.active_organization;
          this.total = parseInt(this.variables.length);

          this.page = parseInt(response.data.page);
          this.pages = response.data.pages;

          this.filters = response.data.filters;
          this.filter_organization = response.data.filter_organization;
          this.filter_type = response.data.filter_type;

          this.loading = false;
          this.initialized = true;
        });
      },

      updateVariable(updated_variable,index) {
        this.loading = true;
        this.variables[index] = updated_variable;
        axios.put('/variables/'+updated_variable.id,{
          value: updated_variable.value,
          organization_id: this.active_organization ? this.active_organization.id : updated_variable.organization_id
        })
          .then(response => {
            if(response.data == 0) {
              this.alertSet("That variable already exists.");
            } else {
              this.organizations[index] = updated_organization;
              this.update_counter++;
            }
            this.loading = false;
          }).catch(error => { this.fetchData(); });
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
              this.update_counter++;
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
