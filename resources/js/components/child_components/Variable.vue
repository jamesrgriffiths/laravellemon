<!-- Shows a variable with options for editing or deleting them in a row format
  index - The index of the variable - used for row coloring.
  variable - The variable data object.
  loading - The loading attribute.
  edit_modal_id - The id for referencing the edit modal.
  delete_modal_id - The id for referencing the delete modal.
-->
<template>
  <div class="row p-2" v-bind:class="index%2 ? 'bg-light' : ''">
    <div class="col col-8">
      <div class="row">
        <div class="col col-12 col-md-4">
          <span class="h5">{{variable.type}} - </span>
          <span class="h6">{{variable.key}}</span>
        </div>
        <div class="col col-12 col-md-8">
          <button v-if="variable.type == 'Route Access' && variable.routes && route.active" type="button" v-for="route in variable.routes" class="btn btn-sm btn-info m-1" disabled>{{route.name}}</button>
          <span v-if="variable.type != 'Route Access'">{{variable.value}}</span>
        </div>
      </div>
    </div>
    <div class="col col-4 my-auto text-right">
      <button type="button" class="btn btn-sm btn-outline-info m-1" data-toggle="modal" :data-target="'#'+edit_modal_id" :disabled="loading">Edit</button>
      <button type="button" class="btn btn-sm btn-outline-danger m-1" data-toggle="modal" :data-target="'#'+delete_modal_id" :disabled="loading || variable.protected == 1">Delete</button>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['index', 'variable', 'loading', 'edit_modal_id', 'delete_modal_id']
  }
</script>
