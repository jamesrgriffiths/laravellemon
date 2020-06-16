<!-- Shows a user type with options for editing or deleting them in a row format
  index - The index of the user type - used for row coloring.
  user_type - The user_type data object.
  loading - The loading attribute.
  assign_modal_id - The id for referencing the assign route modal.
  edit_modal_id - The id for referencing the edit modal.
  delete_modal_id - The id for referencing the delete modal.
-->
<template>
  <div class="row p-2" v-bind:class="index%2 ? '' : 'bg-light'">
    <div class="col col-12 col-md-3 h5">{{user_type.name}}: <small class="text-info ">{{user_type.user_count}} users</small></div>
    <div class="col col-12 col-md-6">
      <button type="button" v-for="route in user_type.routes" v-if="route.active == 1" class="btn btn-sm btn-info m-1" disabled>{{route.name}} </button>
    </div>
    <div class="col col-12 col-md-3 text-right">
      <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" :data-target="'#'+assign_modal_id" :disabled="loading">Assign Routes</button>
      <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" :data-target="'#'+edit_modal_id" :disabled="loading">Edit</button>
      <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" :data-target="'#'+delete_modal_id" :disabled="loading">Delete</button>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['index','user_type','loading','assign_modal_id','edit_modal_id','delete_modal_id']
  }
</script>
