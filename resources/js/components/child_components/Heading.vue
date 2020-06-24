<!-- Shows a page heading info
  title - The name of this page.
  initialized - Boolean for page initialized.
  loading - Boolean for page loading data.
  total - The total number of records of this type (based on filters).
  filters - An array of filters for this page. They should each have a prop field
    for identifying the property type of the filter and an all_values array for
    listing the values available.
  - emit 'change' - sends back updated filter with property type and value.
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
          <div v-if="total">
            <small v-if="initialized" class="text-info h5">Total {{title}}: {{total}}</small>
          </div>
          <div class="form-inline justify-content-center" v-if="initialized && filters">
            <select v-for="filter in filters" class="form-control m-1" v-on:change="$emit('change',filter.prop,$event.target.value)">
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
  props: ['title', 'initialized', 'loading', 'total', 'filters']
}
</script>
