fetchData<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">

        <!-- Header -->
        <management-heading :title="title" :initialized="initialized" :loading="loading" :total="total"></management-heading>

          <!-- Body -->
          <div class="card-body" v-if="initialized">

            <!-- Special Routes -->
            <div class="row mb-2">
              <div class="col col-12 col-md-4 p-2" v-for="routes in special_routes">
                <div class="card border-secondary h-100">
                  <div class="card-header p-2 h5 text-white bg-secondary">
                    <div class="row p-0 m-0">
                      <div class="col col-6 p-0 m-0">
                        {{routes.display}}
                      </div>
                      <div class="col col-6 text-right p-0 m-0">
                        <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#routeModal" data-backdrop="static" data-keyboard="false" v-on:click="assignRoutesInitialize(routes.name,-1)">Assign Routes</button>
                      </div>
                    </div>
                  </div>
                  <div class="card-body p-2">
                    <button type="button" v-for="route in routes" v-if="route.active == 1" class="btn btn-sm btn-info m-1" disabled>{{route.name}} </button>
                  </div>
                </div>
              </div>
            </div>
            <!-- END Special Routes -->

            <hr/>

            <!-- Pagination -->
            <pagination :page="page" :pages="pages"></pagination>

            <!-- Add User Type -->
            <div class="row mb-2">
              <div class="col col-12 flex-center text-right">
                <button type="button" v-on:click="addUserType()" class="btn btn-sm btn-outline-success">ADD USER TYPE</button>
              </div>
            </div>
            <!-- END Add User Type -->

            <!-- User Types -->
            <div class="row mb-2">
              <div class="col col-12 col-md-4 p-2" v-for="(user_type,i) in user_types">
                <div class="card border-secondary h-100">
                  <div class="card-header p-2 h5 text-white bg-secondary">
                    <div class="row p-0 m-0">
                      <div class="col col-6 p-0 m-0">
                        <input type="text" class="lemon-input" v-model="user_type.name" :key="i" v-on:keyup="updateUserType('user',i)">
                      </div>
                      <div class="col col-6 text-right p-0 m-0">
                        <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#routeModal" data-backdrop="static" data-keyboard="false" v-on:click="assignRoutesInitialize('user',i)">Assign Routes</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" v-on:click="deleteUserType(user_type.id,i)">Delete</button>
                      </div>
                    </div>
                  </div>
                  <div class="card-body p-2">
                    <button type="button" v-for="route in user_type.routes" v-if="route.active == 1" class="btn btn-sm btn-info m-1" disabled>{{route.name}} </button>
                  </div>
                </div>
              </div>
            </div>
            <!-- END User Types -->

            <!-- Route Selection Modal -->
            <div class="modal fade" id="routeModal" tabindex="-1" role="dialog" aria-labelledby="routeModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header p-2"><h5 class="modal-title" id="routeModalLabel">{{active_assign_display}}</h5></div>
                  <div class="modal-body">
                    <div class="list-group">
                      <button type="button"
                        v-for="(route,route_index) in active_routes_array"
                        v-if="route.active !== -1"
                        class="list-group-item"
                        v-bind:class="route.active == 1 ? 'list-group-item-primary' : 'list-group-item-light'"
                        v-on:click="assignRoutesToggle(route_index)">
                        {{route.name}}
                      </button>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-success" v-on:click="assignRoutesSave()">Done</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- END Route Selection Modal -->

          </div>
          <!-- END Body -->

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
        title: 'User Types',
        initialized: false,
        loading: false,

        active_routes_array: [],
        active_assign_name: '',
        active_assign_display: '',
        active_user_type_index: -1,

        user_types: [],
        public_routes: [],
        logged_in_routes: [],
        admin_routes: [],

        special_routes: [],

        total: 0,
        page: 1,
        pages: [],
      }
    },

    created() {
      this.fetchData();
    },

    methods: {

      // Add a new user in the database, will refresh
      addUserType() {
        this.loading = true;
        axios.post("/user_types")
          .then(response => { this.fetchData(); })
          .catch(error => { this.fetchData(); });
      },

      // Set the active variables for assigning routes
      assignRoutesInitialize(type, index) {
        switch (type) {
          case 'public':
            this.active_routes_array = this.public_routes;
            this.active_assign_name = 'public';
            this.active_assign_display = 'Assign Public Routes';
            break;
          case 'logged_in':
            this.active_routes_array = this.logged_in_routes;
            this.active_assign_name = 'logged_in';
            this.active_assign_display = 'Assign Logged In Routes';
            break;
          case 'admin':
            this.active_routes_array = this.admin_routes;
            this.active_assign_name = 'admin';
            this.active_assign_display = 'Assign Admin Routes';
            break;
          default:
            this.active_routes_array = this.user_types[index].routes;
            this.active_assign_name = 'user';
            this.active_assign_display = 'Assign Routes for '+this.user_types[index].name;
            this.active_user_type_index = index;
            break;
        }
      },

      // Send the update commands and clean up active variable data
      assignRoutesSave() {
        if(this.active_assign_name == 'user') {
          this.updateUserType('user',this.active_user_type_index);
        } else {
          this.updateUserType(this.active_assign_name,-1);
        }
        this.active_routes_array = [];
        this.active_assign_name = '';
        this.active_assign_display = '';
        this.active_user_type_index = -1;
        $('#routeModal').modal('hide');
      },

      // Change the route access for all types
      assignRoutesToggle(index) {
        this.active_routes_array[index].active = !this.active_routes_array[index].active;
        var current_route = this.active_routes_array[index];

        switch (this.active_assign_name) {
          case 'public':
            this.assignRoutesUpdateAssignable(current_route,this.logged_in_routes);
            this.assignRoutesUpdateAssignable(current_route,this.admin_routes);
            this.assignRoutesUpdateAssignable(current_route);
            break;
          case 'logged_in':
            this.assignRoutesUpdateAssignable(current_route,this.public_routes);
            this.assignRoutesUpdateAssignable(current_route,this.admin_routes);
            this.assignRoutesUpdateAssignable(current_route);
            break;
          case 'admin':
            this.assignRoutesUpdateAssignable(current_route,this.public_routes);
            this.assignRoutesUpdateAssignable(current_route,this.logged_in_routes);
            this.assignRoutesUpdateAssignable(current_route);
            break;
          default:
            this.assignRoutesUpdateAssignable(current_route,this.public_routes);
            this.assignRoutesUpdateAssignable(current_route,this.logged_in_routes);
            this.assignRoutesUpdateAssignable(current_route,this.admin_routes);
        }
      },

      // Update what routes are assignable for each type
      assignRoutesUpdateAssignable(current_route,routes) {
        if(routes) {
          routes.forEach(function(route) {
            if(route.name == current_route.name) {
              if(current_route.active == 1) {
                route.active = -1;
              } else {
                var allow_selection = true;
                this.user_types.forEach(function(user_type) {
                  user_type.routes.forEach(function(user_type_route) {
                    if(user_type_route.name == current_route.name && user_type_route.active == 1) {
                      allow_selection = false;
                    }
                  });
                });
                route.active = allow_selection ? 0 : -1;
              }
            }
          },this);
        } else {
          this.user_types.forEach(function(user_type) {
            user_type.routes.forEach(function(route) {
              if(route.name == current_route.name) {
                route.active = current_route.active == 1 ? -1 : 0;
              }
            });
          });
        }
      },

      // Deletes a user type in the database, updates assignable routes, only refreshes on error.
      deleteUserType(id,index) {
        this.loading = true;
        this.user_types[index].routes.forEach(function(route) {
          if(route.active == 1) {
            route.active = 0;
            this.assignRoutesUpdateAssignable(route,this.public_routes);
            this.assignRoutesUpdateAssignable(route,this.logged_in_routes);
            this.assignRoutesUpdateAssignable(route,this.admin_routes);
          }
        },this);
        this.$delete(this.user_types,index);
        axios.delete("/user_types/"+id)
          .then(response => { this.loading = false; })
          .catch(error => { this.fetchData(); });
      },

      // Initial retrieval and refresh of data
      fetchData() {
        this.loading = true;
        axios.get("/user_types",{params: {vue: true, page: this.page}}).then((response)=>{
          this.user_types = response.data.user_types.data;
          this.public_routes = response.data.public_routes;
          this.logged_in_routes = response.data.logged_in_routes;
          this.admin_routes = response.data.admin_routes;

          this.public_routes.display = 'Public Routes';
          this.public_routes.name = 'public';
          this.logged_in_routes.display = 'Logged In Routes';
          this.logged_in_routes.name = 'logged_in';
          this.admin_routes.display = 'Admin Routes';
          this.admin_routes.name = 'admin';
          this.special_routes = [this.public_routes,this.logged_in_routes,this.admin_routes];

          this.total = parseInt(response.data.user_types.total);
          this.page = parseInt(response.data.page);
          this.pages = response.data.pages;

          this.loading = false;
          this.initialized = true;
        });
      },

      // Updates user type and/or route variable data in the database, only refreshes on error.
      updateUserType(type, index) {
        this.loading = true;
        var routes = [];
        var user_type_id = 0;
        var user_type_name = '';
        switch (type) {
          case 'public':
            routes = this.public_routes;
            break;
          case 'logged_in':
            routes = this.logged_in_routes;
            break;
          case 'admin':
            routes = this.admin_routes;
            break;
          default:
            routes = this.user_types[index].routes;
            user_type_id = this.user_types[index].id;
            user_type_name = this.user_types[index].name;
        }
        var route_access = '';
        for(var i=0; i<routes.length; i++) {
          if(routes[i].active == 1) {
            route_access += route_access == '' ? routes[i].name : ','+routes[i].name;
          }
        }
        axios.put("/user_types/"+user_type_id,{type: type, name: user_type_name, route_access: route_access})
          .then(response => { this.loading = false; })
          .catch(error => { this.fetchData(); });
      }

    }

  };

</script>
