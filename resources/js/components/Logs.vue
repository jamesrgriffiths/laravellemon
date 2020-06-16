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
        <div class="card-body" v-if="initialized">

          <!-- Pagination -->
          <pagination
            :page="page"
            :pages="pages"
            @change="changePage">
          </pagination>

          <!-- logs -->
          <div v-for="(log,index) in logs" :key="log.id">

            <!-- Log Info -->
            <log
              :index="index"
              :log="log"
              :loading="loading"
              :trace_modal_id="trace_modal_id+log.id"
              @delete="deleteLog">
            </log>

            <!-- Full Trace Modal -->
            <modal-info
              :modal_id="trace_modal_id+log.id"
              :title="'Full Trace of Log #'+log.id"
              :text="log.trace">
            </modal-info>

          </div>

        </div>

      </div>
    </div>
  </div>
</template>

<script>

  import Heading from './child_components/Heading';
  import Log from './child_components/Log';
  import ModalInfo from './child_components/ModalInfo';
  import Pagination from './child_components/Pagination';

  export default {

    components: { Heading, Log, ModalInfo, Pagination },

    data() {
      return {
        title: 'Logs',
        trace_modal_id: 'trace_modal_',

        total: 0,
        page: 1,
        pages: [],
        initialized: false,
        loading: false,

        // Filters
        type: '0',
        user: '0',
        ip: '0',
        filters: [],

        logs: [],
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

      // Change the current page and refresh the page.
      changePage(page) {
        this.page = page;
        this.fetchData();
      },

      // Delete a given log.
      deleteLog(id,type) {
        axios.delete("/logs/"+id,{data: {type: type}})
          .then(this.fetchData());
      },

      // Get the updated logs data.
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
      }

    }

  };

</script>
