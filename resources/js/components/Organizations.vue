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

        <div v-if="alert" class="alert alert-danger alert-dismissible fade show text-center m-3">
          {{alert_message}}
          <button type="button" class="close" aria-label="Close" @click="alertClear()"><span aria-hidden="true">&times;</span></button>
        </div>

        <!-- Body -->
        <div v-if="initialized" class="card-body">

          <!-- Add New Organization -->
          <div v-if="!active_organization" class="row">
            <div class="col col-12 text-right">
              <button type="button" class="btn btn-sm btn-outline-success m-1" data-toggle="modal" :data-target="'#'+create_modal_id">Add Organization</button>
            </div>
          </div>

          <!-- Add New Organization Modal -->
          <modal-create v-if="!active_organization"
            :modal_id="create_modal_id"
            :label="'Create A New Organization'"
            :fields="[
              {'name': 'name', 'display': 'Name'},
              {'name': 'slug', 'display': 'Sub Domain'}]"
            @save="createOrganization">
          </modal-create>

          <!-- Pagination -->
          <pagination
            :page="page"
            :pages="pages"
            @change="changePage">
          </pagination>

          <!-- Organizations -->
          <div v-for="(organization,index) in organizations" :key="organization.id">

            <!-- Organization Info -->
            <show-item :key="update_counter"
              :index="index"
              :loading="loading"
              :label="organization.name"
              :label_class="'text-info'"
              :data="[
                {'title': 'Sub Domain', 'value': organization.slug},
                {'title': 'Users', 'value': organization.user_count}
              ]"
              :options="[
                {'action': 'modal', 'class': 'btn-outline-info', 'target': edit_modal_id+organization.id, 'display': 'Edit', 'disabled': loading},
                {'action': 'modal', 'class': 'btn-outline-danger', 'target': delete_modal_id+organization.id, 'display': 'Delete', 'disabled': loading}
              ]">
            </show-item>

            <!-- Organization Edit Modal -->
            <modal-edit
              :modal_id="edit_modal_id+organization.id"
              :index="index"
              :label="'Edit '+organization.name+' (#'+organization.id+')'"
              :object="organization"
              :fields="[
                {'name': 'name', 'display': 'Name'},
                {'name': 'slug', 'display': 'Sub Domain'}]"
              @save="updateOrganization">
            </modal-edit>

            <!-- Delete Modal -->
            <modal-delete v-if="!active_organization"
              :modal_id="delete_modal_id+organization.id"
              :text="'Are you sure you want to delete the organization '+organization.name+' (#'+organization.id+')? This will also delete all associated variables.'"
              @delete="deleteOrganization(organization.id,index)">
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
  import Pagination from './child_components/Pagination';
  import ShowItem from './child_components/ShowItem';

  export default {
    components: { Heading, ModalCreate, ModalDelete, ModalEdit, Pagination, ShowItem },
    data() {
      return {
        title: 'Organizations',
        create_modal_id: 'create_modal_',
        edit_modal_id: 'edit_modal_',
        delete_modal_id: 'delete_modal_',

        initialized: false,
        loading: false,
        total: 0,
        page: 1,
        pages: [],
        update_counter: 0,

        organizations: [],
        active_organization: '',

        // Alerts
        alert: false,
        alert_message: "",
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

      // Change the current page.
      changePage(page) {
        this.page = page;
        this.fetchData();
      },

      // Creates a new organization in the database, will refresh.
      createOrganization(obj) {
        this.loading = true;
        axios.post("/organizations",{
          vue: true,
          name: obj.name,
          slug: obj.slug
        }).then(response => {
          if(response.data == 0) {
            this.alertSet("That organization (or sub domain) already exists.");
            this.loading = false;
          } else {
            this.fetchData();
          }
        }).catch(error => { this.fetchData(); });
      },

      // Deletes the organization in the database, will only refresh on error.
      deleteOrganization(id,index) {
        this.loading = true;
        this.$delete(this.organizations,index);
        this.total--;
        axios.delete("/organizations/"+id,{params: {vue: true}})
          .then(response => { this.loading = false; })
          .catch(error => { this.fetchData(); });
      },

      // Updates the data on the page
      fetchData() {
        this.loading = true;
        axios.get("/organizations",{params: {vue: true,page: this.page}}).then((response)=>{
          this.organizations = response.data.organizations.data;
          this.active_organization = response.data.active_organization;
          this.total = parseInt(response.data.organizations.total);
          this.page = parseInt(response.data.page);
          this.pages = response.data.pages;
          this.$nextTick(() => {
            this.loading = false;
            this.initialized = true;
          });
        });
      },

      // Updates the organization in the database, will only refresh on error.
      updateOrganization(updated_organization,index) {
        this.loading = true;
        axios.put('/organizations/'+updated_organization.id,{
          name: updated_organization.name,
          slug: updated_organization.slug
        }).then(response => {
          if(response.data == 0) {
            this.alertSet("That organization (or sub domain) already exists.");
          } else {
            this.organizations[index] = updated_organization;
            this.update_counter++;
          }
          this.loading = false;
        }).catch(error => { this.fetchData(); });
      },

    }

  };

</script>
