<template>
  <div>

    <!-- Content -->
    <div class="container-fluid mt-3">
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

        initialized: false,
        loading: false,
        total: 0,
        page: 1,
        pages: [],

        logs: [],

        // Filters
        filter_type: '0',
        filter_user: '0',
        filter_ip: '0',
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

      // Change the current page and refresh the page.
      changePage(page) {
        this.page = page;
        this.fetchData();
      },

      // Delete a given log.
      deleteLog(id,index) {
        this.loading = true;
        this.$delete(this.logs,index);
        this.total--;
        axios.delete("/logs/"+id)
          .then(response => { this.loading = false; })
          .catch(error => { this.fetchData(); });
      },

      // Get the updated logs data.
      fetchData() {
        this.loading = true;
        axios.get("/logs",{params: {vue: true, filter_type: this.filter_type, filter_user: this.filter_user, filter_ip: this.filter_ip, page: this.page}}).then((response)=>{
          this.filter_type = response.data.filter_type;
          this.filter_user = response.data.filter_user;
          this.filter_ip = response.data.filter_ip;
          this.filters = [
            {'prop': 'filter_type', 'all_values': [{'id': 0, 'name': 'All Types'},{'id': 'request', 'name': 'Requests'},{'id': 'error', 'name': 'Errors'}]},
            {'prop': 'filter_user', 'all_values': [{'id': 0, 'name': 'All Users'},{'id': -1, 'name': 'No User'}].concat(response.data.users)},
            {'prop': 'filter_ip', 'all_values': [{'id': 0, 'name': 'All IPs'}].concat(response.data.ips)}
          ];

          this.logs = response.data.logs.data;

          this.total = parseInt(response.data.logs.total);
          this.page = parseInt(response.data.page);
          this.pages = response.data.pages;

          this.$nextTick(() => {
            this.loading = false;
            this.initialized = true;
          });
        });
      }

    }

  };

</script>
