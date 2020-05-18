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
                SYSTEM LOGS: <span class="text-info">{{total}}</span><br/>
                <div class="form-inline justify-content-center">
                  <select class="form-control" v-model="type"><option v-for="type in types" v-bind:value="type.id">{{ type.name }}</option></select>
                  <select class="form-control" v-model="user"><option v-for="user in users" v-bind:value="user.id">{{ user.name }}</option></select>
                  <select class="form-control" v-model="ip"><option v-for="ip in ips" v-bind:value="ip.id">{{ ip.name }}</option></select>
                </div>
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

          <!-- logs -->
          <div v-for="(log,i) in logs">
            <div class="row p-2" v-bind:class="i%2 ? 'bg-light' : ''">
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
  export default {

    data() {
      return {
        timer: '',
        loading: false,

        // Filters
        type: '0',
        user: '0',
        ip: '0',
        types: [],
        users: [],
        ips: [],

        total: '',
        show_trace: '',
        logs: [],

        current_page: 1,
        pages: [],
      }
    },

    watch: {
      type: function() { this.fetchLogs(); },
      user: function() { this.fetchLogs(); },
      ip: function() { this.fetchLogs(); },
    },

    created() {
      this.fetchLogs();
      // this.timer = setInterval(this.fetchLogs, 30000);
      console.log("1");
    },

    beforeDestroy () {
      clearInterval(this.timer);
    },

    methods: {

      changePage(page) {
        this.current_page = page;
        this.fetchLogs();
      },

      deleteLog(id,type) {
        axios.delete("/logs/"+id,{data: {type: type}})
          .then(this.fetchLogs());
      },

      fetchLogs() {
        console.log("C1");
        if(!this.loading) {
          this.loading = true;
          axios.get("/logs",{params: {vue: true, type: this.type, user: this.user, ip: this.ip, current_page: this.current_page}}).then((response)=>{
            this.type = response.data.type;
            this.user = response.data.user;
            this.ip = response.data.ip;

            this.types = response.data.types;
            this.users = response.data.users;
            this.ips = response.data.ips;

            this.total = response.data.logs.total;
            this.logs = response.data.logs.data;

            this.current_page = response.data.current_page;
            this.pages = response.data.pages;

            console.log("C2");

            this.loading = false;
          });
        }
        console.log("C3");
      },

      toggleShowTrace(id) {
        this.show_trace == id ? this.show_trace = '' : this.show_trace = id;
        console.log("D");
      }

    }

  };
</script>
