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
            <show-item
              :index="index"
              :loading="loading"
              :label="log.type"
              :label_class="log.type == 'Error' ? 'text-danger' : 'text-info'"
              :data="[
                {'title': 'Time', 'value': convertDateTime(log.created_at)},
                {'title': 'User', 'value': log.user ? log.user.name+' - '+log.organization_name+' '+log.user_type_name : 'None'},
                {'title': 'IP', 'value': log.ip_address},
                {'title': 'Device', 'value': log.device_cleaned},
                {'title': 'URL', 'value': log.url},
                {'title': 'Run Time', 'value': log.run_time},
                {'title': 'Class', 'value': log.class},
                {'title': 'Message', 'value': log.message}
              ]"
              :options="[
                {'action': 'modal', 'class': 'btn-outline-info', 'target': trace_modal_id+log.id, 'display': 'Trace'},
                {'action': 'click', 'class': 'btn-outline-danger', 'target': ['delete',log.id,index], 'display': 'Delete', 'disabled': loading}
              ]"
              @delete="deleteLog">
            </show-item>

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
  import ModalInfo from './child_components/ModalInfo';
  import Pagination from './child_components/Pagination';
  import ShowItem from './child_components/ShowItem';

  export default {

    components: { Heading, ModalInfo, Pagination, ShowItem },

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
        axios.delete("/logs/"+id,{params: {vue: true}})
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
          this.filters = response.data.filters;
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
