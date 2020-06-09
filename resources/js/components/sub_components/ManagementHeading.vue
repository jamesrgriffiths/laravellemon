<!-- Shows the heading for management type pages
REQUIREMENTS:
   VARIABLES:
      title - String for the title of this management page
      initialized - Boolean for if the page has initially loaded.
      loading - Boolean for if data is being refreshed.
      total - Number for the total number of records.
      filters - An array of filters for the page that are directly related to watched variables in the parent.
         - they should have a prop attribute that is the exact watched variable name
         - they should have an all_values attribute that is the list of all values. Each value should have an id and name attribute.
    FUNCTIONS:
      fetchData() - updates the data on the page
-->
<template>
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
          {{title}}
          <br/>
          <small v-if="initialized" class="text-info h5">Total {{title}}: {{total}}</small>
          <br/>
          <div class="form-inline justify-content-center" v-if="initialized && filters">
            <select v-for="filter in filters" class="form-control" v-on:change="updateFilters(filter.prop, $event)">
              <option v-for="value in filter.all_values" v-bind:value="value.id">{{value.name}}</option>
            </select>
          </div>
        </div>
        <div class="col col-1 p-0">&nbsp;</div>
      </div>
    </h2>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    initialized: Boolean,
    loading: Boolean,
    total: Number,
    filters: Array
  },
  methods: {
    updateFilters(prop, event) {
      this.$parent[prop] = event.target.value;
    }
  }
}
</script>
