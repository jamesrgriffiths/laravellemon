<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">

        <!-- Header -->
        <management-heading :title="title" :initialized="initialized" :loading="loading" :total="total" :filters="filters"></management-heading>

        <!-- Body -->
        <div class="card-body" v-if="initialized">

          <!-- Pagination -->
          <pagination :page="page" :pages="pages"></pagination>

          <!-- logs -->
          <div v-for="(log,i) in logs">
            <div class="row p-2" v-bind:class="i%2 ? '' : 'bg-light'">
              <div class="col col-12">
                <b v-bind:class="log.type == 'Error' ? 'text-danger' : 'text-info'">{{log.type}}</b><br/>
              </div>
              <div class="col col-12 col-md-3">
                <b>Time:&nbsp;</b>{{convertDateTime(log.created_at)}}<br/>
                <b>User:&nbsp;</b>{{log.user ? log.user.name + " (" + log.user_type_name + ")" : 'None'}}<br/>
                <b>IP:&nbsp;</b>{{log.ip_address}}
              </div>
              <div class="col col-12 col-md-3">
                <b>Device:&nbsp;</b><span v-bind:title="log.device">{{log.device_cleaned}}</span><br/>
                <b>URL:&nbsp;</b>{{log.url}}
                <span v-if="log.type == 'Request'"><br/><b>Run Time:&nbsp;</b>{{log.run_time}}</span>
              </div>
              <div class="col col-12 col-md-2">
                <b>Class:&nbsp;</b>{{log.class}}<br/>
                <b>Message:&nbsp;</b>{{log.message}}
              </div>
              <div class="col col-12 col-md-1 my-auto">
                <button v-if="log.trace" type="button" class="btn btn-sm btn-outline-info m-1" v-on:click="toggleShowTrace(log.id)">Full Trace</button>
              </div>
              <div class="col col-12 col-md-3 my-auto">
                <button type="button" class="btn btn-sm btn-outline-danger m-1" v-on:click="deleteLog(log.id,'delete')">Delete</button>
                <button type="button" class="btn btn-sm btn-outline-danger m-1" v-on:click="deleteLog(log.id,'delete_class')">Delete all by class and url</button>
                <button type="button" class="btn btn-sm btn-outline-danger m-1" v-on:click="deleteLog(log.id,'delete_ip')">Delete all by IP Address</button>
              </div>
              <div v-if="show_trace == log.id" class="col col-12 m-3">
                {{log.trace}}
              </div>
            </div>
          </div>
          <!-- END Logs -->

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
        title: 'Logs',
        initialized: false,
        loading: false,

        // Filters
        type: '0',
        user: '0',
        ip: '0',
        filters: [],

        show_trace: '',
        logs: [],

        total: 0,
        page: 1,
        pages: [],
      }
    },

    watch: {
      type: function() { this.fetchData(); },
      user: function() { this.fetchData(); },
      ip: function() { this.fetchData(); },
    },

    created() {
      this.fetchData();
    },

    methods: {

      deleteLog(id,type) {
        axios.delete("/logs/"+id,{data: {type: type}})
          .then(this.fetchData());
      },

      fetchData() {
        if(!this.loading) {
          this.loading = true;
          axios.get("/logs",{params: {vue: true, type: this.type, user: this.user, ip: this.ip, page: this.page}}).then((response)=>{
            this.type = response.data.type;
            this.user = response.data.user;
            this.ip = response.data.ip;

            this.filters = [
              {'prop': 'type', 'all_values': response.data.types},
              {'prop': 'user', 'all_values': response.data.users},
              {'prop': 'ip', 'all_values': response.data.ips}
            ]

            this.logs = response.data.logs.data;

            this.total = parseInt(response.data.logs.total);
            this.page = parseInt(response.data.page);
            this.pages = response.data.pages;

            this.loading = false;
            this.initialized = true;
          });
        }
      },

      toggleShowTrace(id) {
        this.show_trace == id ? this.show_trace = '' : this.show_trace = id;
      }

    }

  };

</script>
